<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Manajemen Koneksi User</h3>
                        <p class="text-muted">Kelola koneksi antara akun user dengan data guru/siswa</p>
                    </div>
                    <div class="card-body">
                        <!-- Flash Messages -->
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Search and Filter -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Cari nama atau email..." wire:model.live="search">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" wire:model.live="filterRole">
                                    <option value="">Semua Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="guru">Guru</option>
                                    <option value="siswa">Siswa</option>
                                    <option value="tatausaha">Tata Usaha</option>
                                </select>
                            </div>
                        </div>

                        <!-- Users Table -->
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status Koneksi</th>
                                        <th>Data Terhubung</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'guru' ? 'primary' : ($user->role === 'siswa' ? 'success' : 'secondary')) }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($user->linked_guru || $user->linked_siswa)
                                                    <span class="badge bg-success">Terhubung</span>
                                                @else
                                                    <span class="badge bg-warning">Belum Terhubung</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($user->linked_guru)
                                                    <small class="text-muted">
                                                        <strong>Guru:</strong> {{ $user->linked_guru->nama_guru }}<br>
                                                        <strong>NIP:</strong> {{ $user->linked_guru->nip }}
                                                    </small>
                                                @elseif ($user->linked_siswa)
                                                    <small class="text-muted">
                                                        <strong>Siswa:</strong> {{ $user->linked_siswa->nama_siswa }}<br>
                                                        <strong>NIS:</strong> {{ $user->linked_siswa->nis }}
                                                    </small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($user->linked_guru || $user->linked_siswa)
                                                    <button class="btn btn-sm btn-outline-danger" 
                                                            wire:click="unlinkUser({{ $user->id }})"
                                                            wire:confirm="Yakin ingin memutus koneksi?">
                                                        <i class="fas fa-unlink"></i> Putus Koneksi
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-primary" 
                                                            wire:click="openLinkModal({{ $user->id }})">
                                                        <i class="fas fa-link"></i> Hubungkan
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Tidak ada data user</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Link Modal -->
    @if ($showLinkModal && $selectedUser)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hubungkan User: {{ $selectedUser->email }}</h5>
                        <button type="button" class="btn-close" wire:click="closeLinkModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="linkUser">
                            <!-- Link Type -->
                            <div class="mb-3">
                                <label class="form-label">Tipe Koneksi</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" wire:model.live="linkType" value="guru" id="linkTypeGuru">
                                        <label class="form-check-label" for="linkTypeGuru">Guru</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" wire:model.live="linkType" value="siswa" id="linkTypeSiswa">
                                        <label class="form-check-label" for="linkTypeSiswa">Siswa</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Guru Selection -->
                            @if ($linkType === 'guru')
                                <div class="mb-3">
                                    <label class="form-label">Pilih Guru</label>
                                    <select class="form-select" wire:model="selectedGuruId">
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach ($guruOptions as $guru)
                                            <option value="{{ $guru->id }}">
                                                {{ $guru->nama_guru }} (NIP: {{ $guru->nip }})
                                                @if ($guru->email)
                                                    - Email: {{ $guru->email }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('selectedGuruId')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <!-- Siswa Selection -->
                            @if ($linkType === 'siswa')
                                <div class="mb-3">
                                    <label class="form-label">Pilih Siswa</label>
                                    <select class="form-select" wire:model="selectedSiswaId">
                                        <option value="">-- Pilih Siswa --</option>
                                        @foreach ($siswaOptions as $siswa)
                                            <option value="{{ $siswa->id }}">
                                                {{ $siswa->nama_siswa }} (NIS: {{ $siswa->nis }})
                                                @if ($siswa->email)
                                                    - Email: {{ $siswa->email }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('selectedSiswaId')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="closeLinkModal">Batal</button>
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="linkUser">
                                    <span wire:loading.remove wire:target="linkUser">Hubungkan</span>
                                    <span wire:loading wire:target="linkUser">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Menghubungkan...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>