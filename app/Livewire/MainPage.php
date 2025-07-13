<?php

namespace App\Livewire;

use Livewire\Component;

class MainPage extends Component
{
    public function render()
    {
        return view('livewire.main-page')
            ->layout('layouts.main', ['title' => 'Halaman Utama - DigiClass']);
    }
}