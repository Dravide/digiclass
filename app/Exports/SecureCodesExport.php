<?php

namespace App\Exports;

use App\Models\SecureCode;
use App\Models\Guru;
use App\Models\TataUsaha;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SecureCodesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $search;
    
    public function __construct($search = '')
    {
        $this->search = $search;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = Auth::user();
        
        return SecureCode::with('user')
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhere('secure_code', 'like', '%' . $this->search . '%');
            })
            // Admin bisa melihat semua secure code untuk guru dan tata usaha
            // Guru dan Tata Usaha hanya bisa melihat secure code milik mereka sendiri
            ->when($user->hasRole('admin'), function ($query) {
                // Ambil semua user ID yang merupakan guru atau tata usaha
                $guruUserIds = Guru::with('user')->get()->pluck('user.id')->filter();
                $tataUsahaUserIds = TataUsaha::with('user')->get()->pluck('user.id')->filter();
                $validUserIds = $guruUserIds->merge($tataUsahaUserIds);
                
                $query->whereIn('user_id', $validUserIds);
            }, function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Pengguna',
            'Email',
            'Jabatan',
            'Secure Code',
            'Tanggal Dibuat',
            'Waktu Dibuat'
        ];
    }

    /**
     * @param mixed $secureCode
     * @return array
     */
    public function map($secureCode): array
    {
        static $counter = 0;
        $counter++;
        
        // Tentukan jabatan berdasarkan role user
        $jabatan = 'Lainnya';
        if ($secureCode->user->hasRole('guru')) {
            $jabatan = 'Guru';
        } elseif ($secureCode->user->hasRole('tata_usaha')) {
            $jabatan = 'Tata Usaha';
        }
        
        return [
            $counter,
            $secureCode->user->name,
            $secureCode->user->email,
            $jabatan,
            $secureCode->secure_code,
            $secureCode->created_at->format('d/m/Y'),
            $secureCode->created_at->format('H:i:s')
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:G1')->applyFromArray([
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

        // Style untuk semua data
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:G' . $highestRow)->applyFromArray([
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

        // Style khusus untuk kolom secure code (kolom E)
        $sheet->getStyle('E2:E' . $highestRow)->applyFromArray([
            'font' => [
                'name' => 'Courier New',
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Style untuk kolom nomor (kolom A)
        $sheet->getStyle('A2:A' . $highestRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        return [];
    }
}