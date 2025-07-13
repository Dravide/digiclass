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

            // Cari atau buat kelas berdasarkan nama dan tahun pelajaran
            $namaKelas = (string) trim($row['kelas']);
            $kelas = Kelas::where('nama_kelas', $namaKelas)
                          ->where('tahun_pelajaran_id', $tahunPelajaran->id)
                          ->first();
                          
            if (!$kelas) {
                // Buat kelas baru jika tidak ditemukan
                $tingkat = $this->extractTingkatFromKelas($namaKelas);
                $kelas = Kelas::create([
                    'nama_kelas' => $namaKelas,
                    'tingkat' => (string) $tingkat,
                    'jurusan' => null, // Will be set manually later if needed
                    'kapasitas' => 30, // Default capacity
                    'tahun_pelajaran_id' => $tahunPelajaran->id,
                    'guru_id' => null // Will be assigned later if guru exists
                ]);
            }

            // Cari guru berdasarkan nama dan assign ke kelas jika belum ada
            if (!empty($row['guru'])) {
                $guru = Guru::where('nama_guru', trim($row['guru']))->first();
                if ($guru) {
                    // Assign guru ke kelas jika kelas belum memiliki guru
                    if (!$kelas->guru_id) {
                        $kelas->update(['guru_id' => $guru->id]);
                    }
                } else {
                    $this->errors[] = "Guru '{$row['guru']}' tidak ditemukan untuk siswa {$row['nama_siswa']} - siswa tetap diimport";
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

            // Buat siswa baru dengan semua field yang diperlukan
            $siswa = Siswa::create([
                'nama_siswa' => trim($row['nama_siswa']),
                'jk' => strtoupper(trim($row['jk'])),
                'nisn' => (string) trim($row['nisn']),
                'nis' => (string) trim($row['nis']),
                'tahun_pelajaran_id' => $tahunPelajaran->id,
                'status' => Siswa::STATUS_AKTIF, // Default status aktif untuk import
                'keterangan' => Siswa::KETERANGAN_SISWA_BARU // Default keterangan siswa baru untuk import
            ]);
            
            // Pastikan kelas berhasil dibuat dan memiliki ID
            if (!$kelas || !$kelas->id) {
                $this->errors[] = "Gagal membuat atau menemukan kelas '{$namaKelas}' untuk siswa {$row['nama_siswa']}";
                return null;
            }
            
            // Pastikan siswa berhasil dibuat dan memiliki ID
            if (!$siswa || !$siswa->id) {
                $this->errors[] = "Gagal membuat siswa {$row['nama_siswa']}";
                return null;
            }
            
            // Create KelasSiswa record dengan semua field yang diperlukan
            KelasSiswa::create([
                'tahun_pelajaran_id' => $tahunPelajaran->id,
                'siswa_id' => $siswa->id,
                'kelas_id' => $kelas->id
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
    
    /**
     * Extract tingkat (grade level) from class name
     * Examples: 7A -> 7, 8B -> 8, 9IPA1 -> 9
     */
    private function extractTingkatFromKelas($namaKelas)
    {
        // Extract first digit from class name
        if (preg_match('/^(\d+)/', $namaKelas, $matches)) {
            return (int) $matches[1];
        }
        
        // Default to 7 if no digit found
        return 7;
    }

    public function rules(): array
    {
        return [
            'nama_siswa' => 'required|string|max:255',
            'jk' => 'required|in:L,P,l,p',
            'nisn' => 'required|unique:siswa,nisn',
            'nis' => 'required|unique:siswa,nis',
            'kelas' => 'required',
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