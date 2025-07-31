<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use App\Models\Surat;
use Illuminate\Http\Request;

class SuratValidation extends Component
{
    public $surat;
    public $notFound = false;
    
    public function mount($id)
    {
        try {
            $this->surat = Surat::with('creator')->findOrFail($id);
            
            // Check if surat is signed
            if (!$this->surat->isSigned()) {
                $this->notFound = true;
            }
        } catch (\Exception $e) {
            $this->notFound = true;
        }
    }

    public function render()
    {
        return view('livewire.shared.surat-validation')->layout('layouts.app');
    }
}