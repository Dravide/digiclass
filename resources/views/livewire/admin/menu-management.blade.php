<div>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Manajemen Menu</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Manajemen Menu</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-check-line me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filters and Actions -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Cari Menu</label>
                                <input type="text" class="form-control" wire:model.live="search" placeholder="Cari berdasarkan nama, route, atau permission...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Seksi</label>
                                <select class="form-select" wire:model.live="selectedSection">
                                    <option value="">Semua Seksi</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section }}">{{ $section }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Role</label>
                                <select class="form-select" wire:model.live="selectedRole">
                                    <option value="">Semua Role</option>
                                    @foreach($availableRoles as $role)
                                        <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="button" class="btn btn-primary" wire:click="openModal">
                                    <i class="ri-add-line me-1"></i>Tambah Menu
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Menu</th>
                                        <th>Route</th>
                                        <th>Icon</th>
                                        <th>Seksi</th>
                                        <th>Role</th>
                                        <th>Permission</th>
                                        <th>Urutan</th>
                                        <th>Status</th>
                                        <th>Parent</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($menus as $menu)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($menu->parent_id)
                                                        <span class="text-muted me-2">└─</span>
                                                    @endif
                                                    <strong>{{ $menu->title }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                @if($menu->route)
                                                    <code class="text-primary">{{ $menu->route }}</code>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <i class="{{ $menu->icon }} fs-5"></i>
                                                <small class="text-muted d-block">{{ $menu->icon }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $menu->section }}</span>
                                            </td>
                                            <td>
                                                @foreach($menu->roles as $role)
                                                    <span class="badge bg-secondary me-1">{{ ucfirst($role) }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <code class="text-success">{{ $menu->permission }}</code>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $menu->order }}</span>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" 
                                                           {{ $menu->is_active ? 'checked' : '' }}
                                                           wire:click="toggleStatus({{ $menu->id }})">
                                                </div>
                                            </td>
                                            <td>
                                                @if($menu->parent)
                                                    <small class="text-muted">{{ $menu->parent->title }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            wire:click="edit({{ $menu->id }})" title="Edit">
                                                        <i class="ri-edit-line"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            wire:click="delete({{ $menu->id }})" 
                                                            onclick="return confirm('Yakin ingin menghapus menu ini?')" title="Hapus">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="ri-menu-line fs-1 d-block mb-2"></i>
                                                    Tidak ada data menu
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $menus->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="ri-menu-line me-2"></i>
                            {{ $editMode ? 'Edit Menu' : 'Tambah Menu Baru' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Menu <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           wire:model="title" placeholder="Masukkan nama menu">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Route</label>
                                    <input type="text" class="form-control @error('route') is-invalid @enderror" 
                                           wire:model="route" placeholder="Masukkan route (opsional)">
                                    @error('route')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Icon <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                           wire:model="icon" placeholder="ri-dashboard-line">
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Gunakan class icon Remix Icon (ri-*)</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Permission <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('permission') is-invalid @enderror" 
                                           wire:model="permission" placeholder="manage-menu">
                                    @error('permission')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Seksi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('section') is-invalid @enderror" 
                                           wire:model="section" placeholder="Menu Utama">
                                    @error('section')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Urutan <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                           wire:model="order" min="0">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Parent Menu</label>
                                    <select class="form-select @error('parent_id') is-invalid @enderror" wire:model="parent_id">
                                        <option value="">Tidak ada parent (Menu utama)</option>
                                        @foreach($parentMenus as $parent)
                                            <option value="{{ $parent->id }}">{{ $parent->title }} ({{ $parent->section }})</option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Role <span class="text-danger">*</span></label>
                                    <div class="@error('roles') is-invalid @enderror">
                                        @foreach($availableRoles as $role)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" 
                                                       wire:model="roles" value="{{ $role }}" id="role_{{ $role }}">
                                                <label class="form-check-label" for="role_{{ $role }}">
                                                    {{ ucfirst($role) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('roles')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       wire:model="is_active" id="is_active">
                                                <label class="form-check-label" for="is_active">
                                                    Menu Aktif
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       wire:model="has_submenu" id="has_submenu">
                                                <label class="form-check-label" for="has_submenu">
                                                    Memiliki Submenu
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              wire:model="description" rows="3" 
                                              placeholder="Deskripsi menu (opsional)"></textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">
                            <i class="ri-close-line me-1"></i>Batal
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="save">
                            <i class="ri-save-line me-1"></i>
                            {{ $editMode ? 'Perbarui' : 'Simpan' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>