<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use Carbon\Carbon;

class WeatherWidget extends Component
{
    public $weather;
    public $location;
    
    public function mount()
    {
        $this->loadWeatherData();
    }
    
    public function loadWeatherData()
    {
        // Sample weather data - in real implementation, this would come from weather API
        $this->location = 'Jakarta, Indonesia';
        $this->weather = [
            'temperature' => 28,
            'condition' => 'Cerah Berawan',
            'humidity' => 75,
            'wind_speed' => 12,
            'icon' => 'ri-sun-cloudy-line',
            'forecast' => [
                [
                    'day' => 'Besok',
                    'temp_high' => 30,
                    'temp_low' => 24,
                    'condition' => 'Cerah',
                    'icon' => 'ri-sun-line'
                ],
                [
                    'day' => 'Lusa',
                    'temp_high' => 29,
                    'temp_low' => 23,
                    'condition' => 'Hujan Ringan',
                    'icon' => 'ri-drizzle-line'
                ],
                [
                    'day' => 'Minggu',
                    'temp_high' => 27,
                    'temp_low' => 22,
                    'condition' => 'Berawan',
                    'icon' => 'ri-cloudy-line'
                ]
            ]
        ];
    }
    
    public function refreshWeather()
    {
        $this->loadWeatherData();
        $this->dispatch('weather-updated');
    }
    
    public function getWeatherColor()
    {
        $temp = $this->weather['temperature'];
        
        if ($temp >= 30) {
            return 'danger'; // Hot
        } elseif ($temp >= 25) {
            return 'warning'; // Warm
        } elseif ($temp >= 20) {
            return 'success'; // Comfortable
        } else {
            return 'info'; // Cool
        }
    }
    
    public function render()
    {
        return view('livewire.shared.weather-widget', [
            'weatherColor' => $this->getWeatherColor(),
            'currentTime' => now()->format('H:i'),
            'currentDate' => now()->format('d M Y')
        ]);
    }
}