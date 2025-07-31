<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Surat;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class SuratSignature extends Component
{
    public $suratId;
    public $surat;
    public $signatureData = '';
    public $showValidation = false;
    
    public function mount($suratId)
    {
        $this->suratId = $suratId;
        $this->surat = Surat::with('creator')->findOrFail($suratId);
        
        // If already signed, show validation view
        if ($this->surat->isSigned()) {
            $this->showValidation = true;
        }
    }

    public function render()
    {
        return view('livewire.admin.surat-signature')->layout('layouts.app');
    }

    public function saveSignature()
    {
        if (empty($this->signatureData)) {
            session()->flash('error', 'Silakan buat tanda tangan terlebih dahulu.');
            return;
        }

        try {
            // Generate QR Code with validation URL
            $validationUrl = route('surat.validate', ['id' => $this->surat->id]);
            $qrCodeContent = "Validasi Surat\nNomor: {$this->surat->nomor_surat}\nTanggal: {$this->surat->tanggal_surat->format('d/m/Y')}\nURL: {$validationUrl}";
            
            // Create QR code
            $qrCode = QrCode::format('png')
                ->size(200)
                ->margin(2)
                ->generate($qrCodeContent);
            
            // Save QR code to storage
            $qrCodePath = 'qr-codes/surat-' . $this->surat->id . '-' . time() . '.png';
            Storage::disk('public')->put($qrCodePath, $qrCode);
            
            // Update surat with signature and QR code
            $this->surat->update([
                'signature_data' => $this->signatureData,
                'qr_code_path' => $qrCodePath,
                'status' => 'signed',
                'signed_at' => Carbon::now()
            ]);
            
            $this->showValidation = true;
            session()->flash('success', 'Surat berhasil ditandatangani dan QR Code telah dibuat.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan tanda tangan: ' . $e->getMessage());
        }
    }

    public function validateSurat()
    {
        try {
            $this->surat->update([
                'status' => 'validated',
                'validated_at' => Carbon::now()
            ]);
            
            session()->flash('success', 'Surat telah divalidasi.');
            return redirect()->route('surat-management');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memvalidasi surat: ' . $e->getMessage());
        }
    }

    public function downloadPdf()
    {
        // This will be implemented later with PDF generation
        session()->flash('info', 'Fitur download PDF akan segera tersedia.');
    }

    public function backToManagement()
    {
        return redirect()->route('surat-management');
    }
}