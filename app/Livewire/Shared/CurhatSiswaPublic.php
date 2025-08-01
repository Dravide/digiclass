<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use App\Models\CurhatSiswa as CurhatSiswaModel;
use App\Models\TahunPelajaran;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CurhatSiswaPublic extends Component
{
    // Access control
    public $isAccessGranted = false;
    public $accessCode = 'urhfvierureiguehviguhehrvgieruhvug8gve5ht9e8h9g58eh95ge5hg95eh5g98veg98u98uv4g5u95v84';
    
    // Form properties
    public $kategori = '';
    public $judul = '';
    public $isi_curhat = '';
    public $is_anonim = '0';
    public $nama_siswa = '';
    public $kelas_siswa = '';
    
    public $showAlert = false;
    public $alertType = 'success';
    public $alertMessage = '';
    
    protected $rules = [
        'kategori' => 'required',
        'judul' => 'required|min:5|max:100',
        'isi_curhat' => 'required|min:20|max:1000',
        'nama_siswa' => 'required_if:is_anonim,0|min:3|max:100',
        'kelas_siswa' => 'required_if:is_anonim,0|min:1|max:50'
    ];
    
    protected $messages = [
        'kategori.required' => 'Kategori curhat harus dipilih',
        'judul.required' => 'Judul curhat harus diisi',
        'judul.min' => 'Judul minimal 5 karakter',
        'judul.max' => 'Judul maksimal 100 karakter',
        'isi_curhat.required' => 'Isi curhat harus diisi',
        'isi_curhat.min' => 'Isi curhat minimal 20 karakter',
        'isi_curhat.max' => 'Isi curhat maksimal 1000 karakter',
        'nama_siswa.required_if' => 'Nama siswa harus diisi jika tidak anonim',
        'nama_siswa.min' => 'Nama siswa minimal 3 karakter',
        'nama_siswa.max' => 'Nama siswa maksimal 100 karakter',
        'kelas_siswa.required_if' => 'Kelas siswa harus diisi jika tidak anonim',
        'kelas_siswa.min' => 'Kelas siswa minimal 1 karakter',
        'kelas_siswa.max' => 'Kelas siswa maksimal 50 karakter'
    ];
    
    public $kategoriOptions = [
        'akademik' => 'Masalah Akademik',
        'sosial' => 'Masalah Sosial/Pertemanan',
        'keluarga' => 'Masalah Keluarga',
        'pribadi' => 'Masalah Pribadi',
        'bullying' => 'Bullying/Intimidasi',
        'kesehatan' => 'Masalah Kesehatan Mental',
        'karir' => 'Konsultasi Karir/Masa Depan',
        'lainnya' => 'Lainnya'
    ];
    
    protected $listeners = ['qrScanned' => 'handleQrScan'];
    
    public function handleQrScan($data)
    {
        try {
            // Check if scanned code matches access code
            if ($data === $this->accessCode) {
                $this->isAccessGranted = true;
                $this->showAlertMessage('success', 'Akses berhasil! Silakan isi form curhat.');
            } else {
                $this->showAlertMessage('error', 'Kode QR tidak valid. Silakan scan QR code yang benar.');
            }
        } catch (\Exception $e) {
            $this->showAlertMessage('error', 'Terjadi kesalahan saat memindai QR code.');
        }
    }
    
    public function submitCurhat()
    {
        $this->validate();
        
        try {
            DB::beginTransaction();
            
            $tahunPelajaran = TahunPelajaran::where('is_active', true)->first();
            
            if (!$tahunPelajaran) {
                $this->showAlertMessage('error', 'Tahun pelajaran aktif tidak ditemukan.');
                return;
            }
            
            // Simpan curhat
            CurhatSiswaModel::create([
                'siswa_id' => null, // Selalu null untuk akses publik
                'tahun_pelajaran_id' => $tahunPelajaran->id,
                'kategori' => $this->kategori,
                'judul' => $this->judul,
                'isi_curhat' => $this->isi_curhat,
                'is_anonim' => $this->is_anonim,
                'status' => 'pending',
                'tanggal_curhat' => Carbon::now(),
                'nama_pengirim' => $this->is_anonim == '1' ? 'Anonim' : $this->nama_siswa,
                'kelas_pengirim' => $this->is_anonim == '1' ? null : $this->kelas_siswa
            ]);
            
            DB::commit();
            
            $this->showAlertMessage('success', 'Curhat berhasil dikirim ke BK! Tim BK akan segera merespons curhat Anda.');
            $this->resetForm();
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->showAlertMessage('error', 'Terjadi kesalahan saat mengirim curhat: ' . $e->getMessage());
        }
    }
    
    public function toggleAnonim()
    {
        $this->is_anonim = ($this->is_anonim == '0') ? '1' : '0';
        if ($this->is_anonim == '1') {
            $this->nama_siswa = '';
            $this->kelas_siswa = '';
        }
    }
    
    public function resetForm()
    {
        $this->kategori = '';
        $this->judul = '';
        $this->isi_curhat = '';
        $this->is_anonim = '0';
        $this->nama_siswa = '';
        $this->kelas_siswa = '';
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
        return view('livewire.shared.curhat-siswa-public')->layout('layouts.main');
    }
}