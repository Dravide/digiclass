<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\TahunPelajaran;

class AcademicCalendar extends Component
{
    public $currentMonth;
    public $currentYear;
    public $events = [];
    public $showModal = false;
    public $selectedDate;
    public $selectedEvents = [];
    
    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->loadEvents();
    }
    
    public function previousMonth()
    {
        if ($this->currentMonth == 1) {
            $this->currentMonth = 12;
            $this->currentYear--;
        } else {
            $this->currentMonth--;
        }
        $this->loadEvents();
    }
    
    public function nextMonth()
    {
        if ($this->currentMonth == 12) {
            $this->currentMonth = 1;
            $this->currentYear++;
        } else {
            $this->currentMonth++;
        }
        $this->loadEvents();
    }
    
    public function loadEvents()
    {
        $this->events = [];
        
        // Get active academic year
        $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
        
        if (!$activeTahunPelajaran) {
            return;
        }
        
        // Get all schedules for the current month
        $jadwalList = Jadwal::with(['guru', 'mataPelajaran', 'kelas'])
            ->where('tahun_pelajaran_id', $activeTahunPelajaran->id)
            ->where('is_active', true)
            ->get();
        
        // Map Indonesian day names to English for Carbon
        $dayMapping = [
            'senin' => 'monday',
            'selasa' => 'tuesday', 
            'rabu' => 'wednesday',
            'kamis' => 'thursday',
            'jumat' => 'friday',
            'sabtu' => 'saturday'
        ];
        
        // Generate dates for the current month
        $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        
        // Loop through each day of the month
        for ($date = $startOfMonth->copy(); $date <= $endOfMonth; $date->addDay()) {
            $dayName = strtolower($date->format('l')); // Get English day name
            
            // Find Indonesian day name
            $indonesianDay = array_search($dayName, $dayMapping);
            
            if ($indonesianDay) {
                // Get schedules for this day
                $daySchedules = $jadwalList->where('hari', $indonesianDay);
                
                $dateString = $date->format('Y-m-d');
                $this->events[$dateString] = [];
                
                // Group schedules by class
                $schedulesByClass = $daySchedules->groupBy('kelas_id');
                
                foreach ($schedulesByClass as $kelasId => $kelasSchedules) {
                    $firstSchedule = $kelasSchedules->first();
                    $scheduleCount = $kelasSchedules->count();
                    
                    $this->events[$dateString][] = [
                        'title' => 'Kelas ' . $firstSchedule->kelas->nama_kelas,
                        'subtitle' => $scheduleCount . ' mata pelajaran',
                        'type' => 'teaching',
                        'color' => 'primary',
                        'kelas_id' => $kelasId,
                        'schedules' => $kelasSchedules->map(function($jadwal) {
                            return [
                                'guru' => $jadwal->guru->nama_guru,
                                'mata_pelajaran' => $jadwal->mataPelajaran->nama_mapel,
                                'jam' => $jadwal->jam_format,
                                'jam_ke' => $jadwal->jam_ke
                            ];
                        })->toArray()
                    ];
                }
            }
        }
    }
    
    public function getCalendarDays()
    {
        $firstDay = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $lastDay = $firstDay->copy()->endOfMonth();
        $startDate = $firstDay->copy()->startOfWeek(Carbon::SUNDAY);
        $endDate = $lastDay->copy()->endOfWeek(Carbon::SATURDAY);
        
        $days = [];
        $current = $startDate->copy();
        
        while ($current <= $endDate) {
            $dateString = $current->format('Y-m-d');
            $days[] = [
                'date' => $current->copy(),
                'day' => $current->day,
                'isCurrentMonth' => $current->month == $this->currentMonth,
                'isToday' => $current->isToday(),
                'events' => $this->events[$dateString] ?? []
            ];
            $current->addDay();
        }
        
        return $days;
    }
    
    public function getMonthName()
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return $months[$this->currentMonth];
    }
    
    public function showDayDetails($date)
    {
        $this->selectedDate = $date;
        $this->selectedEvents = $this->events[$date] ?? [];
        $this->showModal = true;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedDate = null;
        $this->selectedEvents = [];
    }
    
    public function render()
    {
        return view('livewire.shared.academic-calendar', [
            'calendarDays' => $this->getCalendarDays(),
            'monthName' => $this->getMonthName()
        ]);
    }
}