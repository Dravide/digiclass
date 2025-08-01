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
        $user = Auth::user();
        $activities = [];
        
        // Add login activity
        $activities[] = [
            'activity' => 'Login ke sistem',
            'time' => now()->format('H:i'),
            'icon' => 'ri-login-circle-line',
            'type' => 'success'
        ];
        
        // Role-specific activities
        switch ($this->userRole) {
            case 'admin':
                $activities[] = [
                    'activity' => 'Mengakses panel admin',
                    'time' => now()->subMinutes(2)->format('H:i'),
                    'icon' => 'ri-admin-line',
                    'type' => 'info'
                ];
                break;
                
            case 'guru':
                $activities[] = [
                    'activity' => 'Memeriksa kelas aktif',
                    'time' => now()->subMinutes(3)->format('H:i'),
                    'icon' => 'ri-school-line',
                    'type' => 'primary'
                ];
                break;
                
            case 'siswa':
                $activities[] = [
                    'activity' => 'Melihat tugas terbaru',
                    'time' => now()->subMinutes(5)->format('H:i'),
                    'icon' => 'ri-task-line',
                    'type' => 'warning'
                ];
                break;
        }
        
        return $activities;
    }
    
    public function getNotifications()
    {
        $user = Auth::user();
        $notifications = [];
        
        // Role-specific notifications
        switch ($this->userRole) {
            case 'admin':
                $notifications[] = [
                    'title' => 'Data Siswa Baru',
                    'message' => '5 siswa baru perlu verifikasi',
                    'type' => 'info',
                    'icon' => 'ri-user-add-line'
                ];
                break;
                
            case 'guru':
                $notifications[] = [
                    'title' => 'Tugas Belum Dinilai',
                    'message' => '12 tugas menunggu penilaian',
                    'type' => 'warning',
                    'icon' => 'ri-file-list-line'
                ];
                break;
                
            case 'siswa':
                $notifications[] = [
                    'title' => 'Tugas Baru',
                    'message' => '3 tugas baru tersedia',
                    'type' => 'success',
                    'icon' => 'ri-notification-line'
                ];
                break;
        }
        
        return $notifications;
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
            'totalPerpustakaan' => $siswaAktifPerpustakaan,
            'activeTahunPelajaran' => $activeTahunPelajaran,
            'recentActivities' => $this->getRecentActivities()
        ])->layout('layouts.app');
    }
}