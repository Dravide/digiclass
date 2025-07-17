<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunPelajaran;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PublicExportController extends Controller
{
    public function exportDaftarHadir(Request $request)
    {
        $kelasId = $request->get('kelas_id');
        $bulan = (int) $request->get('bulan', date('n')); // Default bulan sekarang, convert to integer
        $tahun = (int) $request->get('tahun', date('Y')); // Default tahun sekarang, convert to integer
        
        // Validasi input
        if (!$kelasId) {
            return response()->json(['error' => 'Kelas harus dipilih'], 400);
        }
        
        // Validasi bulan (1-12)
        if ($bulan < 1 || $bulan > 12) {
            return response()->json(['error' => 'Bulan tidak valid. Harus antara 1-12'], 400);
        }
        
        // Ambil data kelas
        $kelas = Kelas::with(['guru', 'tahunPelajaran'])->find($kelasId);
        if (!$kelas) {
            return response()->json(['error' => 'Kelas tidak ditemukan'], 404);
        }
        
        // Gunakan tahun pelajaran dari kelas
        $tahunPelajaranId = $kelas->tahun_pelajaran_id;
        
        // Ambil data tahun pelajaran
        $tahunPelajaran = TahunPelajaran::find($tahunPelajaranId);
        if (!$tahunPelajaran) {
            return response()->json(['error' => 'Tahun Pelajaran tidak ditemukan'], 404);
        }
        
        // Ambil data siswa berdasarkan kelas dan tahun pelajaran (hanya siswa aktif)
        $siswaList = Siswa::whereHas('kelasSiswa', function($query) use ($kelasId, $tahunPelajaranId) {
            $query->where('kelas_id', $kelasId)
                  ->where('tahun_pelajaran_id', $tahunPelajaranId);
        })
        ->where('status', Siswa::STATUS_AKTIF)
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
        
        return $pdf->download($filename);
    }
    
    public function exportDaftarNilai(Request $request)
    {
        $kelasId = $request->get('kelas_id');
        $mataPelajaranId = $request->get('mata_pelajaran_id');
        
        // Validasi input
        if (!$kelasId) {
            return response()->json(['error' => 'Kelas harus dipilih'], 400);
        }
        
        if (!$mataPelajaranId) {
            return response()->json(['error' => 'Mata pelajaran harus dipilih'], 400);
        }
        
        // Ambil data kelas
        $kelas = Kelas::with(['tahunPelajaran', 'guru'])->find($kelasId);
        if (!$kelas) {
            return response()->json(['error' => 'Kelas tidak ditemukan'], 404);
        }
        
        // Ambil mata pelajaran
        $mataPelajaran = MataPelajaran::find($mataPelajaranId);
        if (!$mataPelajaran) {
            return response()->json(['error' => 'Mata pelajaran tidak ditemukan'], 404);
        }
        
        // Ambil daftar siswa dalam kelas (hanya siswa aktif)
        $siswaList = Siswa::whereHas('kelasSiswa', function($query) use ($kelasId) {
            $query->where('kelas_id', $kelasId)
                  ->whereHas('tahunPelajaran', function($subQuery) {
                      $subQuery->where('is_active', true);
                  });
        })
        ->where('status', Siswa::STATUS_AKTIF)
        ->orderBy('nama_siswa')->get();
        
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
    
    public function getKelas()
    {
        $kelas = Kelas::with(['tahunPelajaran', 'guru'])
                     ->whereHas('tahunPelajaran', function($query) {
                         $query->where('is_active', true);
                     })
                     ->orderBy('nama_kelas')
                     ->get();
        
        return response()->json($kelas);
    }
    
    public function getMataPelajaran()
    {
        $mataPelajaran = MataPelajaran::orderBy('nama_mapel')->get();
        
        return response()->json($mataPelajaran);
    }
    
    private function generateTanggalBulan($bulan, $tahun)
    {
        $tanggalList = [];
        $jumlahHari = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;
        
        for ($i = 1; $i <= $jumlahHari; $i++) {
            $tanggalList[] = $i;
        }
        
        return $tanggalList;
    }
}