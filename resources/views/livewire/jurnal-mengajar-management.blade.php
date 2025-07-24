<div>
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Jurnal Mengajar</h1>
                <p class="mt-1 text-sm text-gray-600">Kelola jurnal mengajar guru untuk tahun pelajaran</p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <!-- View Mode Toggle -->
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <button wire:click="switchViewMode('list')" 
                            class="px-3 py-1 text-sm font-medium rounded-md transition-colors {{ $viewMode === 'list' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                        <i class="fas fa-list mr-1"></i> List
                    </button>
                    <button wire:click="switchViewMode('statistics')" 
                            class="px-3 py-1 text-sm font-medium rounded-md transition-colors {{ $viewMode === 'statistics' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                        <i class="fas fa-chart-bar mr-1"></i> Statistik
                    </button>
                </div>
                <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>Tambah Jurnal
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            <div class="flex">
                <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <div class="flex">
                <i class="fas fa-exclamation-circle mr-2 mt-0.5"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('info'))
        <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg">
            <div class="flex">
                <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                <span>{{ session('info') }}</span>
            </div>
        </div>
    @endif

    <!-- Statistics View -->
    @if($viewMode === 'statistics')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-book text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Jurnal</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['total_jurnal'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <i class="fas fa-edit text-yellow-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Draft</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['jurnal_draft'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-orange-100 rounded-lg">
                        <i class="fas fa-paper-plane text-orange-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Submitted</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['jurnal_submitted'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Approved</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['jurnal_approved'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completion Rate -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tingkat Penyelesaian</h3>
            <div class="flex items-center">
                <div class="flex-1">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Progress</span>
                        <span>{{ $statistics['completion_rate'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: {{ $statistics['completion_rate'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                    <input wire:model.live="search" type="text" placeholder="Cari materi, mata pelajaran, kelas, guru..." 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Filter Tahun Pelajaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Pelajaran</label>
                    <select wire:model.live="filterTahunPelajaran" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunPelajaran as $tp)
                            <option value="{{ $tp->id }}">{{ $tp->tahun_mulai }}/{{ $tp->tahun_selesai }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Guru -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Guru</label>
                    <select wire:model.live="filterGuru" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Guru</option>
                        @foreach($guru as $g)
                            <option value="{{ $g->id }}">{{ $g->nama_guru }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Kelas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                    <select wire:model.live="filterKelas" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Mata Pelajaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                    <select wire:model.live="filterMataPelajaran" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Mapel</option>
                        @foreach($mataPelajaran as $mp)
                            <option value="{{ $mp->id }}">{{ $mp->nama_mapel }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select wire:model.live="filterStatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="draft">Draft</option>
                        <option value="submitted">Submitted</option>
                        <option value="approved">Approved</option>
                    </select>
                </div>

                <!-- Filter Bulan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                    <select wire:model.live="filterBulan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Bulan</option>
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>

                <!-- Filter Tahun -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                    <select wire:model.live="filterTahun" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @for($year = date('Y') + 1; $year >= date('Y') - 5; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Per Page -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Per Halaman</label>
                    <select wire:model.live="perPage" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Jurnal Table -->
    @if($viewMode === 'list')
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru & Mata Pelajaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materi Ajar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Presensi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($jurnal as $j)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $j->tanggal->format('d/m/Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $j->time_format }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $j->guru->nama_guru }}</div>
                                    <div class="text-sm text-gray-500">{{ $j->jadwal->mataPelajaran->nama_mapel }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $j->jadwal->kelas->nama_kelas }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $j->materi_ajar }}">
                                        {{ $j->materi_ajar }}
                                    </div>
                                    @if($j->metode_pembelajaran)
                                        <div class="text-xs text-gray-500 mt-1">{{ $j->metode_pembelajaran }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <span class="text-green-600">{{ $j->jumlah_siswa_hadir }}</span> hadir
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <span class="text-red-600">{{ $j->jumlah_siswa_tidak_hadir }}</span> tidak hadir
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $j->attendance_percentage }}% kehadiran
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($j->status === 'draft') bg-gray-100 text-gray-800
                                        @elseif($j->status === 'submitted') bg-yellow-100 text-yellow-800
                                        @elseif($j->status === 'approved') bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst($j->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button wire:click="edit({{ $j->id }})" 
                                                class="text-blue-600 hover:text-blue-900 transition-colors"
                                                title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        @if($j->status === 'draft')
                                            <button wire:click="submitJurnal({{ $j->id }})" 
                                                    class="text-green-600 hover:text-green-900 transition-colors"
                                                    title="Submit">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        @endif
                                        
                                        @if($j->status === 'submitted')
                                            <button wire:click="approveJurnal({{ $j->id }})" 
                                                    class="text-purple-600 hover:text-purple-900 transition-colors"
                                                    title="Approve">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        @endif
                                        
                                        <button wire:click="delete({{ $j->id }})" 
                                                wire:confirm="Apakah Anda yakin ingin menghapus jurnal ini?"
                                                class="text-red-600 hover:text-red-900 transition-colors"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-book text-4xl mb-4"></i>
                                        <p class="text-lg font-medium">Belum ada jurnal mengajar</p>
                                        <p class="text-sm">Klik "Tambah Jurnal" untuk membuat jurnal baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($jurnal->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $jurnal->links() }}
                </div>
            @endif
        </div>
    @endif

    <!-- Modal for Create/Edit -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="$set('showModal', false)">
            <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white" wire:click.stop>
                <div class="mt-3">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between pb-4 border-b">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $editMode ? 'Edit Jurnal Mengajar' : 'Tambah Jurnal Mengajar' }}
                        </h3>
                        <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <form wire:submit="save" class="mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Jadwal -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jadwal <span class="text-red-500">*</span></label>
                                <select wire:model.live="jadwal_id" wire:change="loadJadwalData" 
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Jadwal</option>
                                    @foreach($availableJadwal as $jadwal)
                                        <option value="{{ $jadwal->id }}">
                                            {{ $jadwal->mataPelajaran->nama_mapel }} - {{ $jadwal->kelas->nama_kelas }} 
                                            ({{ $jadwal->hari }}, {{ $jadwal->jam_mulai->format('H:i') }}-{{ $jadwal->jam_selesai->format('H:i') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('jadwal_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Guru -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Guru <span class="text-red-500">*</span></label>
                                <select wire:model="guru_id" 
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Guru</option>
                                    @foreach($guru as $g)
                                        <option value="{{ $g->id }}">{{ $g->nama_guru }}</option>
                                    @endforeach
                                </select>
                                @error('guru_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Tanggal -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                                <input wire:model="tanggal" type="date" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('tanggal') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Jam Mulai -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai <span class="text-red-500">*</span></label>
                                <input wire:model="jam_mulai" type="time" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('jam_mulai') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Jam Selesai -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai <span class="text-red-500">*</span></label>
                                <input wire:model="jam_selesai" type="time" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('jam_selesai') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Materi Ajar -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Materi Ajar <span class="text-red-500">*</span></label>
                                <input wire:model="materi_ajar" type="text" placeholder="Masukkan materi yang diajarkan" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('materi_ajar') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Kegiatan Pembelajaran -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kegiatan Pembelajaran</label>
                                <textarea wire:model="kegiatan_pembelajaran" rows="3" placeholder="Deskripsikan kegiatan pembelajaran yang dilakukan" 
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                                @error('kegiatan_pembelajaran') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Metode Pembelajaran -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembelajaran</label>
                                <input wire:model="metode_pembelajaran" type="text" placeholder="Contoh: Ceramah, Diskusi, Praktikum, dll" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('metode_pembelajaran') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Presensi Section -->
                            <div class="md:col-span-2">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-700">Data Presensi</h4>
                                    <button type="button" wire:click="autoFillPresensi" 
                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        <i class="fas fa-sync-alt mr-1"></i>Auto Fill dari Presensi
                                    </button>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Siswa Hadir <span class="text-red-500">*</span></label>
                                        <input wire:model="jumlah_siswa_hadir" type="number" min="0" 
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        @error('jumlah_siswa_hadir') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Siswa Tidak Hadir <span class="text-red-500">*</span></label>
                                        <input wire:model="jumlah_siswa_tidak_hadir" type="number" min="0" 
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        @error('jumlah_siswa_tidak_hadir') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Kendala -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kendala</label>
                                <textarea wire:model="kendala" rows="2" placeholder="Kendala yang dihadapi selama pembelajaran" 
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                                @error('kendala') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Solusi -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Solusi</label>
                                <textarea wire:model="solusi" rows="2" placeholder="Solusi yang diterapkan untuk mengatasi kendala" 
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                                @error('solusi') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Catatan -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                <textarea wire:model="catatan" rows="2" placeholder="Catatan tambahan" 
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                                @error('catatan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select wire:model="status" 
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="draft">Draft</option>
                                    <option value="submitted">Submitted</option>
                                    <option value="approved">Approved</option>
                                </select>
                                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                            <button type="button" wire:click="$set('showModal', false)" 
                                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                {{ $editMode ? 'Perbarui' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>