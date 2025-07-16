<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Presensi;
use App\Models\Jadwal;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PresensiPage extends Component
{
    public $selectedDate;
    public $presensiList = [];
    public $jadwalList = [];
    public $selectedJadwal = null;
    public $showAlert = false;
    public $alertType = 'success';
    public $alertMessage = '';

    public function mount()
    {
        $this->selectedDate = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $this->loadJadwalToday();
        $this->loadPresensiToday();
    }

    public function updatedSelectedDate()
    {
        $this->loadJadwalByDate();
        $this->loadPresensiByDate();
    }

    public function updatedSelectedJadwal()
    {
        $this->loadPresensiByDate();
    }

    public function loadJadwalToday()
    {
        $hari = strtolower(Carbon::parse($this->selectedDate)->locale('id')->dayName);
        
        $this->jadwalList = Jadwal::with(['guru', 'mataPelajaran', 'kelas'])
            ->where('hari', $hari)
            ->where('is_active', true)
            ->orderBy('jam_mulai')
            ->get();
    }

    public function loadJadwalByDate()
    {
        $hari = strtolower(Carbon::parse($this->selectedDate)->locale('id')->dayName);
        
        $this->jadwalList = Jadwal::with(['guru', 'mataPelajaran', 'kelas'])
            ->where('hari', $hari)
            ->where('is_active', true)
            ->orderBy('jam_mulai')
            ->get();
            
        $this->selectedJadwal = null;
    }

    public function loadPresensiToday()
    {
        $this->presensiList = Presensi::with(['siswa', 'jadwal.guru', 'jadwal.mataPelajaran', 'jadwal.kelas'])
            ->whereDate('tanggal', $this->selectedDate)
            ->orderBy('jam_masuk', 'desc')
            ->get();
    }

    public function loadPresensiByDate()
    {
        $query = Presensi::with(['siswa', 'jadwal.guru', 'jadwal.mataPelajaran', 'jadwal.kelas'])
            ->whereDate('tanggal', $this->selectedDate);
            
        if ($this->selectedJadwal) {
            $query->where('jadwal_id', $this->selectedJadwal);
        }
        
        $this->presensiList = $query->orderBy('jam_masuk', 'desc')->get();
    }

    public function processQrScan($qrCode)
    {
        try {
            // Cari siswa berdasarkan NIS dari QR code
            $siswa = Siswa::where('nis', $qrCode)->first();

            if (!$siswa) {
                $this->showAlertMessage('error', 'NIS tidak ditemukan: ' . $qrCode);
                return;
            }

            // Jika tidak ada jadwal yang dipilih, tampilkan error
            if (!$this->selectedJadwal) {
                $this->showAlertMessage('error', 'Silakan pilih jadwal terlebih dahulu!');
                return;
            }

            $jadwal = Jadwal::find($this->selectedJadwal);
            if (!$jadwal) {
                $this->showAlertMessage('error', 'Jadwal tidak ditemukan!');
                return;
            }

            // Cek apakah siswa ada di kelas yang sesuai dengan jadwal
            $siswaInClass = DB::table('kelas_siswa')
                ->where('siswa_id', $siswa->id)
                ->where('kelas_id', $jadwal->kelas_id)
                ->exists();

            if (!$siswaInClass) {
                $this->showAlertMessage('error', 'Siswa ' . $siswa->nama_siswa . ' tidak terdaftar di kelas ' . $jadwal->kelas->nama_kelas);
                return;
            }

            // Cari atau buat presensi
            $presensi = Presensi::where('siswa_id', $siswa->id)
                ->where('jadwal_id', $this->selectedJadwal)
                ->whereDate('tanggal', $this->selectedDate)
                ->first();

            if (!$presensi) {
                // Buat presensi baru
                $presensi = Presensi::create([
                    'siswa_id' => $siswa->id,
                    'jadwal_id' => $this->selectedJadwal,
                    'tanggal' => $this->selectedDate,
                    'jam_masuk' => Carbon::now('Asia/Jakarta')->format('H:i'),
                    'status' => $this->determineStatus($jadwal),
                    'qr_code' => $siswa->nis
                ]);
                
                $this->showAlertMessage('success', 'Presensi berhasil! Siswa: ' . $siswa->nama_siswa . ' (' . $siswa->nis . ')');
            } else {
                // Cek apakah sudah presensi hari ini
                if ($presensi->jam_masuk) {
                    $this->showAlertMessage('warning', 'Siswa ' . $siswa->nama_siswa . ' sudah melakukan presensi hari ini pada ' . $presensi->jam_masuk);
                } else {
                    // Update presensi yang sudah ada
                    $presensi->update([
                        'jam_masuk' => Carbon::now('Asia/Jakarta')->format('H:i'),
                        'status' => $this->determineStatus($jadwal)
                    ]);
                    
                    $this->showAlertMessage('success', 'Presensi berhasil! Siswa: ' . $siswa->nama_siswa . ' (' . $siswa->nis . ')');
                }
            }

            $this->loadPresensiByDate();
            
        } catch (\Exception $e) {
            $this->showAlertMessage('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function determineStatus($jadwal)
    {
        $currentTime = Carbon::now('Asia/Jakarta')->format('H:i');
        $jamMulai = $jadwal->jam_mulai->format('H:i');
        
        // Jika lebih dari 15 menit terlambat
        if ($currentTime > date('H:i', strtotime($jamMulai . ' +15 minutes'))) {
            return 'terlambat';
        }
        
        return 'hadir';
    }

    public function initializePresensiForJadwal($jadwalId)
    {
        try {
            $jadwal = Jadwal::with('kelas.siswa')->find($jadwalId);
            
            if (!$jadwal) {
                $this->showAlertMessage('error', 'Jadwal tidak ditemukan!');
                return;
            }

            // Inisialisasi presensi untuk semua siswa di kelas
            $siswaList = $jadwal->kelas->siswa;
            $initialized = 0;

            foreach ($siswaList as $siswa) {
                // Cek apakah sudah ada presensi hari ini
                $existingPresensi = Presensi::where('siswa_id', $siswa->id)
                    ->where('jadwal_id', $jadwalId)
                    ->whereDate('tanggal', $this->selectedDate)
                    ->first();

                if (!$existingPresensi) {
                    Presensi::create([
                        'siswa_id' => $siswa->id,
                        'jadwal_id' => $jadwalId,
                        'tanggal' => $this->selectedDate,
                        'status' => 'alpha',
                        'qr_code' => $siswa->nis
                    ]);
                    $initialized++;
                }
            }

            $this->showAlertMessage('success', "Presensi berhasil diinisialisasi untuk {$initialized} siswa! Siswa dapat scan QR code NIS mereka.");
            $this->loadPresensiByDate();
            
        } catch (\Exception $e) {
            $this->showAlertMessage('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function showAlertMessage($type, $message)
    {
        $this->alertType = $type;
        $this->alertMessage = $message;
        $this->showAlert = true;
    }

    public function hideAlert()
    {
        $this->showAlert = false;
    }

    public function updatePresensiManual($presensiId, $status)
    {
        try {
            $presensi = Presensi::find($presensiId);
            
            if (!$presensi) {
                $this->showAlertMessage('error', 'Data presensi tidak ditemukan!');
                return;
            }

            // Update status presensi
            $presensi->update([
                'status' => $status,
                'jam_masuk' => $status !== 'alpha' ? ($presensi->jam_masuk ?: Carbon::now('Asia/Jakarta')->format('H:i')) : null
            ]);

            $statusText = [
                'hadir' => 'Hadir',
                'terlambat' => 'Terlambat', 
                'alpha' => 'Tidak Hadir'
            ];

            $this->showAlertMessage('success', 'Status presensi ' . $presensi->siswa->nama_siswa . ' berhasil diubah menjadi ' . $statusText[$status]);
            $this->loadPresensiByDate();
            
        } catch (\Exception $e) {
            $this->showAlertMessage('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.presensi-page', [
            'totalPresensi' => count($this->presensiList),
            'totalHadir' => collect($this->presensiList)->where('status', 'hadir')->count(),
            'totalTerlambat' => collect($this->presensiList)->where('status', 'terlambat')->count(),
            'totalAlpha' => collect($this->presensiList)->where('status', 'alpha')->count(),
        ])->layout('layouts.main');
    }
}