<?php

namespace App\Livewire\Admin;

use App\Models\Menu;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class MenuManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedSection = '';
    public $selectedRole = '';
    public $showModal = false;
    public $editMode = false;
    public $menuId;

    // Form fields
    public $title = '';
    public $route = '';
    public $icon = '';
    public $permission = '';
    public $section = '';
    public $roles = [];
    public $order = 0;
    public $is_active = true;
    public $has_submenu = false;
    public $parent_id = null;
    public $description = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'route' => 'nullable|string|max:255',
        'icon' => 'required|string|max:255',
        'permission' => 'required|string|max:255',
        'section' => 'required|string|max:255',
        'roles' => 'required|array|min:1',
        'order' => 'required|integer|min:0',
        'is_active' => 'boolean',
        'has_submenu' => 'boolean',
        'parent_id' => 'nullable|exists:menus,id',
        'description' => 'nullable|string'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedSection()
    {
        $this->resetPage();
    }

    public function updatingSelectedRole()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function resetForm()
    {
        $this->title = '';
        $this->route = '';
        $this->icon = '';
        $this->permission = '';
        $this->section = '';
        $this->roles = [];
        $this->order = 0;
        $this->is_active = true;
        $this->has_submenu = false;
        $this->parent_id = null;
        $this->description = '';
        $this->menuId = null;
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $data = [
                'title' => $this->title,
                'route' => $this->route,
                'icon' => $this->icon,
                'permission' => $this->permission,
                'section' => $this->section,
                'roles' => $this->roles,
                'order' => $this->order,
                'is_active' => $this->is_active,
                'has_submenu' => $this->has_submenu,
                'parent_id' => $this->parent_id,
                'description' => $this->description
            ];

            if ($this->editMode) {
                Menu::findOrFail($this->menuId)->update($data);
                session()->flash('success', 'Menu berhasil diperbarui!');
            } else {
                Menu::create($data);
                session()->flash('success', 'Menu berhasil ditambahkan!');
            }

            DB::commit();
            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        
        $this->menuId = $menu->id;
        $this->title = $menu->title;
        $this->route = $menu->route;
        $this->icon = $menu->icon;
        $this->permission = $menu->permission;
        $this->section = $menu->section;
        $this->roles = $menu->roles ?? [];
        $this->order = $menu->order;
        $this->is_active = $menu->is_active;
        $this->has_submenu = $menu->has_submenu;
        $this->parent_id = $menu->parent_id;
        $this->description = $menu->description;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        try {
            $menu = Menu::findOrFail($id);
            
            // Check if menu has children
            if ($menu->children()->count() > 0) {
                session()->flash('error', 'Tidak dapat menghapus menu yang memiliki submenu!');
                return;
            }
            
            $menu->delete();
            session()->flash('success', 'Menu berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $menu = Menu::findOrFail($id);
            $menu->update(['is_active' => !$menu->is_active]);
            
            $status = $menu->is_active ? 'diaktifkan' : 'dinonaktifkan';
            session()->flash('success', "Menu berhasil {$status}!");
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getMenusProperty()
    {
        $query = Menu::with('parent', 'children')
            ->when($this->search, function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('route', 'like', '%' . $this->search . '%')
                  ->orWhere('permission', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedSection, function ($q) {
                $q->where('section', $this->selectedSection);
            })
            ->when($this->selectedRole, function ($q) {
                $q->whereJsonContains('roles', $this->selectedRole);
            })
            ->ordered();

        return $query->paginate(15);
    }

    public function getSectionsProperty()
    {
        return Menu::distinct()->pluck('section')->filter()->sort()->values();
    }

    public function getAvailableRolesProperty()
    {
        return ['admin', 'guru', 'siswa', 'tata_usaha', 'bk'];
    }

    public function getParentMenusProperty()
    {
        return Menu::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('section')
            ->orderBy('order')
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.menu-management', [
            'menus' => $this->menus,
            'sections' => $this->sections,
            'availableRoles' => $this->availableRoles,
            'parentMenus' => $this->parentMenus
        ])->layout('layouts.app');
    }
}