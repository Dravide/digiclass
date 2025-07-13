<?php

namespace App\Imports;

use App\Models\Guru;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GuruImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    private $importedCount = 0;
    private $rowCount = 0;
    private $errors = [];

    public function model(array $row)
    {
        $this->rowCount++;
        
        try {
            // Pastikan tidak ada kolom id yang masuk ke dalam data
            unset($row['id']);
            
            // Buat guru baru tanpa menggunakan mass assignment untuk menghindari masalah ID
            $guru = new Guru();
            $guru->nama_guru = trim($row['nama_guru'] ?? '');
            $guru->nip = trim($row['nip'] ?? '');
            $guru->email = trim($row['email'] ?? '');
            $guru->telepon = trim($row['telepon'] ?? '');
            $guru->is_wali_kelas = false; // Default false, akan diatur manual
            $guru->mata_pelajaran_id = null; // Default null, akan diatur manual

            $this->importedCount++;
            return $guru;

        } catch (\Exception $e) {
            $this->errors[] = "Error processing row for {$row['nama_guru']}: " . $e->getMessage();
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'nama_guru' => 'required|string|max:255',
            'nip' => 'required|numeric|unique:gurus,nip',
            'email' => 'required|email|unique:gurus,email',
            'telepon' => 'required|numeric'
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_guru.required' => 'Nama guru harus diisi',
            'nama_guru.max' => 'Nama guru maksimal 255 karakter',
            'nip.required' => 'NIP harus diisi',
            'nip.numeric' => 'NIP harus berupa angka',
            'nip.unique' => 'NIP sudah terdaftar',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'telepon.required' => 'Telepon harus diisi',
            'telepon.numeric' => 'Telepon harus berupa angka'
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

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function onError(\Throwable $e)
    {
        $this->errors[] = $e->getMessage();
    }
}