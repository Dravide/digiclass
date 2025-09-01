<?php

namespace App\Exports;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunPelajaran;
use App\Models\MataPelajaran;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DaftarNilaiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithCustomStartCell
{
    protected $kelasId;
    protected $mataPelajaranId;
    protected $kelas;
    protected $tahunPelajaran;
    protected $mataPelajaran;

    public function __construct($kelasId, $mataPelajaranId)
    {
        $this->kelasId = $kelasId;
        $this->mataPelajaranId = $mataPelajaranId;
        
        // Load data
        $this->kelas = Kelas::with(['guru', 'tahunPelajaran'])->find($kelasId);
        $this->tahunPelajaran = TahunPelajaran::find($this->kelas->tahun_pelajaran_id);
        $this->mataPelajaran = MataPelajaran::find($mataPelajaranId);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Siswa::whereHas('kelasSiswa', function($query) {
            $query->where('kelas_id', $this->kelasId)
                  ->whereHas('tahunPelajaran', function($subQuery) {
                      $subQuery->where('is_active', true);
                  });
        })
        ->where('status', Siswa::STATUS_AKTIF)
        ->orderBy('nama_siswa')
        ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NO',
            'NAMA SISWA',
            'NISN',
            'TUGAS 1',
            'TUGAS 2',
            'TUGAS 3',
            'TUGAS 4',
            'TUGAS 5',
            'UTS',
            'UAS',
            'RATA-RATA',
            'NILAI AKHIR',
            'GRADE',
            'KETERANGAN'
        ];
    }

    /**
     * @param mixed $siswa
     * @return array
     */
    public function map($siswa): array
    {
        static $counter = 0;
        $counter++;
        
        return [
            $counter,
            $siswa->nama_siswa,
            $siswa->nisn,
            '', // Tugas 1
            '', // Tugas 2
            '', // Tugas 3
            '', // Tugas 4
            '', // Tugas 5
            '', // UTS
            '', // UAS
            '', // Rata-rata
            '', // Nilai Akhir
            '', // Grade
            ''  // Keterangan
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Daftar Nilai ' . $this->mataPelajaran->nama_mapel;
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return 'A7'; // Start from row 7 to leave space for header info
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Add school header information
        $sheet->setCellValue('A1', 'SMPN 1 CIPANAS');
        $sheet->setCellValue('A2', 'DAFTAR NILAI SISWA');
        $sheet->setCellValue('A3', 'Kelas: ' . $this->kelas->nama_kelas);
        $sheet->setCellValue('A4', 'Mata Pelajaran: ' . $this->mataPelajaran->nama_mapel . ' (' . $this->mataPelajaran->kode_mapel . ')');
        $sheet->setCellValue('A5', 'Tahun Pelajaran: ' . $this->tahunPelajaran->nama_tahun_pelajaran);
        $sheet->setCellValue('A6', 'Wali Kelas: ' . ($this->kelas->guru->nama_guru ?? 'N/A'));

        // Style for school header
        $sheet->getStyle('A1:A6')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ],
        ]);

        // Style for main header (row 7)
        $sheet->getStyle('A7:N7')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '28a745'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Style for all data cells
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A7:N' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style for number column (A)
        $sheet->getStyle('A8:A' . $highestRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Style for name column (B)
        $sheet->getStyle('B8:B' . $highestRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ],
        ]);

        // Style for grade columns (D-N) - center alignment for scores
        $sheet->getStyle('D8:N' . $highestRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Add note at the bottom
        $noteRow = $highestRow + 2;
        $sheet->setCellValue('A' . $noteRow, 'Keterangan:');
        $sheet->setCellValue('A' . ($noteRow + 1), '- Nilai minimal untuk lulus: 75');
        $sheet->setCellValue('A' . ($noteRow + 2), '- A: 90-100, B: 80-89, C: 75-79, D: 60-74, E: <60');
        
        $sheet->getStyle('A' . $noteRow . ':A' . ($noteRow + 2))->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 10,
            ],
        ]);

        return [];
    }
}