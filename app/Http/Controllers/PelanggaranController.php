<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PelanggaranSiswa;
use App\Models\Siswa;
use App\Models\TahunPelajaran;
use App\Models\KategoriPelanggaran;
use App\Models\JenisPelanggaran;
use App\Models\SanksiPelanggaran;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PelanggaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pelanggaran.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $siswaList = Siswa::active()
                          ->with(['kelasSiswa.kelas'])
                          ->orderBy('nama_siswa')
                          ->get();
        
        $kategoriPelanggarans = KategoriPelanggaran::with('jenisPelanggaran')->get();
        $tahunPelajaranAktif = TahunPelajaran::where('is_active', true)->first();
        
        return view('pelanggaran.create', compact('siswaList', 'kategoriPelanggarans', 'tahunPelajaranAktif'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggaran,id',
            'deskripsi_pelanggaran' => 'required|string|max:1000',
            'tanggal_pelanggaran' => 'required|date',
            'pelapor' => 'required|string|max:255',
            'tindak_lanjut' => 'nullable|string|max:1000',
            'status_penanganan' => 'required|in:belum_ditangani,dalam_proses,selesai',
            'catatan' => 'nullable|string|max:1000'
        ]);

        $jenisPelanggaran = JenisPelanggaran::find($request->jenis_pelanggaran_id);
        $tahunPelajaranAktif = TahunPelajaran::where('is_active', true)->first();
        
        PelanggaranSiswa::create([
            'siswa_id' => $request->siswa_id,
            'tahun_pelajaran_id' => $tahunPelajaranAktif->id,
            'jenis_pelanggaran' => $jenisPelanggaran->nama_pelanggaran,
            'deskripsi_pelanggaran' => $request->deskripsi_pelanggaran,
            'poin_pelanggaran' => $jenisPelanggaran->poin_pelanggaran,
            'tanggal_pelanggaran' => $request->tanggal_pelanggaran,
            'pelapor' => $request->pelapor,
            'tindak_lanjut' => $request->tindak_lanjut,
            'status_penanganan' => $request->status_penanganan,
            'catatan' => $request->catatan
        ]);

        return redirect()->route('pelanggaran.index')
                        ->with('success', 'Data pelanggaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pelanggaran = PelanggaranSiswa::with(['siswa.kelasSiswa.kelas', 'tahunPelajaran'])
                                      ->findOrFail($id);
        
        return view('pelanggaran.show', compact('pelanggaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pelanggaran = PelanggaranSiswa::findOrFail($id);
        $siswaList = Siswa::active()
                          ->with(['kelasSiswa.kelas'])
                          ->orderBy('nama_siswa')
                          ->get();
        
        $kategoriPelanggarans = KategoriPelanggaran::with('jenisPelanggaran')->get();
        $tahunPelajaranAktif = TahunPelajaran::where('is_active', true)->first();
        
        return view('pelanggaran.edit', compact('pelanggaran', 'siswaList', 'kategoriPelanggarans', 'tahunPelajaranAktif'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggaran,id',
            'deskripsi_pelanggaran' => 'required|string|max:1000',
            'tanggal_pelanggaran' => 'required|date',
            'pelapor' => 'required|string|max:255',
            'tindak_lanjut' => 'nullable|string|max:1000',
            'status_penanganan' => 'required|in:belum_ditangani,dalam_proses,selesai',
            'catatan' => 'nullable|string|max:1000'
        ]);

        $pelanggaran = PelanggaranSiswa::findOrFail($id);
        $jenisPelanggaran = JenisPelanggaran::find($request->jenis_pelanggaran_id);
        
        $pelanggaran->update([
            'siswa_id' => $request->siswa_id,
            'jenis_pelanggaran' => $jenisPelanggaran->nama_pelanggaran,
            'deskripsi_pelanggaran' => $request->deskripsi_pelanggaran,
            'poin_pelanggaran' => $jenisPelanggaran->poin_pelanggaran,
            'tanggal_pelanggaran' => $request->tanggal_pelanggaran,
            'pelapor' => $request->pelapor,
            'tindak_lanjut' => $request->tindak_lanjut,
            'status_penanganan' => $request->status_penanganan,
            'catatan' => $request->catatan
        ]);

        return redirect()->route('pelanggaran.index')
                        ->with('success', 'Data pelanggaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pelanggaran = PelanggaranSiswa::findOrFail($id);
        $pelanggaran->delete();

        return redirect()->route('pelanggaran.index')
                        ->with('success', 'Data pelanggaran berhasil dihapus.');
    }

    /**
     * Get jenis pelanggaran by kategori
     */
    public function getJenisPelanggaranByKategori(Request $request)
    {
        $kategoriId = $request->kategori_id;
        
        $jenisPelanggarans = JenisPelanggaran::where('kategori_pelanggaran_id', $kategoriId)
                                            ->active()
                                            ->orderBy('nama_pelanggaran')
                                            ->get(['id', 'nama_pelanggaran', 'poin_pelanggaran', 'tingkat_pelanggaran']);
        
        return response()->json($jenisPelanggarans);
    }

    /**
     * Get laporan pelanggaran siswa
     */
    public function laporan(Request $request)
    {
        $tahunPelajaranAktif = TahunPelajaran::where('is_active', true)->first();
        $kelasList = Kelas::active()->with('tahunPelajaran')->get();
        
        $query = PelanggaranSiswa::with(['siswa.kelasSiswa.kelas', 'tahunPelajaran'])
                                ->where('tahun_pelajaran_id', $tahunPelajaranAktif->id);

        // Filter berdasarkan request
        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa.kelasSiswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id)
                  ->whereHas('tahunPelajaran', function ($tq) {
                      $tq->where('is_active', true);
                  });
            });
        }

        if ($request->filled('tanggal_mulai')) {
            $query->where('tanggal_pelanggaran', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->where('tanggal_pelanggaran', '<=', $request->tanggal_selesai);
        }

        if ($request->filled('status')) {
            $query->where('status_penanganan', $request->status);
        }

        $pelanggarans = $query->orderBy('tanggal_pelanggaran', 'desc')->get();
        
        // Statistik
        $statistik = [
            'total_pelanggaran' => $pelanggarans->count(),
            'total_siswa_melanggar' => $pelanggarans->pluck('siswa_id')->unique()->count(),
            'total_poin' => $pelanggarans->sum('poin_pelanggaran'),
            'pelanggaran_per_bulan' => $pelanggarans->groupBy(function($item) {
                return Carbon::parse($item->tanggal_pelanggaran)->format('Y-m');
            })->map->count(),
            'pelanggaran_per_kelas' => $pelanggarans->groupBy(function($item) {
                return $item->siswa->getCurrentKelas()?->nama_kelas ?? 'Tidak ada kelas';
            })->map->count(),
            'status_penanganan' => $pelanggarans->groupBy('status_penanganan')->map->count()
        ];
        
        return view('pelanggaran.laporan', compact('pelanggarans', 'kelasList', 'tahunPelajaranAktif', 'statistik'));
    }

    /**
     * Get detail siswa dengan total poin dan sanksi
     */
    public function detailSiswa($siswaId)
    {
        $siswa = Siswa::with(['kelasSiswa.kelas', 'tahunPelajaran'])->findOrFail($siswaId);
        $tahunPelajaranAktif = TahunPelajaran::where('is_active', true)->first();
        
        $pelanggarans = PelanggaranSiswa::where('siswa_id', $siswaId)
                                       ->where('tahun_pelajaran_id', $tahunPelajaranAktif->id)
                                       ->orderBy('tanggal_pelanggaran', 'desc')
                                       ->get();
        
        $totalPoin = PelanggaranSiswa::getTotalPoinSiswa($siswaId, $tahunPelajaranAktif->id);
        
        $kelas = $siswa->getCurrentKelas();
        $tingkatKelas = $kelas ? (int) substr($kelas->nama_kelas, 0, 1) : 7;
        $sanksi = SanksiPelanggaran::getSanksiByPoin($tingkatKelas, $totalPoin);
        
        return view('pelanggaran.detail-siswa', compact('siswa', 'pelanggarans', 'totalPoin', 'sanksi', 'tahunPelajaranAktif'));
    }

    /**
     * Export laporan ke Excel/PDF
     */
    public function export(Request $request)
    {
        // Implementation untuk export akan ditambahkan nanti
        // Bisa menggunakan Laravel Excel atau library lainnya
        
        return redirect()->back()->with('info', 'Fitur export sedang dalam pengembangan.');
    }

    /**
     * Dashboard pelanggaran
     */
    public function dashboard()
    {
        $tahunPelajaranAktif = TahunPelajaran::where('is_active', true)->first();
        
        // Statistik umum
        $totalPelanggaran = PelanggaranSiswa::where('tahun_pelajaran_id', $tahunPelajaranAktif->id)->count();
        $totalSiswaMelanggar = PelanggaranSiswa::where('tahun_pelajaran_id', $tahunPelajaranAktif->id)
                                             ->distinct('siswa_id')
                                             ->count('siswa_id');
        $totalPoin = PelanggaranSiswa::where('tahun_pelajaran_id', $tahunPelajaranAktif->id)
                                    ->sum('poin_pelanggaran');
        
        // Pelanggaran bulan ini
        $pelanggaranBulanIni = PelanggaranSiswa::where('tahun_pelajaran_id', $tahunPelajaranAktif->id)
                                              ->whereMonth('tanggal_pelanggaran', Carbon::now()->month)
                                              ->whereYear('tanggal_pelanggaran', Carbon::now()->year)
                                              ->count();
        
        // Top 10 siswa dengan poin tertinggi
        $topSiswa = DB::table('pelanggaran_siswa')
                     ->select('siswa_id', DB::raw('SUM(poin_pelanggaran) as total_poin'))
                     ->where('tahun_pelajaran_id', $tahunPelajaranAktif->id)
                     ->groupBy('siswa_id')
                     ->orderBy('total_poin', 'desc')
                     ->limit(10)
                     ->get()
                     ->map(function($item) {
                         $siswa = Siswa::find($item->siswa_id);
                         return [
                             'siswa' => $siswa,
                             'total_poin' => $item->total_poin,
                             'kelas' => $siswa->getCurrentKelas()?->nama_kelas ?? '-'
                         ];
                     });
        
        // Pelanggaran per kategori
        $pelanggaranPerKategori = DB::table('pelanggaran_siswa')
                                   ->join('jenis_pelanggaran', function($join) {
                                       $join->on('pelanggaran_siswa.jenis_pelanggaran', '=', 'jenis_pelanggaran.nama_pelanggaran');
                                   })
                                   ->join('kategori_pelanggaran', 'jenis_pelanggaran.kategori_pelanggaran_id', '=', 'kategori_pelanggaran.id')
                                   ->select('kategori_pelanggaran.nama_kategori', DB::raw('COUNT(*) as jumlah'))
                                   ->where('pelanggaran_siswa.tahun_pelajaran_id', $tahunPelajaranAktif->id)
                                   ->groupBy('kategori_pelanggaran.id', 'kategori_pelanggaran.nama_kategori')
                                   ->orderBy('jumlah', 'desc')
                                   ->get();
        
        // Trend pelanggaran 6 bulan terakhir
        $trendPelanggaran = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = PelanggaranSiswa::where('tahun_pelajaran_id', $tahunPelajaranAktif->id)
                                    ->whereMonth('tanggal_pelanggaran', $date->month)
                                    ->whereYear('tanggal_pelanggaran', $date->year)
                                    ->count();
            
            $trendPelanggaran[] = [
                'bulan' => $date->format('M Y'),
                'jumlah' => $count
            ];
        }
        
        return view('pelanggaran.dashboard', compact(
            'totalPelanggaran',
            'totalSiswaMelanggar', 
            'totalPoin',
            'pelanggaranBulanIni',
            'topSiswa',
            'pelanggaranPerKategori',
            'trendPelanggaran',
            'tahunPelajaranAktif'
        ));
    }
}