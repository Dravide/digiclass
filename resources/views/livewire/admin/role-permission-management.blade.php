<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Manajemen Role & Permission</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Role & Permission</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'roles' ? 'active' : '' }}" 
                                   wire:click="setActiveTab('roles')" 
                                   href="#" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-user-tag"></i></span>
                                    <span class="d-none d-sm-block">Roles</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'permissions' ? 'active' : '' }}" 
                                   wire:click="setActiveTab('permissions')" 
                                   href="#" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-key"></i></span>
                                    <span class="d-none d-sm-block">Permissions</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'users' ? 'active' : '' }}" 
                                   wire:click="setActiveTab('users')" 
                                   href="#" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-users"></i></span>
                                    <span class="d-none d-sm-block">User Roles</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles Tab -->
        @if($activeTab === 'roles')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title mb-0">Daftar Roles</h4>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary" wire:click="openRoleModal">
                                    <i class="ri-add-line align-bottom me-1"></i> Tambah Role
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Search -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Cari role..." wire:model.live="searchRoles">
                            </div>
                        </div>

                        <!-- Roles Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Role</th>
                                        <th>Guard Name</th>
                                        <th>Permissions</th>
                                        <th>Users</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->guard_name }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $role->permissions->count() }} permissions</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $role->users->count() }} users</span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    wire:click="editRole({{ $role->id }})">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmDelete('role', {{ $role->id }}, '{{ $role->name }}')">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data role</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Permissions Tab -->
        @if($activeTab === 'permissions')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title mb-0">Daftar Permissions</h4>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary" wire:click="openPermissionModal">
                                    <i class="ri-add-line align-bottom me-1"></i> Tambah Permission
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Search -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Cari permission..." wire:model.live="searchPermissions">
                            </div>
                        </div>

                        <!-- Permissions Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Permission</th>
                                        <th>Guard Name</th>
                                        <th>Roles</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($permissions as $permission)
                                    <tr>
                                        <td>{{ $permission->name }}</td>
                                        <td>{{ $permission->guard_name }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $permission->roles->count() }} roles</span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    wire:click="editPermission({{ $permission->id }})">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmDeletePermission({{ $permission->id }}, '{{ $permission->name }}')">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data permission</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        {{ $permissions->links() }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Users Tab -->
        @if($activeTab === 'users')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Manajemen Role User</h4>
                    </div>
                    <div class="card-body">
                        <!-- Search -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Cari user..." wire:model.live="searchUsers">
                            </div>
                        </div>

                        <!-- Users Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Roles</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <span class="badge bg-primary me-1">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    wire:click="openUserRoleModal({{ $user->id }})">
                                                <i class="ri-user-settings-line"></i> Atur Role
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data user</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Role Modal -->
    @if($showRoleModal)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingRole ? 'Edit Role' : 'Tambah Role' }}</h5>
                    <button type="button" class="btn-close" wire:click="closeRoleModal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="saveRole">
                        <div class="mb-3">
                            <label class="form-label">Nama Role</label>
                            <input type="text" class="form-control @error('roleName') is-invalid @enderror" 
                                   wire:model="roleName" placeholder="Masukkan nama role">
                            @error('roleName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Guard Name</label>
                            <input type="text" class="form-control @error('roleGuardName') is-invalid @enderror" 
                                   wire:model="roleGuardName" placeholder="web">
                            @error('roleGuardName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Permissions</label>
                            <div class="row">
                                @foreach($allPermissions as $permission)
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               value="{{ $permission->id }}" 
                                               wire:model="selectedPermissions"
                                               id="permission_{{ $permission->id }}">
                                        <label class="form-check-label" for="permission_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeRoleModal">Batal</button>
                    <button type="button" class="btn btn-primary" wire:click="saveRole">
                        {{ $editingRole ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Permission Modal -->
    @if($showPermissionModal)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingPermission ? 'Edit Permission' : 'Tambah Permission' }}</h5>
                    <button type="button" class="btn-close" wire:click="closePermissionModal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="savePermission">
                        <div class="mb-3">
                            <label class="form-label">Nama Permission</label>
                            <input type="text" class="form-control @error('permissionName') is-invalid @enderror" 
                                   wire:model="permissionName" placeholder="Masukkan nama permission">
                            @error('permissionName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Guard Name</label>
                            <input type="text" class="form-control @error('permissionGuardName') is-invalid @enderror" 
                                   wire:model="permissionGuardName" placeholder="web">
                            @error('permissionGuardName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closePermissionModal">Batal</button>
                    <button type="button" class="btn btn-primary" wire:click="savePermission">
                        {{ $editingPermission ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

    <!-- User Role Modal -->
    @if($showUserRoleModal && $selectedUser)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Atur Role - {{ $selectedUser->name }}</h5>
                    <button type="button" class="btn-close" wire:click="closeUserRoleModal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Roles</label>
                        @foreach($allRoles as $role)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   value="{{ $role->id }}" 
                                   wire:model="userRoles"
                                   id="user_role_{{ $role->id }}">
                            <label class="form-check-label" for="user_role_{{ $role->id }}">
                                {{ $role->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeUserRoleModal">Batal</button>
                    <button type="button" class="btn btn-primary" wire:click="saveUserRoles">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>

<script>
    function confirmDelete(type, id, name) {
        Swal.fire({
            title: 'Hapus Role?',
            text: `Apakah Anda yakin ingin menghapus role "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('deleteRole', id);
            }
        });
    }

    function confirmDeletePermission(id, name) {
        Swal.fire({
            title: 'Hapus Permission?',
            text: `Apakah Anda yakin ingin menghapus permission "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('deletePermission', id);
            }
        });
    }

    // Listen for Livewire events
    document.addEventListener('livewire:init', () => {
        Livewire.on('role-saved', (message) => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                timer: 2000,
                showConfirmButton: false
            });
        });

        Livewire.on('role-deleted', (message) => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                timer: 2000,
                showConfirmButton: false
            });
        });

        Livewire.on('role-error', (message) => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message
            });
        });

        Livewire.on('permission-saved', (message) => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                timer: 2000,
                showConfirmButton: false
            });
        });

        Livewire.on('permission-deleted', (message) => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                timer: 2000,
                showConfirmButton: false
            });
        });

        Livewire.on('permission-error', (message) => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message
            });
        });

        Livewire.on('user-roles-saved', (message) => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                timer: 2000,
                showConfirmButton: false
            });
        });

        Livewire.on('user-roles-error', (message) => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message
            });
        });
    });
</script>