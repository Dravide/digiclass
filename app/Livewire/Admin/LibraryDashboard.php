<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\LibraryBook;
use App\Models\LibraryBorrowing;
use App\Models\LibraryAttendance;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LibraryDashboard extends Component
{
    public function render()
    {
        // Book Statistics
        $bookStats = [
            'total_books' => LibraryBook::count(),
            'active_books' => LibraryBook::where('is_active', true)->count(),
            'available_books' => LibraryBook::where('is_active', true)->sum('jumlah_tersedia'),
            'borrowed_books' => LibraryBook::where('is_active', true)->sum(DB::raw('jumlah_total - jumlah_tersedia')),
        ];

        // Borrowing Statistics
        $borrowingStats = [
            'active_borrowings' => LibraryBorrowing::where('status', 'dipinjam')->count(),
            'overdue_borrowings' => LibraryBorrowing::where('status', 'dipinjam')
                ->where('tanggal_kembali_rencana', '<', now())
                ->count(),
            'returned_today' => LibraryBorrowing::where('status', 'dikembalikan')
                ->whereDate('tanggal_kembali_aktual', now())
                ->count(),
            'borrowed_today' => LibraryBorrowing::whereDate('tanggal_pinjam', now())->count(),
        ];

        // Attendance Statistics
        $attendanceStats = [
            'today_visitors' => LibraryAttendance::whereDate('tanggal', now())->count(),
            'currently_present' => LibraryAttendance::whereDate('tanggal', now())
                ->where('status', 'hadir')
                ->count(),
            'monthly_visitors' => LibraryAttendance::whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->count(),
            'average_daily_visitors' => LibraryAttendance::whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->count() / now()->day,
        ];

        // Recent Activities
        $recentBorrowings = LibraryBorrowing::with(['siswa', 'libraryBook'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentAttendances = LibraryAttendance::with('siswa')
            ->whereDate('tanggal', now())
            ->orderBy('jam_masuk', 'desc')
            ->limit(5)
            ->get();

        // Popular Books (most borrowed)
        $popularBooks = LibraryBook::withCount(['borrowings' => function ($query) {
                $query->whereMonth('tanggal_pinjam', now()->month)
                      ->whereYear('tanggal_pinjam', now()->year);
            }])
            ->orderBy('borrowings_count', 'desc')
            ->limit(5)
            ->get();

        // Monthly Statistics for Chart
        $monthlyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $date->format('M Y'),
                'borrowings' => LibraryBorrowing::whereMonth('tanggal_pinjam', $date->month)
                    ->whereYear('tanggal_pinjam', $date->year)
                    ->count(),
                'visitors' => LibraryAttendance::whereMonth('tanggal', $date->month)
                    ->whereYear('tanggal', $date->year)
                    ->count(),
            ];
        }

        // Overdue Books Details
        $overdueBooks = LibraryBorrowing::with(['siswa', 'libraryBook'])
            ->where('status', 'dipinjam')
            ->where('tanggal_kembali_rencana', '<', now())
            ->orderBy('tanggal_kembali_rencana')
            ->limit(10)
            ->get();

        // Book Categories Distribution
        $categoryStats = LibraryBook::select('kategori', DB::raw('count(*) as total'))
            ->where('is_active', true)
            ->groupBy('kategori')
            ->orderBy('total', 'desc')
            ->get();

        return view('livewire.admin.library-dashboard', compact(
            'bookStats',
            'borrowingStats', 
            'attendanceStats',
            'recentBorrowings',
            'recentAttendances',
            'popularBooks',
            'monthlyStats',
            'overdueBooks',
            'categoryStats'
        ))->layout('layouts.app');
    }
}
