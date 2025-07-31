<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RolePermissionManagement extends Component
{
    use WithPagination;

    // Tab management
    public $activeTab = 'roles';

    // Role management
    public $showRoleModal = false;
    public $editingRole = null;
    public $roleName = '';
    public $roleGuardName = 'web';
    public $selectedPermissions = [];

    // Permission management
    public $showPermissionModal = false;
    public $editingPermission = null;
    public $permissionName = '';
    public $permissionGuardName = 'web';

    // User role assignment
    public $showUserRoleModal = false;
    public $selectedUser = null;
    public $userRoles = [];

    // Search and filter
    public $searchRoles = '';
    public $searchPermissions = '';
    public $searchUsers = '';
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        return [
            'roleName' => 'required|string|max:255|unique:roles,name,' . ($this->editingRole ? $this->editingRole->id : 'NULL'),
            'roleGuardName' => 'required|string|max:255',
            'permissionName' => 'required|string|max:255|unique:permissions,name,' . ($this->editingPermission ? $this->editingPermission->id : 'NULL'),
            'permissionGuardName' => 'required|string|max:255',
        ];
    }

    protected $messages = [
        'roleName.required' => 'Nama role harus diisi.',
        'roleName.unique' => 'Nama role sudah ada.',
        'permissionName.required' => 'Nama permission harus diisi.',
        'permissionName.unique' => 'Nama permission sudah ada.',
    ];

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    // Role Management Methods
    public function openRoleModal()
    {
        $this->showRoleModal = true;
        $this->resetRoleForm();
    }

    public function closeRoleModal()
    {
        $this->showRoleModal = false;
        $this->resetRoleForm();
    }

    public function resetRoleForm()
    {
        $this->editingRole = null;
        $this->roleName = '';
        $this->roleGuardName = 'web';
        $this->selectedPermissions = [];
        $this->resetValidation();
    }

    public function editRole($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);
        $this->editingRole = $role;
        $this->roleName = $role->name;
        $this->roleGuardName = $role->guard_name;
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
        $this->showRoleModal = true;
    }

    public function saveRole()
    {
        $this->validate([
            'roleName' => 'required|string|max:255|unique:roles,name,' . ($this->editingRole ? $this->editingRole->id : 'NULL'),
            'roleGuardName' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () {
                if ($this->editingRole) {
                    // Update existing role
                    $this->editingRole->update([
                        'name' => $this->roleName,
                        'guard_name' => $this->roleGuardName,
                    ]);
                    $role = $this->editingRole;
                } else {
                    // Create new role
                    $role = Role::create([
                        'name' => $this->roleName,
                        'guard_name' => $this->roleGuardName,
                    ]);
                }

                // Sync permissions
                $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();
                $role->syncPermissions($permissions);
            });

            $this->dispatch('role-saved', $this->editingRole ? 'Role berhasil diperbarui!' : 'Role berhasil ditambahkan!');
            $this->closeRoleModal();
        } catch (\Exception $e) {
            $this->dispatch('role-error', 'Gagal menyimpan role: ' . $e->getMessage());
        }
    }

    public function deleteRole($roleId)
    {
        try {
            $role = Role::findOrFail($roleId);
            
            // Check if role is assigned to users
            $userCount = $role->users()->count();
            if ($userCount > 0) {
                $this->dispatch('role-error', 'Tidak dapat menghapus role yang masih digunakan oleh ' . $userCount . ' user!');
                return;
            }

            $role->delete();
            $this->dispatch('role-deleted', 'Role berhasil dihapus!');
        } catch (\Exception $e) {
            $this->dispatch('role-error', 'Gagal menghapus role: ' . $e->getMessage());
        }
    }

    // Permission Management Methods
    public function openPermissionModal()
    {
        $this->showPermissionModal = true;
        $this->resetPermissionForm();
    }

    public function closePermissionModal()
    {
        $this->showPermissionModal = false;
        $this->resetPermissionForm();
    }

    public function resetPermissionForm()
    {
        $this->editingPermission = null;
        $this->permissionName = '';
        $this->permissionGuardName = 'web';
        $this->resetValidation();
    }

    public function editPermission($permissionId)
    {
        $permission = Permission::findOrFail($permissionId);
        $this->editingPermission = $permission;
        $this->permissionName = $permission->name;
        $this->permissionGuardName = $permission->guard_name;
        $this->showPermissionModal = true;
    }

    public function savePermission()
    {
        $this->validate([
            'permissionName' => 'required|string|max:255|unique:permissions,name,' . ($this->editingPermission ? $this->editingPermission->id : 'NULL'),
            'permissionGuardName' => 'required|string|max:255',
        ]);

        try {
            if ($this->editingPermission) {
                // Update existing permission
                $this->editingPermission->update([
                    'name' => $this->permissionName,
                    'guard_name' => $this->permissionGuardName,
                ]);
            } else {
                // Create new permission
                Permission::create([
                    'name' => $this->permissionName,
                    'guard_name' => $this->permissionGuardName,
                ]);
            }

            $this->dispatch('permission-saved', $this->editingPermission ? 'Permission berhasil diperbarui!' : 'Permission berhasil ditambahkan!');
            $this->closePermissionModal();
        } catch (\Exception $e) {
            $this->dispatch('permission-error', 'Gagal menyimpan permission: ' . $e->getMessage());
        }
    }

    public function deletePermission($permissionId)
    {
        try {
            $permission = Permission::findOrFail($permissionId);
            
            // Check if permission is assigned to roles
            $roleCount = $permission->roles()->count();
            if ($roleCount > 0) {
                $this->dispatch('permission-error', 'Tidak dapat menghapus permission yang masih digunakan oleh ' . $roleCount . ' role!');
                return;
            }

            $permission->delete();
            $this->dispatch('permission-deleted', 'Permission berhasil dihapus!');
        } catch (\Exception $e) {
            $this->dispatch('permission-error', 'Gagal menghapus permission: ' . $e->getMessage());
        }
    }

    // User Role Assignment Methods
    public function openUserRoleModal($userId)
    {
        $user = User::with('roles')->findOrFail($userId);
        $this->selectedUser = $user;
        $this->userRoles = $user->roles->pluck('id')->toArray();
        $this->showUserRoleModal = true;
    }

    public function closeUserRoleModal()
    {
        $this->showUserRoleModal = false;
        $this->selectedUser = null;
        $this->userRoles = [];
    }

    public function saveUserRoles()
    {
        try {
            $roles = Role::whereIn('id', $this->userRoles)->get();
            $this->selectedUser->syncRoles($roles);

            $this->dispatch('user-roles-saved', 'Role user berhasil diperbarui!');
            $this->closeUserRoleModal();
        } catch (\Exception $e) {
            $this->dispatch('user-roles-error', 'Gagal memperbarui role user: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $roles = Role::with(['permissions', 'users'])
            ->when($this->searchRoles, function ($q) {
                $q->where('name', 'like', '%' . $this->searchRoles . '%');
            })
            ->paginate($this->perPage, ['*'], 'rolesPage');

        $permissions = Permission::with('roles')
            ->when($this->searchPermissions, function ($q) {
                $q->where('name', 'like', '%' . $this->searchPermissions . '%');
            })
            ->paginate($this->perPage, ['*'], 'permissionsPage');

        $users = User::with('roles')
            ->when($this->searchUsers, function ($q) {
                $q->where('name', 'like', '%' . $this->searchUsers . '%')
                  ->orWhere('email', 'like', '%' . $this->searchUsers . '%');
            })
            ->paginate($this->perPage, ['*'], 'usersPage');

        $allPermissions = Permission::orderBy('name')->get();
        $allRoles = Role::orderBy('name')->get();

        return view('livewire.admin.role-permission-management', [
            'roles' => $roles,
            'permissions' => $permissions,
            'users' => $users,
            'allPermissions' => $allPermissions,
            'allRoles' => $allRoles,
        ])
        ->layout('layouts.app');
    }
}