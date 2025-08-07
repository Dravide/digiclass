<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MagicLinkCardController extends Controller
{
    public function generatePDF($siswaId)
    {
        try {
            \Log::info('generateMagicLinkCardPDF called with siswaId: ' . $siswaId);
            
            $siswa = Siswa::find($siswaId);
            if (!$siswa) {
                \Log::error('Siswa not found with ID: ' . $siswaId);
                return response()->json(['error' => 'Data siswa tidak ditemukan.'], 404);
            }

            // Generate consistent token based on siswa ID (tidak generate baru)
            $token = hash('sha256', 'magic_link_' . $siswa->id . '_2026');
            $expiresAt = Carbon::create(2026, 7, 1, 23, 59, 59); // Link berlaku sampai 1 Juli 2026

            // Store the token in cache
            cache()->put("magic_link_{$token}", [
                'siswa_id' => $siswa->id,
                'type' => 'violation_form',
                'expires_at' => $expiresAt
            ], $expiresAt);

            // Generate the magic link URL
            $magicLink = route('magic-link-pelanggaran', ['token' => $token]);
            
            // Generate QR Code as base64 image
            $qrCode = QrCode::format('png')
                ->size(200)
                ->margin(1)
                ->generate($magicLink);
            
            $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($qrCode);

            $cardData = [
                'siswa_name' => $siswa->nama_siswa,
                'siswa_nis' => $siswa->nis,
                'siswa_nisn' => $siswa->nisn,
                'expires_at' => $expiresAt->format('d/m/Y H:i'),
                'kelas' => $siswa->getCurrentKelas()->nama_kelas ?? '-',
                'qr_code' => $qrCodeBase64,
                'magic_link' => $magicLink
            ];

            // Generate PDF
            $pdf = Pdf::loadView('exports.magic-link-card', $cardData)
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'dpi' => 150,
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true
                ]);

            $fileName = 'kartu-magic-link-' . str_replace(' ', '-', strtolower($siswa->nama_siswa)) . '.pdf';
            
            \Log::info('PDF generated successfully for: ' . $siswa->nama_siswa);
            
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $fileName, [
                'Content-Type' => 'application/pdf',
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in generateMagicLinkCardPDF: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat PDF kartu magic link: ' . $e->getMessage()], 500);
        }
    }
}