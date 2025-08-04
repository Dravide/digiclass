<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserManagement extends Component
{
    use WithPagination;

    // Search and filter
    public $search = '';
    public $filterRole = '';
    public $perPage = 10;

    // Modal state
    public $showModal = false;
    public $isEdit = false;
    public $userId;

    // Form properties
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $selectedRole = '';
    public $is_active = true;

    // Role assignment modal
    public $showRoleModal = false;
    public $selectedUser = null;
    public $userRoles = [];

    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->userId)
            ],
            'selectedRole' => 'required|exists:roles,name',
            'is_active' => 'boolean'
        ];

        if (!$this->isEdit) {
            $rules['password'] = 'required|string|min:8|confirmed';
        } else {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        return $rules;
    }

    public function mount()
    {
        $this->resetForm();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEdit = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function editUser($userId)
    {
        $user = User::with('roles')->findOrFail($userId);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRole = $user->roles->first()->name ?? '';
        $this->is_active = (bool) $user->is_active;
        $this->password = '';
        $this->password_confirmation = '';
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function saveUser()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'is_active' => (bool) $this->is_active
            ];

            if (!$this->isEdit || !empty($this->password)) {
                $userData['password'] = Hash::make($this->password);
            }

            if ($this->isEdit) {
                $user = User::findOrFail($this->userId);
                $user->update($userData);
                
                // Update role
                $user->syncRoles([$this->selectedRole]);
                
                $message = 'User berhasil diperbarui!';
            } else {
                $user = User::create($userData);
                
                // Assign role
                $user->assignRole($this->selectedRole);
                
                $message = 'User berhasil ditambahkan!';
            }

            DB::commit();
            
            $this->dispatch($this->isEdit ? 'user-updated' : 'user-created', $message);
            $this->closeModal();
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatch('user-error', 'Gagal menyimpan user: ' . $e->getMessage());
        }
    }

    public function deleteUser($userId)
    {
        try {
            $user = User::findOrFail($userId);
            
            // Check if user has related data
            $hasGuru = Guru::where('email', $user->email)->exists();
            $hasSiswa = Siswa::where('email', $user->email)->exists();
            
            if ($hasGuru || $hasSiswa) {
                $this->dispatch('user-error', 'Tidak dapat menghapus user yang memiliki data terkait (Guru/Siswa)!');
                return;
            }
            
            $user->delete();
            $this->dispatch('user-deleted', 'User berhasil dihapus!');
            
        } catch (\Exception $e) {
            $this->dispatch('user-error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }



    public function openRoleModal($userId)
    {
        $this->selectedUser = User::with('roles')->findOrFail($userId);
        $this->userRoles = $this->selectedUser->roles->pluck('name')->toArray();
        $this->showRoleModal = true;
    }

    public function closeRoleModal()
    {
        $this->showRoleModal = false;
        $this->selectedUser = null;
        $this->userRoles = [];
    }

    public function updateUserRoles()
    {
        try {
            $this->selectedUser->syncRoles($this->userRoles);
            $this->dispatch('user-updated', 'Role user berhasil diperbarui!');
            $this->closeRoleModal();
            
        } catch (\Exception $e) {
            $this->dispatch('user-error', 'Gagal memperbarui role: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->selectedRole = '';
        $this->is_active = true;
    }

    public function render()
    {
        $users = User::with('roles')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterRole, function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', $this->filterRole);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $roles = Role::all();
        $allRoles = Role::all();

        return view('livewire.admin.user-management', [
            'users' => $users,
            'roles' => $roles,
            'allRoles' => $allRoles
        ])->layout('layouts.app');
    }
}