<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Siswa;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MagicLinkManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterKelas = '';
    public $filterTahunPelajaran = '';
    public $filterStatus = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterKelas()
    {
        $this->resetPage();
    }

    public function updatingFilterTahunPelajaran()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function generateMagicLink($siswaId)
    {
        try {
            Log::info('generateMagicLink called with siswaId: ' . $siswaId);
            
            $siswa = Siswa::find($siswaId);
            if (!$siswa) {
                Log::error('Siswa not found with ID: ' . $siswaId);
                $this->dispatch('magic-link-error', 'Data siswa tidak ditemukan.');
                return;
            }

            Log::info('Siswa found: ' . $siswa->nama_siswa);

            // Generate consistent token based on siswa ID (tidak generate baru)
            $token = hash('sha256', 'magic_link_' . $siswa->id . '_2026');
            $expiresAt = Carbon::create(2026, 7, 1, 23, 59, 59); // Link berlaku sampai 1 Juli 2026

            Log::info('Using existing token: ' . $token);

            // Store the token in cache (akan overwrite jika sudah ada)
            cache()->put("magic_link_{$token}", [
                'siswa_id' => $siswa->id,
                'type' => 'violation_form',
                'expires_at' => $expiresAt
            ], $expiresAt);

            // Generate the magic link URL
            $magicLink = route('magic-link-pelanggaran', ['token' => $token]);
            
            Log::info('Showing existing magic link: ' . $magicLink);

            $eventData = [
                'link' => $magicLink,
                'siswa_name' => $siswa->nama_siswa,
                'expires_at' => $expiresAt->format('d/m/Y H:i')
            ];
            
            Log::info('Dispatching magic-link-generated with data: ' . json_encode($eventData));

            $this->dispatch('magic-link-generated', $eventData);

        } catch (\Exception $e) {
            Log::error('Error in generateMagicLink: ' . $e->getMessage());
            $this->dispatch('magic-link-error', 'Gagal membuat magic link: ' . $e->getMessage());
        }
    }

    public function downloadMagicLinksByClass($kelasId)
    {
        try {
            $kelas = \App\Models\Kelas::with(['guru', 'tahunPelajaran'])->find($kelasId);
            if (!$kelas) {
                $this->dispatch('magic-link-error', 'Kelas tidak ditemukan.');
                return;
            }

            // Get students in this class
            $siswaList = Siswa::whereHas('kelasSiswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            })
            ->where('status', 'aktif')
            ->orderBy('nama_siswa', 'asc')
            ->get();

            if ($siswaList->isEmpty()) {
                $this->dispatch('magic-link-error', 'Tidak ada siswa aktif di kelas ini.');
                return;
            }

            // Generate magic links for all students
            $magicLinksData = [];
            foreach ($siswaList as $siswa) {
                $token = hash('sha256', 'magic_link_' . $siswa->id . '_2026');
                $expiresAt = \Carbon\Carbon::create(2026, 7, 1, 23, 59, 59);
                
                // Store in cache
                cache()->put("magic_link_{$token}", [
                    'siswa_id' => $siswa->id,
                    'type' => 'violation_form',
                    'expires_at' => $expiresAt
                ], $expiresAt);

                $magicLinksData[] = [
                    'nama_siswa' => $siswa->nama_siswa,
                    'nis' => $siswa->nis,
                    'nisn' => $siswa->nisn,
                    'magic_link' => route('magic-link-pelanggaran', ['token' => $token])
                ];
            }

            // Generate PDF content
            $pdf = Pdf::loadView('exports.magic-links-pdf', [
                'kelas' => $kelas,
                'siswaList' => $magicLinksData,
                'tanggal' => date('d/m/Y')
            ]);

            // Generate filename
            $fileName = 'magic-links-' . str_replace(' ', '-', strtolower($kelas->nama_kelas)) . '-' . date('Y-m-d') . '.pdf';

            // Dispatch download event
            $this->dispatch('download-pdf', [
                'content' => base64_encode($pdf->output()),
                'filename' => $fileName
            ]);

            $this->dispatch('magic-link-success', "Magic links untuk kelas {$kelas->nama_kelas} berhasil diunduh!");

        } catch (\Exception $e) {
            Log::error('Error in downloadMagicLinksByClass: ' . $e->getMessage());
            $this->dispatch('magic-link-error', 'Gagal mengunduh magic links: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Siswa::with(['tahunPelajaran', 'kelasSiswa.kelas.guru'])
            ->where('status', 'aktif');

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama_siswa', 'like', '%' . $this->search . '%')
                  ->orWhere('nisn', 'like', '%' . $this->search . '%')
                  ->orWhere('nis', 'like', '%' . $this->search . '%');
            });
        }

        // Apply class filter
        if ($this->filterKelas) {
            $query->whereHas('kelasSiswa', function ($q) {
                $q->where('kelas_id', $this->filterKelas);
            });
        }

        // Apply academic year filter
        if ($this->filterTahunPelajaran) {
            $query->where('tahun_pelajaran_id', $this->filterTahunPelajaran);
        }

        $siswaList = $query->orderBy('nama_siswa', 'asc')->paginate(15);

        // Get filter options
        $kelasList = \App\Models\Kelas::orderBy('nama_kelas', 'asc')->get();
        $tahunPelajaranOptions = \App\Models\TahunPelajaran::orderBy('nama_tahun_pelajaran', 'desc')->get();

        return view('livewire.admin.magic-link-management', [
            'siswaList' => $siswaList,
            'kelasList' => $kelasList,
            'tahunPelajaranOptions' => $tahunPelajaranOptions
        ])->layout('layouts.app');
    }
}