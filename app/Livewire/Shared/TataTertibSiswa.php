<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use App\Models\KategoriPelanggaran;
use App\Models\JenisPelanggaran;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class TataTertibSiswa extends Component
{
    public $currentPage = 1;
    public $totalPages = 0;
    public $checkedPages = [];
    public $allPagesChecked = false;
    public $kategoriPelanggarans = [];
    public $showPaktaIntegritas = false;
    
    public function mount()
    {
        $this->loadTataTertib();
        $this->calculateTotalPages();
    }
    
    public function loadTataTertib()
    {
        $this->kategoriPelanggarans = KategoriPelanggaran::with(['jenisPelanggaran' => function($query) {
            $query->where('is_active', true)
                  ->where('poin_pelanggaran', 0)
                  ->orderBy('kode_pelanggaran');
        }])
        ->orderBy('kode_kategori')
        ->get();
    }
    
    public function calculateTotalPages()
    {
        // Hitung total halaman berdasarkan kategori + 1 halaman untuk pakta integritas
        $this->totalPages = $this->kategoriPelanggarans->count() + 1;
    }
    
    public function nextPage()
    {
        if ($this->currentPage < $this->totalPages) {
            $this->currentPage++;
        }
        
        // Jika sudah sampai halaman terakhir, tampilkan pakta integritas
        if ($this->currentPage == $this->totalPages) {
            $this->showPaktaIntegritas = true;
        }
    }
    
    public function prevPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->showPaktaIntegritas = false;
        }
    }
    
    public function checkPage($pageNumber)
    {
        if (!in_array($pageNumber, $this->checkedPages)) {
            $this->checkedPages[] = $pageNumber;
        }
        
        // Cek apakah semua halaman sudah dicek
        $this->checkAllPagesRead();
    }
    
    public function checkAllPagesRead()
    {
        $this->allPagesChecked = count($this->checkedPages) >= ($this->totalPages - 1);
    }
    
    public function downloadPaktaIntegritas()
    {
        if (!$this->allPagesChecked) {
            session()->flash('error', 'Anda harus membaca semua tata tertib terlebih dahulu.');
            return;
        }
        
        // Generate PDF pakta integritas
        $paktaContent = $this->generatePaktaIntegritasContent();
        
        $pdf = Pdf::loadHTML($paktaContent);
        
        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, 'pakta-integritas-siswa.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
    
    private function generatePaktaIntegritasContent()
    {
        $content = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Pakta Integritas Siswa</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .content { line-height: 1.6; }
                .signature { margin-top: 50px; }
                .signature-box { border: 1px solid #000; height: 100px; width: 200px; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>PAKTA INTEGRITAS SISWA</h2>
                <h3>SMPN 1 CIPANAS</h3>
                <hr>
            </div>
            
            <div class="content">
                <p>Saya yang bertanda tangan di bawah ini:</p>
                <br>
                <table>
                    <tr><td width="150">Nama</td><td>: ________________________</td></tr>
                    <tr><td>NIS</td><td>: ________________________</td></tr>
                    <tr><td>Kelas</td><td>: ________________________</td></tr>
                    <tr><td>Tahun Pelajaran</td><td>: ________________________</td></tr>
                </table>
                <br>
                
                <p>Dengan ini menyatakan bahwa saya telah membaca, memahami, dan berkomitmen untuk mematuhi seluruh tata tertib yang berlaku di SMPN 1 Cipanas.</p>
                
                <p>Saya berjanji untuk:</p>
                <ol>
                    <li>Mematuhi semua peraturan dan tata tertib sekolah</li>
                    <li>Berperilaku sopan dan santun kepada guru, karyawan, dan sesama siswa</li>
                    <li>Menjaga nama baik sekolah di manapun berada</li>
                    <li>Aktif dalam kegiatan pembelajaran dan ekstrakurikuler</li>
                    <li>Menjaga kebersihan dan ketertiban lingkungan sekolah</li>
                </ol>
                
                <p>Apabila saya melanggar tata tertib yang telah ditetapkan, saya bersedia menerima sanksi sesuai dengan ketentuan yang berlaku.</p>
                
                <div class="signature">
                    <table width="100%">
                        <tr>
                            <td width="50%">
                                <p>Mengetahui,<br>Orang Tua/Wali</p>
                                <div class="signature-box"></div>
                                <p>(_____________________)</p>
                            </td>
                            <td width="50%" style="text-align: right;">
                                <p>Cipanas, _______________<br>Siswa</p>
                                <div class="signature-box"></div>
                                <p>(_____________________)</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </body>
        </html>';
        
        return $content;
    }
    
    public function getCurrentKategori()
    {
        if ($this->currentPage <= $this->kategoriPelanggarans->count()) {
            return $this->kategoriPelanggarans[$this->currentPage - 1] ?? null;
        }
        return null;
    }
    
    public function render()
    {
        return view('livewire.shared.tata-tertib-siswa', [
            'currentKategori' => $this->getCurrentKategori(),
            'isLastPage' => $this->currentPage == $this->totalPages,
            'canProceed' => in_array($this->currentPage, $this->checkedPages) || $this->currentPage == $this->totalPages
        ])->layout('layouts.main');
    }
}