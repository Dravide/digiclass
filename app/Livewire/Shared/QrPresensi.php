<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use App\Models\PresensiQr;
use App\Models\SecureCode;
use App\Models\User;
use App\Models\JamPresensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Carbon\Carbon;

class QrPresensi extends Component
{
    public string $qr_code = '';
    public string $jenis_presensi = 'masuk';
    public string $foto_webcam = ''; // Base64 encoded image

    public bool $showResult = false;
    public string $resultMessage = '';
    public string $resultType = 'success'; // success, error, warning
    public array $presensiHariIni = [];
    public ?object $lastPresensi = null;

    protected array $rules = [
        'qr_code' => 'required|string',
        'jenis_presensi' => 'required|in:masuk,pulang',
    ];

    protected array $messages = [
        'qr_code.required' => 'QR Code harus diisi.',
        'jenis_presensi.required' => 'Jenis presensi harus dipilih.',
        'jenis_presensi.in' => 'Jenis presensi tidak valid.',
    ];
    
    protected $listeners = [
        'setFotoWebcam' => 'setFotoWebcam'
    ];

    public function mount(): void
    {
        // Auto-detect jenis presensi berdasarkan jam
        $this->autoDetectJenisPresensi();
        
        // Load presensi hari ini
        $this->loadPresensiHariIni();
    }
    
    public function autoDetectJenisPresensi(): void
    {
        // Get attendance time settings for today
        $jamPresensi = JamPresensi::getJamPresensiHari();
        
        if (!$jamPresensi) {
            // Fallback to default time ranges if no settings found
            $currentHour = Carbon::now('Asia/Jakarta')->format('H');
            if ($currentHour >= 6 && $currentHour < 14) {
                $this->jenis_presensi = 'masuk';
            } else {
                $this->jenis_presensi = 'pulang';
            }
            return;
        }
        
        $currentTime = Carbon::now('Asia/Jakarta');
        $currentTimeFormatted = $currentTime->format('H:i');
        
        // Get configured time ranges
        $jamMasukMulai = Carbon::parse($jamPresensi->jam_masuk_mulai)->format('H:i');
        $jamMasukSelesai = Carbon::parse($jamPresensi->jam_masuk_selesai)->format('H:i');
        $jamPulangMulai = Carbon::parse($jamPresensi->jam_pulang_mulai)->format('H:i');
        $jamPulangSelesai = Carbon::parse($jamPresensi->jam_pulang_selesai)->format('H:i');
        
        // Determine attendance type based on configured times
        if ($currentTimeFormatted >= $jamMasukMulai && $currentTimeFormatted <= $jamMasukSelesai) {
            $this->jenis_presensi = 'masuk';
        } elseif ($currentTimeFormatted >= $jamPulangMulai && $currentTimeFormatted <= $jamPulangSelesai) {
            $this->jenis_presensi = 'pulang';
        } else {
            // Outside both ranges, determine by proximity
            $masukStart = Carbon::parse($jamMasukMulai);
            $pulangStart = Carbon::parse($jamPulangMulai);
            $now = Carbon::parse($currentTimeFormatted);
            
            $diffToMasuk = abs($now->diffInMinutes($masukStart));
            $diffToPulang = abs($now->diffInMinutes($pulangStart));
            
            $this->jenis_presensi = ($diffToMasuk <= $diffToPulang) ? 'masuk' : 'pulang';
        }
    }

    public function loadPresensiHariIni(): void
    {
        // Load presensi hari ini untuk semua staff (admin, guru, tata_usaha), urutkan dari yang terakhir
        $this->presensiHariIni = PresensiQr::with(['user'])
            ->whereDate('waktu_presensi', Carbon::now('Asia/Jakarta')->toDateString())
            ->whereHas('user', function($query) {
                $query->whereIn('role', ['admin', 'guru', 'tata_usaha']);
            })
            ->orderBy('waktu_presensi', 'desc')
            ->get()
            ->toArray();
    }

    public function prosesQrCode(): void
    {
        $this->validate();
    
        
        try {
            // Reset hasil sebelumnya
            $this->showResult = false;
            
            // Bersihkan QR code dari whitespace dan karakter tidak terlihat
            $cleanQrCode = trim($this->qr_code);
            // Hapus semua spasi (termasuk yang di tengah)
            $cleanQrCode = str_replace(' ', '', $cleanQrCode);
            // Hapus karakter non-printable dan newline
            $cleanQrCode = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $cleanQrCode);
            // Pastikan hanya karakter alphanumeric dan beberapa simbol yang diizinkan
            $cleanQrCode = preg_replace('/[^A-Z0-9]/', '', $cleanQrCode);
            
            \Log::info('QR Code cleaning process', [
                'original' => $this->qr_code,
                'original_length' => strlen($this->qr_code),
                'cleaned' => $cleanQrCode,
                'cleaned_length' => strlen($cleanQrCode)
            ]);
            
            // Simpan foto webcam jika ada (sebelum validasi QR)
        $fotoPath = null;
        if (!empty($this->foto_webcam)) {
            $fotoPath = $this->simpanFotoWebcam();
        }
        
        // Validasi jadwal presensi terlebih dahulu
        $validasiJadwal = JamPresensi::validasiJamPresensi($this->jenis_presensi);
        
        if (!$validasiJadwal['valid']) {
            // Hapus foto jika jadwal tidak valid
            if ($fotoPath) {
                $this->hapusFoto($fotoPath);
            }
            
            $this->resultMessage = $validasiJadwal['pesan'];
            $this->resultType = 'error';
            $this->showResult = true;
            $this->dispatch('auto-hide-result');
            $this->reset(['qr_code']);
            return;
        }
        
        // Cari user berdasarkan secure code dari QR
        $secureCode = SecureCode::where('secure_code', $cleanQrCode)->first();
        
        // Jika tidak ditemukan exact match, coba cari dengan fuzzy matching
        if (!$secureCode) {
            // Cari QR code yang mirip dengan toleransi kesalahan
            $allSecureCodes = SecureCode::all();
            $bestMatch = null;
            $bestSimilarity = 0;
            
            foreach ($allSecureCodes as $code) {
                $similarity = similar_text($code->secure_code, $cleanQrCode, $percent);
                if ($percent > $bestSimilarity && $percent >= 95) { // Minimal 95% similarity
                    $bestMatch = $code;
                    $bestSimilarity = $percent;
                }
            }
            
            if ($bestMatch) {
                $secureCode = $bestMatch;
                \Log::info('QR Code matched with fuzzy matching', [
                    'original_qr' => $cleanQrCode,
                    'matched_qr' => $secureCode->secure_code,
                    'similarity' => $bestSimilarity,
                    'user_id' => $secureCode->user_id
                ]);
            }
        }
        
        if (!$secureCode) {
            // Hapus foto jika QR code tidak valid
            if ($fotoPath) {
                $this->hapusFoto($fotoPath);
            }
            
            $this->resultMessage = 'QR Code tidak valid atau tidak ditemukan!';
            $this->resultType = 'error';
            $this->showResult = true;
            $this->dispatch('auto-hide-result');
            $this->reset(['qr_code']);
            return;
        }
        
        // Proses presensi dengan foto
        $presensi = PresensiQr::buatPresensi(
            $cleanQrCode,
            $this->jenis_presensi,
            $fotoPath
        );

            // Load user info untuk pesan
            $user = User::find($presensi->user_id);
            $namaUser = $user ? $user->name : 'User';
            
            // Set pesan sukses
            $jenisText = $this->jenis_presensi === 'masuk' ? 'Masuk' : 'Pulang';
            $waktuText = $presensi->waktu_presensi->format('H:i:s');
            
            $statusText = $presensi->is_terlambat ? ' (TERLAMBAT)' : '';
            $this->resultMessage = "Presensi {$jenisText} berhasil dicatat untuk {$namaUser} pada {$waktuText}{$statusText}.";
            $this->resultType = $presensi->is_terlambat ? 'warning' : 'success';
            $this->showResult = true;
            $this->lastPresensi = $presensi;
            
            // Reset form
            $this->reset(['qr_code', 'foto_webcam']);
            
            // Auto-detect jenis presensi untuk scan berikutnya
            $this->autoDetectJenisPresensi();
            
            // Reload presensi hari ini
            $this->loadPresensiHariIni();
            
            // Auto-hide result setelah 5 detik
            $this->dispatch('auto-hide-result');
            
        } catch (\Exception $e) {
            // Hapus foto jika terjadi error dalam proses presensi
            if ($fotoPath) {
                $this->hapusFoto($fotoPath);
            }
            
            \Log::error('Error processing QR presensi: ' . $e->getMessage(), [
                'qr_code' => $cleanQrCode,
                'jenis_presensi' => $this->jenis_presensi,
                'user_id' => Auth::user()->id ?? null,
                'foto_path' => $fotoPath
            ]);
            
            $this->resultMessage = $e->getMessage();
            $this->resultType = 'error';
            $this->showResult = true;
            $this->lastPresensi = null;
            
            // Reset form QR code untuk memungkinkan scan berikutnya
            $this->reset(['qr_code']);
            
            // Auto-hide result setelah 8 detik untuk error
            $this->dispatch('auto-hide-error');
        }

        // Focus QR input untuk scan berikutnya
        $this->dispatch('focus-qr-input');
    }

    public function hideResult(): void
    {
        $this->showResult = false;
        $this->lastPresensi = null;
    }

    public function resetForm(): void
    {
        $this->reset(['qr_code']);
        $this->hideResult();
        $this->dispatch('focus-qr-input');
    }
    
    public function setFotoWebcam(string $fotoBase64): void
    {
        $this->foto_webcam = $fotoBase64;
    }
    
    private function simpanFotoWebcam(): ?string
    {
        try {
            // Decode base64 image
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $this->foto_webcam));
            
            // Generate unique filename
            $filename = 'presensi_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.jpg';
            $path = 'presensi-foto/' . $filename;
            
            // Save to storage
            \Storage::disk('public')->put($path, $imageData);
            
            return $path;
        } catch (\Exception $e) {
            \Log::error('Error saving webcam photo: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Hapus foto dari storage
     */
    private function hapusFoto(?string $fotoPath): void
    {
        if ($fotoPath && \Storage::disk('public')->exists($fotoPath)) {
            try {
                \Storage::disk('public')->delete($fotoPath);
                \Log::info('Foto berhasil dihapus dari storage: ' . $fotoPath);
            } catch (\Exception $e) {
                \Log::error('Error deleting photo: ' . $e->getMessage());
            }
        }
    }

    /**
     * Bersihkan foto lama (lebih dari 30 hari)
     */
    public function bersihkanFotoLama(): void
    {
        try {
            $files = \Storage::disk('public')->files('presensi-foto');
            $deletedCount = 0;
            
            foreach ($files as $file) {
                $lastModified = \Storage::disk('public')->lastModified($file);
                $daysDiff = now()->diffInDays(\Carbon\Carbon::createFromTimestamp($lastModified));
                
                if ($daysDiff > 30) {
                    \Storage::disk('public')->delete($file);
                    $deletedCount++;
                }
            }
            
            \Log::info("Berhasil menghapus {$deletedCount} foto lama dari storage");
            
            if ($deletedCount > 0) {
                session()->flash('message', "Berhasil membersihkan {$deletedCount} foto lama dari storage.");
            }
        } catch (\Exception $e) {
            \Log::error('Error cleaning old photos: ' . $e->getMessage());
        }
    }

    /**
     * Bersihkan foto orphan (foto yang tidak terkait dengan presensi valid)
     */
    public function bersihkanFotoOrphan(): void
    {
        try {
            $files = \Storage::disk('public')->files('presensi-foto');
            $validPaths = PresensiQr::whereNotNull('foto_path')->pluck('foto_path')->toArray();
            $deletedCount = 0;
            
            foreach ($files as $file) {
                if (!in_array($file, $validPaths)) {
                    \Storage::disk('public')->delete($file);
                    $deletedCount++;
                }
            }
            
            \Log::info("Berhasil menghapus {$deletedCount} foto orphan dari storage");
            
            if ($deletedCount > 0) {
                session()->flash('message', "Berhasil membersihkan {$deletedCount} foto orphan dari storage.");
            }
        } catch (\Exception $e) {
            \Log::error('Error cleaning orphan photos: ' . $e->getMessage());
        }
    }



    public function render(): View
    {
        return view('livewire.shared.qr-presensi')
               ->layout('layouts.presensi-qr');
    }
}
