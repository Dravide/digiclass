<?php

namespace App\Exports;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunPelajaran;
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

class DaftarHadirExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithCustomStartCell
{
    protected $kelasId;
    protected $bulan;
    protected $tahun;
    protected $kelas;
    protected $tahunPelajaran;
    protected $tanggalList;
    protected $namaBulan;

    public function __construct($kelasId, $bulan, $tahun)
    {
        $this->kelasId = $kelasId;
        $this->bulan = (int) $bulan;
        $this->tahun = (int) $tahun;
        
        // Load data
        $this->kelas = Kelas::with(['guru', 'tahunPelajaran'])->find($kelasId);
        $this->tahunPelajaran = TahunPelajaran::find($this->kelas->tahun_pelajaran_id);
        $this->tanggalList = $this->generateTanggalBulan($this->bulan, $this->tahun);
        
        $namaBulan = [
            1 => 'JANUARI', 2 => 'FEBRUARI', 3 => 'MARET', 4 => 'APRIL',
            5 => 'MEI', 6 => 'JUNI', 7 => 'JULI', 8 => 'AGUSTUS',
            9 => 'SEPTEMBER', 10 => 'OKTOBER', 11 => 'NOVEMBER', 12 => 'DESEMBER'
        ];
        $this->namaBulan = $namaBulan[$this->bulan];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Siswa::whereHas('kelasSiswa', function($query) {
            $query->where('kelas_id', $this->kelasId)
                  ->where('tahun_pelajaran_id', $this->kelas->tahun_pelajaran_id);
        })
        ->where('status', Siswa::STATUS_AKTIF)
        ->with(['kelasSiswa' => function($query) {
            $query->where('kelas_id', $this->kelasId)
                  ->where('tahun_pelajaran_id', $this->kelas->tahun_pelajaran_id);
        }])
        ->orderBy('nama_siswa')
        ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $headers = ['NO', 'NAMA SISWA', 'NISN'];
        
        // Add dates for the month
        foreach ($this->tanggalList as $tanggal) {
            $headers[] = $tanggal;
        }
        
        $headers[] = 'TOTAL HADIR';
        $headers[] = 'TOTAL SAKIT';
        $headers[] = 'TOTAL IZIN';
        $headers[] = 'TOTAL ALPHA';
        
        return $headers;
    }

    /**
     * @param mixed $siswa
     * @return array
     */
    public function map($siswa): array
    {
        static $counter = 0;
        $counter++;
        
        $row = [
            $counter,
            $siswa->nama_siswa,
            $siswa->nisn
        ];
        
        // Add empty cells for each date (to be filled manually)
        foreach ($this->tanggalList as $tanggal) {
            $row[] = '';
        }
        
        // Add summary columns (empty for manual calculation)
        $row[] = ''; // Total Hadir
        $row[] = ''; // Total Sakit
        $row[] = ''; // Total Izin
        $row[] = ''; // Total Alpha
        
        return $row;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Daftar Hadir ' . $this->namaBulan . ' ' . $this->tahun;
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return 'A6'; // Start from row 6 to leave space for header info
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Add school header information
        $sheet->setCellValue('A1', 'SMPN 1 CIPANAS');
        $sheet->setCellValue('A2', 'DAFTAR HADIR SISWA');
        $sheet->setCellValue('A3', 'Kelas: ' . $this->kelas->nama_kelas);
        $sheet->setCellValue('A4', 'Bulan: ' . $this->namaBulan . ' ' . $this->tahun);
        $sheet->setCellValue('A5', 'Tahun Pelajaran: ' . $this->tahunPelajaran->nama_tahun_pelajaran);

        // Style for school header
        $sheet->getStyle('A1:A5')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ],
        ]);

        // Style for main header (row 6)
        $lastColumn = $this->getLastColumn();
        $sheet->getStyle('A6:' . $lastColumn . '6')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '007BFF'],
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
        $sheet->getStyle('A6:' . $lastColumn . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Style for number column (A)
        $sheet->getStyle('A7:A' . $highestRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Style for name column (B)
        $sheet->getStyle('B7:B' . $highestRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ],
        ]);

        return [];
    }

    /**
     * Generate list of dates in a month
     */
    private function generateTanggalBulan($bulan, $tahun)
    {
        $tanggalList = [];
        $jumlahHari = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;
        
        for ($i = 1; $i <= $jumlahHari; $i++) {
            $tanggalList[] = $i;
        }
        
        return $tanggalList;
    }

    /**
     * Get the last column letter based on the number of dates
     */
    private function getLastColumn()
    {
        $columnCount = 3 + count($this->tanggalList) + 4; // NO, NAMA, NISN + dates + 4 summary columns
        return $this->numberToColumnLetter($columnCount);
    }

    /**
     * Convert column number to letter
     */
    private function numberToColumnLetter($number)
    {
        $letter = '';
        while ($number > 0) {
            $number--;
            $letter = chr($number % 26 + 65) . $letter;
            $number = intval($number / 26);
        }
        return $letter;
    }
}