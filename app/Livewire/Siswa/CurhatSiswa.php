<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use App\Models\CurhatSiswa as CurhatSiswaModel;
use App\Models\TahunPelajaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CurhatSiswa extends Component
{
    public $kategori = '';
    public $judul = '';
    public $isi_curhat = '';
    public $is_anonim = false;
    
    public $showAlert = false;
    public $alertType = 'success';
    public $alertMessage = '';
    
    public $riwayatCurhat = [];
    public $showRiwayat = false;
    
    protected $rules = [
        'kategori' => 'required',
        'judul' => 'required|min:5|max:100',
        'isi_curhat' => 'required|min:20|max:1000'
    ];
    
    protected $messages = [
        'kategori.required' => 'Kategori curhat harus dipilih',
        'judul.required' => 'Judul curhat harus diisi',
        'judul.min' => 'Judul minimal 5 karakter',
        'judul.max' => 'Judul maksimal 100 karakter',
        'isi_curhat.required' => 'Isi curhat harus diisi',
        'isi_curhat.min' => 'Isi curhat minimal 20 karakter',
        'isi_curhat.max' => 'Isi curhat maksimal 1000 karakter'
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
    
    public function mount()
    {
        $this->loadRiwayatCurhat();
    }
    
    public function loadRiwayatCurhat()
    {
        $this->riwayatCurhat = CurhatSiswaModel::where('siswa_id', Auth::user()->siswa->id ?? null)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    }
    
    public function submitCurhat()
    {
        $this->validate();
        
        try {
            DB::beginTransaction();
            
            $tahunPelajaran = TahunPelajaran::where('is_active', true)->first();
            $siswa = Auth::user()->siswa;
            
            if (!$siswa) {
                $this->showAlertMessage('error', 'Data siswa tidak ditemukan. Silakan hubungi administrator.');
                return;
            }
            
            // Simpan curhat
            CurhatSiswaModel::create([
                'siswa_id' => $this->is_anonim ? null : $siswa->id,
                'tahun_pelajaran_id' => $tahunPelajaran->id,
                'kategori' => $this->kategori,
                'judul' => $this->judul,
                'isi_curhat' => $this->isi_curhat,
                'is_anonim' => $this->is_anonim,
                'status' => 'pending',
                'tanggal_curhat' => Carbon::now(),
                'nama_pengirim' => $this->is_anonim ? 'Anonim' : $siswa->nama_siswa,
                'kelas_pengirim' => $this->is_anonim ? null : $siswa->kelas->nama_kelas ?? null
            ]);
            
            DB::commit();
            
            $this->showAlertMessage('success', 'Curhat berhasil dikirim ke BK! Tim BK akan segera merespons curhat Anda.');
            $this->resetForm();
            $this->loadRiwayatCurhat();
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->showAlertMessage('error', 'Terjadi kesalahan saat mengirim curhat: ' . $e->getMessage());
        }
    }
    
    public function toggleRiwayat()
    {
        $this->showRiwayat = !$this->showRiwayat;
        if ($this->showRiwayat) {
            $this->loadRiwayatCurhat();
        }
    }
    
    public function resetForm()
    {
        $this->kategori = '';
        $this->judul = '';
        $this->isi_curhat = '';
        $this->is_anonim = false;
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
        return view('livewire.siswa.curhat-siswa')->layout('layouts.app');
    }
}