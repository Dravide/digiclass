<?php

namespace App\Livewire\Shared;

use Livewire\Component;

class MainPage extends Component
{
    public function render()
    {
        return view('livewire.main-page')
            ->layout('layouts.main', ['title' => 'Halaman Utama - DigiClass']);
    }
}