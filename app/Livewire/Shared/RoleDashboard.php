<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\TahunPelajaran;
use Spatie\Permission\Models\Role;

class RoleDashboard extends Component
{
    public $userRole;
    public $dashboardData = [];

    public function mount()
    {
        $user = Auth::user();
        $this->userRole = $user->roles->first()->name ?? 'guest';
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $user = Auth::user();
        
        switch ($this->userRole) {
            case 'admin':
                $this->dashboardData = [
                    'total_users' => User::count(),
                    'total_guru' => User::role('guru')->count(),
                    'total_siswa' => User::role('siswa')->count(),
                    'total_tata_usaha' => User::role('tata_usaha')->count(),
                    'recent_activities' => $this->getRecentActivities()
                ];
                break;
                
            case 'guru':
                $this->dashboardData = [
                    'my_classes' => 0, // TODO: Implement class count for teacher
                    'pending_assignments' => 0, // TODO: Implement pending assignments
                    'students_count' => 0, // TODO: Implement students count
                    'recent_activities' => $this->getRecentActivities()
                ];
                break;
                
            case 'siswa':
                $this->dashboardData = [
                    'my_grades' => 0, // TODO: Implement grade average
                    'pending_assignments' => 0, // TODO: Implement pending assignments
                    'attendance_percentage' => 0, // TODO: Implement attendance percentage
                    'recent_activities' => $this->getRecentActivities()
                ];
                break;
                
            case 'tata_usaha':
                $this->dashboardData = [
                    'total_students' => User::role('siswa')->count(),
                    'total_teachers' => User::role('guru')->count(),
                    'pending_documents' => 0, // TODO: Implement pending documents
                    'recent_activities' => $this->getRecentActivities()
                ];
                break;
        }
    }

    private function getRecentActivities()
    {
        // TODO: Implement recent activities based on role
        return [
            ['activity' => 'Login ke sistem', 'time' => now()->format('H:i')],
            ['activity' => 'Mengakses dashboard', 'time' => now()->subMinutes(5)->format('H:i')]
        ];
    }

    public function render()
    {
        // Get active academic year
        $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
        
        // Filter statistics by active academic year
        $totalSiswa = Siswa::when($activeTahunPelajaran, function ($query) use ($activeTahunPelajaran) {
            $query->where('tahun_pelajaran_id', $activeTahunPelajaran->id);
        })->count();
        
        $totalKelas = Kelas::when($activeTahunPelajaran, function ($query) use ($activeTahunPelajaran) {
            $query->where('tahun_pelajaran_id', $activeTahunPelajaran->id);
        })->count();
        
        $totalGuru = Guru::count();
        
        $siswaAktifPerpustakaan = Siswa::perpustakaanAktif()
            ->when($activeTahunPelajaran, function ($query) use ($activeTahunPelajaran) {
                $query->where('tahun_pelajaran_id', $activeTahunPelajaran->id);
            })->count();
        
        return view('livewire.shared.dashboard', [
            'totalSiswa' => $totalSiswa,
            'totalKelas' => $totalKelas,
            'totalGuru' => $totalGuru,
            'siswaAktifPerpustakaan' => $siswaAktifPerpustakaan,
            'activeTahunPelajaran' => $activeTahunPelajaran
        ])->layout('layouts.app');
    }
}