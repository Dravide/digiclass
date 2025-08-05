<?php

namespace App\Imports;

use App\Models\JenisPelanggaran;
use App\Models\KategoriPelanggaran;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class JenisPelanggaranImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    private $importedCount = 0;
    private $errors = [];

    public function model(array $row)
    {
        try {
            // Cari atau buat kategori pelanggaran berdasarkan kode
            $kategori = KategoriPelanggaran::firstOrCreate(
                ['kode_kategori' => trim($row['kode_kategori'])],
                [
                    'nama_kategori' => 'Kategori ' . trim($row['kode_kategori']),
                    'deskripsi_kategori' => 'Kategori dibuat otomatis dari import'
                ]
            );

            // Cek apakah jenis pelanggaran sudah ada
            $existingJenis = JenisPelanggaran::where('kode_pelanggaran', trim($row['kode_pelanggaran']))
                                            ->where('kategori_pelanggaran_id', $kategori->id)
                                            ->first();
            
            if ($existingJenis) {
                // Update jika sudah ada
                $existingJenis->update([
                    'nama_pelanggaran' => trim($row['nama_pelanggaran']),
                    'deskripsi_pelanggaran' => trim($row['deskripsi_pelanggaran'] ?? ''),
                    'poin_pelanggaran' => (int) ($row['poin_pelanggaran'] ?? 0),
                    'tingkat_pelanggaran' => strtolower(trim($row['tingkat_pelanggaran'] ?? 'ringan')),
                    'is_active' => filter_var($row['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN)
                ]);
                
                $this->importedCount++;
                return null;
            }

            // Buat jenis pelanggaran baru
            $jenisPelanggaran = new JenisPelanggaran([
                'kategori_pelanggaran_id' => $kategori->id,
                'kode_pelanggaran' => trim($row['kode_pelanggaran']),
                'nama_pelanggaran' => trim($row['nama_pelanggaran']),
                'deskripsi_pelanggaran' => trim($row['deskripsi_pelanggaran'] ?? ''),
                'poin_pelanggaran' => (int) ($row['poin_pelanggaran'] ?? 0),
                'tingkat_pelanggaran' => strtolower(trim($row['tingkat_pelanggaran'] ?? 'ringan')),
                'is_active' => filter_var($row['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN)
            ]);

            $this->importedCount++;
            return $jenisPelanggaran;
            
        } catch (\Exception $e) {
            $this->errors[] = "Error pada baris {$row['kode_pelanggaran']}: " . $e->getMessage();
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'kode_kategori' => 'required|string|max:20',
            'kode_pelanggaran' => 'required|string|max:20',
            'nama_pelanggaran' => 'required|string|max:255',
            'deskripsi_pelanggaran' => 'nullable|string',
            'poin_pelanggaran' => 'nullable|integer|min:0|max:100',
            'tingkat_pelanggaran' => 'nullable|in:ringan,sedang,berat,sangat_berat',
            'is_active' => 'nullable|boolean'
        ];
    }

    public function customValidationMessages()
    {
        return [
            'kode_kategori.required' => 'Kode kategori wajib diisi',
            'kode_kategori.max' => 'Kode kategori maksimal 20 karakter',
            'kode_pelanggaran.required' => 'Kode pelanggaran wajib diisi',
            'kode_pelanggaran.max' => 'Kode pelanggaran maksimal 20 karakter',
            'nama_pelanggaran.required' => 'Nama pelanggaran wajib diisi',
            'nama_pelanggaran.max' => 'Nama pelanggaran maksimal 255 karakter',
            'poin_pelanggaran.integer' => 'Poin pelanggaran harus berupa angka',
            'poin_pelanggaran.min' => 'Poin pelanggaran minimal 0',
            'poin_pelanggaran.max' => 'Poin pelanggaran maksimal 100',
            'tingkat_pelanggaran.in' => 'Tingkat pelanggaran harus ringan, sedang, berat, atau sangat berat',
            'is_active.boolean' => 'Status aktif harus berupa true/false atau 1/0'
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