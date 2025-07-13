<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunPelajaran;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ExportController extends Controller
{
    public function exportDaftarHadir(Request $request, $kelasId)
    {
        $tahunPelajaranId = $request->get('tahun_pelajaran_id');
        $bulan = $request->get('bulan', date('n')); // Default bulan sekarang
        $tahun = $request->get('tahun', date('Y')); // Default tahun sekarang
        
        // Validasi input
        if (!$kelasId) {
            return back()->with('error', 'Kelas harus dipilih');
        }
        
        // Ambil data kelas
        $kelas = Kelas::with(['guru', 'tahunPelajaran'])->find($kelasId);
        if (!$kelas) {
            return back()->with('error', 'Kelas tidak ditemukan');
        }
        
        // Gunakan tahun pelajaran dari kelas jika tidak ada yang dipilih
        if (!$tahunPelajaranId) {
            $tahunPelajaranId = $kelas->tahun_pelajaran_id;
        }
        
        // Ambil data tahun pelajaran
        $tahunPelajaran = TahunPelajaran::find($tahunPelajaranId);
        if (!$tahunPelajaran) {
            return back()->with('error', 'Tahun Pelajaran tidak ditemukan');
        }
        
        // Ambil data siswa berdasarkan kelas dan tahun pelajaran
        $siswaList = Siswa::whereHas('kelasSiswa', function($query) use ($kelasId, $tahunPelajaranId) {
            $query->where('kelas_id', $kelasId)
                  ->where('tahun_pelajaran_id', $tahunPelajaranId);
        })
        ->with(['kelasSiswa' => function($query) use ($kelasId, $tahunPelajaranId) {
            $query->where('kelas_id', $kelasId)
                  ->where('tahun_pelajaran_id', $tahunPelajaranId);
        }])
        ->orderBy('nama_siswa')
        ->get();
        
        // Generate tanggal dalam bulan
        $tanggalList = $this->generateTanggalBulan($bulan, $tahun);
        
        // Nama bulan dalam bahasa Indonesia
        $namaBulan = [
            1 => 'JANUARI', 2 => 'FEBRUARI', 3 => 'MARET', 4 => 'APRIL',
            5 => 'MEI', 6 => 'JUNI', 7 => 'JULI', 8 => 'AGUSTUS',
            9 => 'SEPTEMBER', 10 => 'OKTOBER', 11 => 'NOVEMBER', 12 => 'DESEMBER'
        ];
        
        $data = [
            'kelas' => $kelas,
            'tahunPelajaran' => $tahunPelajaran,
            'siswaList' => $siswaList,
            'tanggalList' => $tanggalList,
            'bulan' => $namaBulan[$bulan],
            'tahun' => $tahun,
            'totalSiswa' => $siswaList->count(),
            'generatedAt' => Carbon::now()->format('d/m/Y H:i:s')
        ];
        
        $pdf = Pdf::loadView('exports.daftar-hadir', $data)
                  ->setPaper('a4', 'landscape')
                  ->setOptions([
                      'isHtml5ParserEnabled' => true,
                      'isPhpEnabled' => true,
                      'defaultFont' => 'Arial'
                  ]);
        
        $filename = 'Daftar_Hadir_' . $kelas->nama_kelas . '_' . $namaBulan[$bulan] . '_' . $tahun . '.pdf';
        
        return $pdf->stream($filename);
    }
    
    private function generateTanggalBulan($bulan, $tahun)
    {
        $tanggalList = [];
        $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        
        for ($i = 1; $i <= $jumlahHari; $i++) {
            $tanggalList[] = $i;
        }
        
        return $tanggalList;
    }
    
  

    public function exportDaftarNilai(Request $request, $kelasId)
    {
        // Validasi input
        $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id'
        ]);
        
        // Ambil data kelas
        $kelas = Kelas::with(['tahunPelajaran', 'guru'])->findOrFail($kelasId);
        
        // Ambil mata pelajaran
        $mataPelajaran = MataPelajaran::findOrFail($request->mata_pelajaran_id);
        
        // Ambil daftar siswa dalam kelas
        $siswaList = Siswa::whereHas('kelasSiswa', function($query) use ($kelasId) {
            $query->where('kelas_id', $kelasId)
                  ->whereHas('tahunPelajaran', function($subQuery) {
                      $subQuery->where('is_active', true);
                  });
        })->orderBy('nama_siswa')->get();
        
        $data = [
            'kelas' => $kelas,
            'tahunPelajaran' => $kelas->tahunPelajaran,
            'mataPelajaran' => $mataPelajaran,
            'siswaList' => $siswaList,
            'totalSiswa' => $siswaList->count(),
            'generatedAt' => Carbon::now()->format('d/m/Y H:i:s')
        ];
        
        $pdf = Pdf::loadView('exports.daftar-nilai', $data)
                  ->setPaper('A4', 'landscape')
                  ->setOptions([
                      'isHtml5ParserEnabled' => true,
                      'isPhpEnabled' => true,
                      'defaultFont' => 'Arial'
                  ]);
        
        $filename = 'Daftar_Nilai_' . $kelas->nama_kelas . '_' . $mataPelajaran->kode_mapel . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}