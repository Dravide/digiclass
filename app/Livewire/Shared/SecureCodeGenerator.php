<?php

namespace App\Livewire\Shared;

use App\Models\SecureCode;
use App\Models\User;
use App\Models\Guru;
use App\Models\TataUsaha;
use App\Exports\SecureCodesExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class SecureCodeGenerator extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showGenerateModal = false;
    public string $successMessage = '';
    public int $selectedUserId = 0;
    public array $availableUsers = [];
    public string $bulkGenerationType = ''; // 'guru', 'tata_usaha', or empty for single user
    public bool $userHasSecureCode = false;

    protected $listeners = ['refreshComponent' => '$refresh'];

    /**
     * Mount component - Load available users for admin
     */
    public function mount(): void
    {
        $user = Auth::user();
        
        // Cek apakah user saat ini sudah memiliki secure code
        $this->userHasSecureCode = SecureCode::userHasSecureCode($user->id);
        
        if ($user->hasRole('admin')) {
            // Ambil data guru dan tata usaha dari tabel masing-masing
            $gurus = Guru::with('user')->get()->map(function ($guru) {
                return [
                    'id' => $guru->user ? $guru->user->id : null,
                    'name' => $guru->nama_guru,
                    'email' => $guru->email,
                    'type' => 'guru',
                    'has_secure_code' => $guru->user ? SecureCode::userHasSecureCode($guru->user->id) : false
                ];
            })->filter(function ($item) {
                return $item['id'] !== null;
            });
            
            $tataUsahas = TataUsaha::with('user')->get()->map(function ($tataUsaha) {
                return [
                    'id' => $tataUsaha->user ? $tataUsaha->user->id : null,
                    'name' => $tataUsaha->nama_tata_usaha,
                    'email' => $tataUsaha->email,
                    'type' => 'tata_usaha',
                    'has_secure_code' => $tataUsaha->user ? SecureCode::userHasSecureCode($tataUsaha->user->id) : false
                ];
            })->filter(function ($item) {
                return $item['id'] !== null;
            });
            
            $this->availableUsers = $gurus->merge($tataUsahas)->toArray();
        }
    }

    /**
     * Generate secure code bulk untuk semua guru
     */
    public function generateBulkGuru(): void
    {
        $this->generateBulkSecureCode('guru');
    }

    /**
     * Generate secure code bulk untuk semua tata usaha
     */
    public function generateBulkTataUsaha(): void
    {
        $this->generateBulkSecureCode('tata_usaha');
    }

    /**
     * Generate secure code bulk untuk role tertentu
     */
    private function generateBulkSecureCode(string $role): void
    {
        try {
            $user = Auth::user();
            
            // Hanya admin yang bisa bulk generate
            if (!$user->hasRole('admin')) {
                session()->flash('error', 'Anda tidak memiliki akses untuk bulk generate secure code.');
                return;
            }

            // Ambil semua user berdasarkan role dari tabel yang tepat
            if ($role === 'guru') {
                $users = Guru::with('user')->get()->map(function ($guru) {
                    return $guru->user;
                })->filter();
            } else {
                $users = TataUsaha::with('user')->get()->map(function ($tataUsaha) {
                    return $tataUsaha->user;
                })->filter();
            }

            if ($users->isEmpty()) {
                session()->flash('error', 'Tidak ada user dengan role ' . ucfirst(str_replace('_', ' ', $role)) . ' yang ditemukan.');
                return;
            }

            $generatedCount = 0;
            $errors = [];

            foreach ($users as $targetUser) {
                try {
                    SecureCode::createForUser($targetUser->id);
                    $generatedCount++;
                } catch (\Exception $e) {
                    $errors[] = "Error untuk {$targetUser->name}: {$e->getMessage()}";
                    \Log::error('Error generating bulk secure code', [
                        'target_user_id' => $targetUser->id,
                        'role' => $role,
                        'exception' => $e
                    ]);
                }
            }

            $roleLabel = ucfirst(str_replace('_', ' ', $role));
            
            if ($generatedCount > 0) {
                $this->successMessage = "Berhasil generate {$generatedCount} secure code untuk {$roleLabel}.";
                session()->flash('message', $this->successMessage);
            }

            if (!empty($errors)) {
                $errorMessage = "Beberapa error terjadi: " . implode(', ', $errors);
                session()->flash('error', $errorMessage);
            }
            
            $this->dispatch('refreshComponent');
            
        } catch (\Exception $e) {
            \Log::error('Error in bulk secure code generation: ' . $e->getMessage(), [
                'user_id' => Auth::user()->id,
                'role' => $role,
                'exception' => $e
            ]);
            
            session()->flash('error', 'Terjadi kesalahan saat bulk generate secure code.');
        }
    }

    /**
     * Generate secure code untuk user yang dipilih (admin) atau user yang sedang login (guru/tata usaha)
     */
    public function generateSecureCode(): void
    {
        try {
            $user = Auth::user();
            
            // Validasi role user
            if (!$user->hasAnyRole(['guru', 'tata_usaha', 'admin'])) {
                session()->flash('error', 'Anda tidak memiliki akses untuk generate secure code.');
                return;
            }

            $targetUserId = $user->id;
            
            // Jika admin, harus memilih user target
            if ($user->hasRole('admin')) {
                if (empty($this->selectedUserId)) {
                    session()->flash('error', 'Silakan pilih user untuk dibuatkan secure code.');
                    return;
                }
                
                // Validasi user yang dipilih adalah guru atau tata usaha
                $targetUser = User::find($this->selectedUserId);
                
                if (!$targetUser) {
                    session()->flash('error', 'User yang dipilih tidak valid.');
                    return;
                }
                
                // Cek apakah user adalah guru atau tata usaha
                $isGuru = Guru::where('email', $targetUser->email)->exists();
                $isTataUsaha = TataUsaha::where('email', $targetUser->email)->exists();
                
                if (!$isGuru && !$isTataUsaha) {
                    session()->flash('error', 'User yang dipilih bukan guru atau tata usaha.');
                    return;
                }
                
                $targetUserId = $this->selectedUserId;
            }

            $secureCode = SecureCode::createForUser($targetUserId);
            
            $targetUserName = $user->hasRole('admin') ? User::find($targetUserId)->name : 'Anda';
            $this->successMessage = "Secure code berhasil dibuat untuk {$targetUserName}: {$secureCode->secure_code}";
            session()->flash('message', $this->successMessage);
            
            // Update status userHasSecureCode jika bukan admin
            if (!$user->hasRole('admin')) {
                $this->userHasSecureCode = true;
            }
            
            // Reset selected user untuk admin
            if ($user->hasRole('admin')) {
                $this->selectedUserId = 0;
                // Refresh available users untuk update status has_secure_code
                $this->refreshAvailableUsers();
            }
            
            $this->dispatch('refreshComponent');
            
        } catch (\Exception $e) {
            \Log::error('Error generating secure code: ' . $e->getMessage(), [
                'user_id' => Auth::user()->id,
                'target_user_id' => $targetUserId ?? null,
                'exception' => $e
            ]);
            
            // Jika error karena user sudah memiliki secure code, tampilkan pesan yang sesuai
            if (str_contains($e->getMessage(), 'sudah memiliki secure code')) {
                session()->flash('error', $e->getMessage());
            } else {
                session()->flash('error', 'Terjadi kesalahan saat membuat secure code.');
            }
        }
    }

    /**
     * Hapus secure code
     */
    public function hapusSecureCode(int $id): void
    {
        try {
            $user = Auth::user();
            $secureCode = SecureCode::where('id', $id)
                                   ->where('user_id', $user->id)
                                   ->firstOrFail();
            
            $secureCode->delete();
            
            // Update status userHasSecureCode jika bukan admin
            if (!$user->hasRole('admin')) {
                $this->userHasSecureCode = false;
            }
            
            session()->flash('message', 'Secure code berhasil dihapus.');
            
        } catch (\Exception $e) {
            \Log::error('Error deleting secure code: ' . $e->getMessage(), [
                'secure_code_id' => $id,
                'user_id' => Auth::user()->id,
                'exception' => $e
            ]);
            
            session()->flash('error', 'Terjadi kesalahan saat menghapus secure code.');
        }
    }

    /**
     * Refresh available users untuk admin (update status has_secure_code)
     */
    private function refreshAvailableUsers(): void
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            // Load guru users dengan status secure code
            $guruUsers = Guru::with('user')->get()->map(function ($guru) {
                return [
                    'id' => $guru->user->id,
                    'name' => $guru->user->name,
                    'email' => $guru->email,
                    'role' => 'guru',
                    'has_secure_code' => SecureCode::userHasSecureCode($guru->user->id)
                ];
            });
            
            // Load tata usaha users dengan status secure code
            $tataUsahaUsers = TataUsaha::with('user')->get()->map(function ($tataUsaha) {
                return [
                    'id' => $tataUsaha->user->id,
                    'name' => $tataUsaha->user->name,
                    'email' => $tataUsaha->email,
                    'role' => 'tata_usaha',
                    'has_secure_code' => SecureCode::userHasSecureCode($tataUsaha->user->id)
                ];
            });
            
            $this->availableUsers = $guruUsers->concat($tataUsahaUsers)->toArray();
        }
    }

    /**
     * Reset search
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Export secure codes to Excel
     */
    public function exportExcel()
    {
        try {
            $fileName = 'secure-codes-' . date('Y-m-d-H-i-s') . '.xlsx';
            
            return Excel::download(new SecureCodesExport($this->search), $fileName);
            
        } catch (\Exception $e) {
            \Log::error('Error exporting secure codes to Excel: ' . $e->getMessage(), [
                'user_id' => Auth::user()->id,
                'search' => $this->search,
                'exception' => $e
            ]);
            
            session()->flash('error', 'Terjadi kesalahan saat mengexport data ke Excel.');
        }
    }

    /**
     * Render component
     */
    public function render(): View
    {
        $user = Auth::user();
        
        $secureCodes = SecureCode::with('user')
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhere('secure_code', 'like', '%' . $this->search . '%');
            })
            // Admin bisa melihat semua secure code untuk guru dan tata usaha
            // Guru dan Tata Usaha hanya bisa melihat secure code milik mereka sendiri
            ->when($user->hasRole('admin'), function ($query) {
                // Ambil semua user ID yang merupakan guru atau tata usaha
                $guruUserIds = Guru::with('user')->get()->pluck('user.id')->filter();
                $tataUsahaUserIds = TataUsaha::with('user')->get()->pluck('user.id')->filter();
                $validUserIds = $guruUserIds->merge($tataUsahaUserIds);
                
                $query->whereIn('user_id', $validUserIds);
            }, function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.shared.secure-code-generator', [
            'secureCodes' => $secureCodes
        ])->layout('layouts.app');
    }
}