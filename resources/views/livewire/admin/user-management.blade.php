<div>
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Manajemen Users</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manajemen Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Daftar Users</h4>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" wire:click="openModal">
                                <i class="ri-add-line align-bottom me-1"></i> Tambah User
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Cari nama atau email..." wire:model.live="search">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="filterRole">
                                <option value="">Semua Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="perPage">
                                <option value="10">10 per halaman</option>
                                <option value="25">25 per halaman</option>
                                <option value="50">50 per halaman</option>
                            </select>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Tanggal Dibuat</th>
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
                                            <span class="badge bg-primary">{{ ucfirst($role->name) }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    wire:click="editUser({{ $user->id }})" title="Edit">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    wire:click="openRoleModal({{ $user->id }})" title="Kelola Role">
                                                <i class="ri-user-settings-line"></i>
                                            </button>

                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmDelete({{ $user->id }})" title="Hapus">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data user</td>
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

    <!-- Add/Edit User Modal -->
    @if($showModal)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $isEdit ? 'Edit User' : 'Tambah User' }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <form wire:submit.prevent="saveUser">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   wire:model="name" placeholder="Masukkan nama lengkap">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   wire:model="email" placeholder="Masukkan email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('selectedRole') is-invalid @enderror" wire:model="selectedRole">
                                <option value="">Pilih Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            @error('selectedRole')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password {{ $isEdit ? '(Kosongkan jika tidak ingin mengubah)' : '' }} <span class="text-danger">{{ $isEdit ? '' : '*' }}</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   wire:model="password" placeholder="Masukkan password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password {{ $isEdit ? '(Kosongkan jika tidak ingin mengubah)' : '' }} <span class="text-danger">{{ $isEdit ? '' : '*' }}</span></label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   wire:model="password_confirmation" placeholder="Konfirmasi password">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status User</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" wire:model.defer="is_active" id="is_active">
                                <label class="form-check-label" for="is_active">
                                    Aktif
                                </label>
                            </div>
                            <small class="text-muted">Centang untuk mengaktifkan user</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            {{ $isEdit ? 'Perbarui' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Role Management Modal -->
    @if($showRoleModal)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kelola Role - {{ $selectedUser->name ?? '' }}</h5>
                    <button type="button" class="btn-close" wire:click="closeRoleModal"></button>
                </div>
                <form wire:submit.prevent="updateUserRoles">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pilih Role</label>
                            <div class="row">
                                @foreach($allRoles as $role)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               value="{{ $role->name }}" 
                                               wire:model="userRoles"
                                               id="role_{{ $role->id }}">
                                        <label class="form-check-label" for="role_{{ $role->id }}">
                                            {{ ucfirst($role->name) }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeRoleModal">Batal</button>
                        <button type="submit" class="btn btn-primary">Perbarui Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>

<script>
    function confirmDelete(userId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Data user akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.deleteUser(userId);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Toast configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        // Listen for Livewire events
        document.addEventListener('livewire:init', () => {
            Livewire.on('user-created', (message) => {
                Toast.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: message
                });
            });

            Livewire.on('user-updated', (message) => {
                Toast.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: message
                });
            });

            Livewire.on('user-deleted', (message) => {
                Toast.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: message
                });
            });

            Livewire.on('user-error', (message) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: message
                });
            });
        });
    });
</script>