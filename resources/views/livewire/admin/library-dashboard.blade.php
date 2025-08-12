<div>
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Dashboard Perpustakaan</h2>
        <div class="text-muted">
            <i class="fas fa-calendar-alt"></i> {{ now()->format('d F Y') }}
        </div>
    </div>

    <!-- Statistics Cards Row 1 - Books -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Buku</h6>
                            <h3 class="mb-0">{{ number_format($bookStats['total_books']) }}</h3>
                            <small>{{ $bookStats['active_books'] }} aktif</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Buku Tersedia</h6>
                            <h3 class="mb-0">{{ number_format($bookStats['available_books']) }}</h3>
                            <small>Siap dipinjam</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-book-open fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Sedang Dipinjam</h6>
                            <h3 class="mb-0">{{ number_format($bookStats['borrowed_books']) }}</h3>
                            <small>{{ $borrowingStats['active_borrowings'] }} peminjaman</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-hand-holding fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Terlambat</h6>
                            <h3 class="mb-0">{{ number_format($borrowingStats['overdue_borrowings']) }}</h3>
                            <small>Perlu tindakan</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards Row 2 - Activities -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Pengunjung Hari Ini</h6>
                            <h3 class="mb-0">{{ number_format($attendanceStats['today_visitors']) }}</h3>
                            <small>{{ $attendanceStats['currently_present'] }} masih di perpus</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Dipinjam Hari Ini</h6>
                            <h3 class="mb-0">{{ number_format($borrowingStats['borrowed_today']) }}</h3>
                            <small>Peminjaman baru</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-arrow-up fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Dikembalikan Hari Ini</h6>
                            <h3 class="mb-0">{{ number_format($borrowingStats['returned_today']) }}</h3>
                            <small>Pengembalian</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-arrow-down fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Rata-rata Harian</h6>
                            <h3 class="mb-0">{{ number_format($attendanceStats['average_daily_visitors'], 1) }}</h3>
                            <small>Pengunjung/hari</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Recent Activities -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Aktivitas Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted">Peminjaman Terbaru</h6>
                            @forelse($recentBorrowings as $borrowing)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>
                                        <strong>{{ $borrowing->siswa->nama }}</strong><br>
                                        <small class="text-muted">{{ $borrowing->libraryBook->judul_buku }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-{{ $borrowing->status == 'dipinjam' ? 'warning' : 'success' }}">{{ ucfirst($borrowing->status) }}</span><br>
                                        <small class="text-muted">{{ $borrowing->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">Belum ada peminjaman</p>
                            @endforelse
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted">Pengunjung Hari Ini</h6>
                            @forelse($recentAttendances as $attendance)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>
                                        <strong>{{ $attendance->siswa->nama }}</strong><br>
                                        <small class="text-muted">{{ $attendance->keperluan }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-{{ $attendance->status == 'hadir' ? 'primary' : 'success' }}">{{ ucfirst($attendance->status) }}</span><br>
                                        <small class="text-muted">{{ $attendance->jam_masuk->format('H:i') }}</small>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">Belum ada pengunjung hari ini</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Books & Categories -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Buku Populer Bulan Ini</h5>
                </div>
                <div class="card-body">
                    @forelse($popularBooks as $book)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <strong>{{ $book->judul_buku }}</strong><br>
                                <small class="text-muted">{{ $book->pengarang }} - {{ $book->kategori }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-info">{{ $book->borrowings_count }} kali</span><br>
                                <small class="text-muted">dipinjam</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada data peminjaman bulan ini</p>
                    @endforelse
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Kategori Buku</h5>
                </div>
                <div class="card-body">
                    @forelse($categoryStats as $category)
                        <div class="d-flex justify-content-between align-items-center py-2">
                            <span>{{ $category->kategori }}</span>
                            <span class="badge bg-secondary">{{ $category->total }} buku</span>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada kategori buku</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Overdue Books Alert -->
    @if($overdueBooks->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Buku Terlambat Dikembalikan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Siswa</th>
                                        <th>Buku</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Seharusnya Kembali</th>
                                        <th>Terlambat</th>
                                        <th>Denda</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($overdueBooks as $overdue)
                                        <tr>
                                            <td>{{ $overdue->siswa->nama }}</td>
                                            <td>{{ $overdue->libraryBook->judul_buku }}</td>
                                            <td>{{ $overdue->tanggal_pinjam->format('d/m/Y') }}</td>
                                            <td>{{ $overdue->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                                            <td>
                                                <span class="badge bg-danger">{{ $overdue->tanggal_kembali_rencana->diffInDays(now()) }} hari</span>
                                            </td>
                                            <td>Rp {{ number_format($overdue->denda ?? 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('admin.library.books') }}" class="btn btn-primary btn-lg w-100 mb-2">
                                <i class="fas fa-book"></i><br>
                                Kelola Buku
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.library.borrowings') }}" class="btn btn-warning btn-lg w-100 mb-2">
                                <i class="fas fa-hand-holding"></i><br>
                                Kelola Peminjaman
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.library.attendance') }}" class="btn btn-info btn-lg w-100 mb-2">
                                <i class="fas fa-users"></i><br>
                                Kelola Kehadiran
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.library.reports') }}" class="btn btn-success btn-lg w-100 mb-2">
                                <i class="fas fa-chart-bar"></i><br>
                                Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
