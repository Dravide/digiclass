<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Perpustakaan;
use App\Models\KelasSiswa;
use App\Models\TahunPelajaran;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    private $importedCount = 0;
    private $errors = [];
    private $tahunPelajaranId;

    public function __construct($tahunPelajaranId = null)
    {
        $this->tahunPelajaranId = $tahunPelajaranId;
    }

    public function model(array $row)
    {
        try {
            // Cari kelas berdasarkan nama
            $kelas = Kelas::where('nama_kelas', trim($row['kelas']))->first();
            if (!$kelas) {
                $this->errors[] = "Kelas '{$row['kelas']}' tidak ditemukan untuk siswa {$row['nama_siswa']}";
                return null;
            }

            // Get tahun pelajaran
            if ($this->tahunPelajaranId) {
                $tahunPelajaran = TahunPelajaran::find($this->tahunPelajaranId);
            } else {
                $tahunPelajaran = TahunPelajaran::where('is_active', true)->first();
            }
            
            if (!$tahunPelajaran) {
                $this->errors[] = "Tahun pelajaran tidak ditemukan untuk siswa {$row['nama_siswa']}";
                return null;
            }

            // Cari guru berdasarkan nama dan assign ke kelas jika belum ada
            $guru = null;
            if (!empty($row['guru'])) {
                $guru = Guru::where('nama_guru', trim($row['guru']))->first();
                if (!$guru) {
                    $this->errors[] = "Guru '{$row['guru']}' tidak ditemukan untuk siswa {$row['nama_siswa']}";
                } else {
                    // Assign guru ke kelas jika kelas belum memiliki guru
                    if (!$kelas->guru_id) {
                        $kelas->update(['guru_id' => $guru->id]);
                    }
                }
            }



            // Tentukan status perpustakaan
            $terpenuhi = false;
            if (!empty($row['perpus'])) {
                $perpusValue = strtolower(trim($row['perpus']));
                if (in_array($perpusValue, ['ya', 'yes', '1', 'aktif', 'true'])) {
                    $terpenuhi = true;
                }
            }

            // Buat siswa baru
            $siswa = new Siswa([
                'nama_siswa' => trim($row['nama_siswa']),
                'jk' => strtoupper(trim($row['jk'])),
                'nisn' => trim($row['nisn']),
                'nis' => trim($row['nis']),
                'tahun_pelajaran_id' => $tahunPelajaran->id
            ]);

            // Simpan siswa terlebih dahulu
            $siswa->save();
            
            // Create KelasSiswa record
            KelasSiswa::create([
                'siswa_id' => $siswa->id,
                'kelas_id' => $kelas->id,
                'tahun_pelajaran_id' => $tahunPelajaran->id
            ]);

            // Buat data perpustakaan
            Perpustakaan::create([
                'siswa_id' => $siswa->id,
                'terpenuhi' => $terpenuhi,
                'keterangan' => $terpenuhi ? 'Persyaratan perpustakaan sudah terpenuhi (import)' : 'Persyaratan perpustakaan belum terpenuhi (import)',
                'tanggal_pemenuhan' => $terpenuhi ? now() : null
            ]);

            $this->importedCount++;
            return $siswa;

        } catch (\Exception $e) {
            $this->errors[] = "Error processing row for {$row['nama_siswa']}: " . $e->getMessage();
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'nama_siswa' => 'required|string|max:255',
            'jk' => 'required|in:L,P,l,p',
            'nisn' => 'required|string|unique:siswa,nisn',
            'nis' => 'required|string|unique:siswa,nis',
            'kelas' => 'required|string',
            'guru' => 'nullable|string',

        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_siswa.required' => 'Nama siswa harus diisi',
            'jk.required' => 'Jenis kelamin harus diisi',
            'jk.in' => 'Jenis kelamin harus L atau P',
            'nisn.required' => 'NISN harus diisi',
            'nisn.unique' => 'NISN sudah terdaftar',
            'nis.required' => 'NIS harus diisi',
            'nis.unique' => 'NIS sudah terdaftar',
            'kelas.required' => 'Kelas harus diisi',

        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}