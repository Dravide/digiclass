<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunPelajaran;

echo "=== Current Data Check ===\n";

// Check Tahun Pelajaran
$tahunPelajaran = TahunPelajaran::all();
echo "\nTahun Pelajaran (" . $tahunPelajaran->count() . "):";
foreach ($tahunPelajaran as $tp) {
    echo "\n- ID: {$tp->id}, Nama: {$tp->nama_tahun_pelajaran}, Active: " . ($tp->is_active ? 'Yes' : 'No');
}

// Check Kelas
$kelas = Kelas::all();
echo "\n\nKelas (" . $kelas->count() . "):";
foreach ($kelas as $k) {
    echo "\n- ID: {$k->id}, Nama: {$k->nama_kelas}, Tingkat: {$k->tingkat}, Tahun: {$k->tahun_pelajaran_id}";
}

// Check Siswa
$siswa = Siswa::all();
echo "\n\nSiswa (" . $siswa->count() . "):";
foreach ($siswa->take(10) as $s) {
    echo "\n- ID: {$s->id}, Nama: {$s->nama_siswa}, Kelas: {$s->kelas_id}, Tahun: {$s->tahun_pelajaran_id}";
}
if ($siswa->count() > 10) {
    echo "\n... and " . ($siswa->count() - 10) . " more";
}

echo "\n\n=== End Check ===\n";