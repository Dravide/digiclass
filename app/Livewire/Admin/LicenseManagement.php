<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\LicenseService;
use Illuminate\View\View;

class LicenseManagement extends Component
{
    public string $license_key = '';
    public string $app_name = '';
    public string $notes = '';
    public bool $showModal = false;
    public string $message = '';
    public string $messageType = '';
    
    protected LicenseService $licenseService;
    
    protected array $rules = [
        'license_key' => 'required|string',
        'app_name' => 'required|string|max:255',
        'notes' => 'nullable|string|max:500'
    ];
    
    protected array $messages = [
        'license_key.required' => 'License key harus diisi.',
        'app_name.required' => 'Nama aplikasi harus diisi.',
        'app_name.max' => 'Nama aplikasi maksimal 255 karakter.',
        'notes.max' => 'Catatan maksimal 500 karakter.'
    ];
    
    public function boot(LicenseService $licenseService): void
    {
        $this->licenseService = $licenseService;
    }
    
    public function mount(): void
    {
        $licenseInfo = $this->licenseService->getLicenseInfo();
        
        if ($licenseInfo) {
            $license = $licenseInfo['license'];
            $this->license_key = $license->license_key;
            $this->app_name = $license->app_name;
            $this->notes = $license->notes ?? '';
        }
    }
    
    public function bukaModal(): void
    {
        $this->showModal = true;
        $this->reset(['license_key', 'app_name', 'notes', 'message']);
    }
    
    public function bukaModalDenganData(): void
    {
        $this->showModal = true;
        // Tidak reset data karena sudah diisi dari generate
    }
    
    public function tutupModal(): void
    {
        $this->showModal = false;
        $this->reset(['license_key', 'app_name', 'notes', 'message']);
    }
    
    public function simpanLisensi(): void
    {
        $this->validate();

        try {
            $result = $this->licenseService->saveLicense($this->license_key, [
                'app_name' => $this->app_name,
                'notes' => $this->notes
            ]);
            
            if ($result['valid']) {
                $this->message = $result['message'];
                $this->messageType = 'success';
                $this->tutupModal();
                $this->mount();
            } else {
                $this->message = $result['message'];
                $this->messageType = 'error';
            }

        } catch (\Exception $e) {
            $this->message = 'Error: ' . $e->getMessage();
            $this->messageType = 'error';
        }
    }

    public function validateLicense(): void
    {
        try {
            $licenseInfo = $this->licenseService->getLicenseInfo();
            
            if ($licenseInfo && $licenseInfo['validation']['valid']) {
                $this->message = 'Lisensi valid!';
                $this->messageType = 'success';
            } else {
                $this->message = 'Lisensi tidak valid atau tidak ditemukan!';
                $this->messageType = 'error';
            }
            
            $this->mount();
        } catch (\Exception $e) {
            $this->message = 'Error validasi: ' . $e->getMessage();
            $this->messageType = 'error';
        }
    }

    public function generateSampleLicense(): void
    {
        try {
            $sampleKey = $this->licenseService->generateSampleLicense();
            
            // Set data untuk modal
            $this->license_key = $sampleKey;
            $this->app_name = 'DigiClass';
            $this->notes = 'Sample license untuk testing';
            
            // Set pesan sukses
            $this->message = 'Sample license key berhasil digenerate!';
            $this->messageType = 'success';
            
            // Buka modal dengan data yang sudah diisi
            $this->bukaModalDenganData();
            
        } catch (\Exception $e) {
            $this->message = 'Error generate sample: ' . $e->getMessage();
            $this->messageType = 'error';
        }
    }

    public function deactivateLicense(): void
    {
        try {
            $this->licenseService->deactivateLicense();
            
            $this->message = 'Lisensi berhasil dinonaktifkan!';
            $this->messageType = 'success';
            $this->mount();
        } catch (\Exception $e) {
            $this->message = 'Error deaktivasi: ' . $e->getMessage();
            $this->messageType = 'error';
        }
    }
    
    public function render()
    {
        $currentDomain = $this->licenseService->getCurrentDomain();
        $licenseInfo = $this->licenseService->getLicenseInfo();
        
        return view('livewire.admin.license-management', [
            'currentDomain' => $currentDomain,
            'licenseInfo' => $licenseInfo
        ])->layout('layouts.app');
    }
}
