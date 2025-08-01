<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use App\Models\Siswa;
use App\Models\KategoriPelanggaran;
use App\Models\JenisPelanggaran;
use App\Models\PelanggaranSiswa;
use App\Models\TahunPelajaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PelaporanPelanggaran extends Component
{
    public $qrCode = '';
    public $selectedKategori = '';
    public $selectedJenis = '';
    public $deskripsi = '';
    public $tanggal;
    public $jam;
    public $lokasi = '';
    
    public $showAlert = false;
    public $alertType = 'success';
    public $alertMessage = '';
    
    public $jenisPelanggaranList = [];
    public $isQrValid = false;
    public $scannedSiswa = null;
    
    // QR codes yang valid untuk pelaporan (hanya bisa digunakan di area sekolah)
    public $validQrCodes = [
        'SEKOLAH_AREA_001',
        'SEKOLAH_AREA_002', 
        'SEKOLAH_AREA_003',
        'SEKOLAH_KANTIN_001',
        'SEKOLAH_PERPUS_001',
        'SEKOLAH_LAB_001',
        'SEKOLAH_KELAS_001'
    ];
    
    protected $rules = [
        'qrCode' => 'required',
        'selectedKategori' => 'required',
        'selectedJenis' => 'required', 
        'deskripsi' => 'required|min:10',
        'tanggal' => 'required|date',
        'jam' => 'required',
        'lokasi' => 'required|min:3'
    ];
    
    protected $messages = [
        'qrCode.required' => 'QR Code area sekolah harus di-scan terlebih dahulu',
        'selectedKategori.required' => 'Kategori pelanggaran harus dipilih',
        'selectedJenis.required' => 'Jenis pelanggaran harus dipilih',
        'deskripsi.required' => 'Deskripsi pelanggaran harus diisi',
        'deskripsi.min' => 'Deskripsi minimal 10 karakter',
        'tanggal.required' => 'Tanggal kejadian harus diisi',
        'tanggal.date' => 'Format tanggal tidak valid',
        'jam.required' => 'Jam kejadian harus diisi',
        'lokasi.required' => 'Lokasi kejadian harus diisi',
        'lokasi.min' => 'Lokasi minimal 3 karakter'
    ];
    
    public function mount()
    {
        $this->tanggal = Carbon::now()->format('Y-m-d');
        $this->jam = Carbon::now()->format('H:i');
    }
    
    public function updatedSelectedKategori()
    {
        $this->selectedJenis = '';
        $this->loadJenisPelanggaran();
    }
    
    public function loadJenisPelanggaran()
    {
        if ($this->selectedKategori) {
            $this->jenisPelanggaranList = JenisPelanggaran::where('kategori_pelanggaran_id', $this->selectedKategori)
                ->where('is_active', true)
                ->orderBy('nama_jenis')
                ->get();
        } else {
            $this->jenisPelanggaranList = [];
        }
    }
    
    public function processQrScan($qrCode)
    {
        try {
            // Validasi QR code area sekolah
            if (!in_array($qrCode, $this->validQrCodes)) {
                $this->showAlertMessage('error', 'QR Code tidak valid! Pastikan Anda berada di area sekolah yang memiliki QR code pelaporan.');
                return;
            }
            
            $this->qrCode = $qrCode;
            $this->isQrValid = true;
            $this->showAlertMessage('success', 'QR Code area sekolah berhasil di-scan! Anda dapat melanjutkan pelaporan.');
            
        } catch (\Exception $e) {
            $this->showAlertMessage('error', 'Terjadi kesalahan saat memproses QR code: ' . $e->getMessage());
        }
    }
    
    public function scanSiswaQr($siswaQrCode)
    {
        try {
            // Cari siswa berdasarkan NIS dari QR code
            $siswa = Siswa::where('nis', $siswaQrCode)->first();
            
            if (!$siswa) {
                $this->showAlertMessage('error', 'NIS siswa tidak ditemukan: ' . $siswaQrCode);
                return;
            }
            
            $this->scannedSiswa = $siswa;
            $this->showAlertMessage('success', 'Data siswa berhasil di-scan: ' . $siswa->nama_siswa . ' (' . $siswa->nis . ')');
            
        } catch (\Exception $e) {
            $this->showAlertMessage('error', 'Terjadi kesalahan saat memproses QR siswa: ' . $e->getMessage());
        }
    }
    
    public function submitLaporan()
    {
        if (!$this->isQrValid) {
            $this->showAlertMessage('error', 'Silakan scan QR code area sekolah terlebih dahulu!');
            return;
        }
        
        if (!$this->scannedSiswa) {
            $this->showAlertMessage('error', 'Silakan scan QR code siswa yang melanggar terlebih dahulu!');
            return;
        }
        
        $this->validate();
        
        try {
            DB::beginTransaction();
            
            $tahunPelajaran = TahunPelajaran::where('is_active', true)->first();
            $jenisPelanggaran = JenisPelanggaran::find($this->selectedJenis);
            
            // Simpan laporan pelanggaran
            PelanggaranSiswa::create([
                'siswa_id' => $this->scannedSiswa->id,
                'jenis_pelanggaran_id' => $this->selectedJenis,
                'tahun_pelajaran_id' => $tahunPelajaran->id,
                'tanggal_pelanggaran' => $this->tanggal,
                'jam_pelanggaran' => $this->jam,
                'deskripsi' => $this->deskripsi,
                'lokasi' => $this->lokasi,
                'pelapor' => Auth::user()->name,
                'pelapor_type' => 'siswa',
                'qr_area_code' => $this->qrCode,
                'poin' => $jenisPelanggaran->poin,
                'status' => 'pending',
                'catatan' => 'Dilaporkan oleh siswa melalui sistem QR'
            ]);
            
            DB::commit();
            
            $this->showAlertMessage('success', 'Laporan pelanggaran berhasil dikirim! Terima kasih atas partisipasi Anda dalam menjaga kedisiplinan sekolah.');
            $this->resetForm();
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->showAlertMessage('error', 'Terjadi kesalahan saat menyimpan laporan: ' . $e->getMessage());
        }
    }
    
    public function resetForm()
    {
        $this->qrCode = '';
        $this->selectedKategori = '';
        $this->selectedJenis = '';
        $this->deskripsi = '';
        $this->tanggal = Carbon::now()->format('Y-m-d');
        $this->jam = Carbon::now()->format('H:i');
        $this->lokasi = '';
        $this->isQrValid = false;
        $this->scannedSiswa = null;
        $this->jenisPelanggaranList = [];
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
    
    public function render()
    {
        $kategoriList = KategoriPelanggaran::orderBy('nama_kategori')
            ->get();
            
        return view('livewire.siswa.pelaporan-pelanggaran', [
            'kategoriList' => $kategoriList
        ])->layout('layouts.app');
    }
}