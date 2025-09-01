<?php

namespace App\Livewire\Admin;

use App\Models\TataUsaha;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class TataUsahaManagement extends Component
{
    use WithPagination, WithFileUploads;

    // Form properties
    public $nama_tata_usaha;
    public $nip;
    public $email;
    public $telepon;
    public $jabatan;
    public $bidang_tugas;
    public $is_active = true;

    // State management
    public $isEditing = false;
    public $editingTataUsahaId;

    // Search and filter
    public $search = '';
    public $perPage = 10;
    public $sortField = 'nama_tata_usaha';
    public $sortDirection = 'asc';
    public $filterActive = '';

    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        return [
            'nama_tata_usaha' => 'required|string|max:100',
            'nip' => [
                'required',
                'string',
                'max:20',
                Rule::unique('tata_usaha', 'nip')->ignore($this->editingTataUsahaId)
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('tata_usaha', 'email')->ignore($this->editingTataUsahaId)
            ],
            'telepon' => 'required|string|max:15',
            'jabatan' => 'required|string|max:100',
            'bidang_tugas' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'nama_tata_usaha.required' => 'Nama tata usaha harus diisi.',
        'nama_tata_usaha.max' => 'Nama tata usaha maksimal 100 karakter.',
        'nip.required' => 'NIP harus diisi.',
        'nip.unique' => 'NIP sudah terdaftar.',
        'nip.max' => 'NIP maksimal 20 karakter.',
        'email.required' => 'Email harus diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah terdaftar.',
        'email.max' => 'Email maksimal 100 karakter.',
        'telepon.required' => 'Telepon harus diisi.',
        'telepon.max' => 'Telepon maksimal 15 karakter.',
        'jabatan.required' => 'Jabatan harus diisi.',
        'jabatan.max' => 'Jabatan maksimal 100 karakter.',
        'bidang_tugas.max' => 'Bidang tugas maksimal 255 karakter.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset([
            'nama_tata_usaha',
            'nip',
            'email',
            'telepon',
            'jabatan',
            'bidang_tugas',
            'is_active',
            'isEditing',
            'editingTataUsahaId'
        ]);
        $this->is_active = true;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        try {
            \DB::transaction(function () {
                // Create tata usaha record
                $tataUsaha = TataUsaha::create([
                    'nama_tata_usaha' => $this->nama_tata_usaha,
                    'nip' => $this->nip,
                    'email' => $this->email,
                    'telepon' => $this->telepon,
                    'jabatan' => $this->jabatan,
                    'bidang_tugas' => $this->bidang_tugas,
                    'is_active' => $this->is_active,
                ]);

                // Create user account automatically
                $user = User::create([
                    'name' => $this->nama_tata_usaha,
                    'email' => $this->email,
                    'password' => Hash::make('password123'), // Default password
                    'email_verified_at' => now(),
                ]);

                // Assign tata usaha role
                $tataUsahaRole = Role::where('name', 'tata_usaha')->first();
                if ($tataUsahaRole) {
                    $user->assignRole($tataUsahaRole);
                }
            });

            $this->resetForm();
            $this->dispatch('tata-usaha-created', 'Tata Usaha dan akun user berhasil ditambahkan! Password default: password123');
        } catch (\Exception $e) {
            $this->dispatch('tata-usaha-error', 'Gagal menambahkan tata usaha: ' . $e->getMessage());
        }
    }

    public function edit($tataUsahaId)
    {
        try {
            $tataUsaha = TataUsaha::findOrFail($tataUsahaId);
            
            $this->editingTataUsahaId = $tataUsaha->id;
            $this->nama_tata_usaha = $tataUsaha->nama_tata_usaha;
            $this->nip = $tataUsaha->nip;
            $this->email = $tataUsaha->email;
            $this->telepon = $tataUsaha->telepon;
            $this->jabatan = $tataUsaha->jabatan;
            $this->bidang_tugas = $tataUsaha->bidang_tugas;
            $this->is_active = $tataUsaha->is_active;
            
            $this->isEditing = true;
            
            $this->resetValidation();
        } catch (\Exception $e) {
            $this->dispatch('tata-usaha-error', 'Gagal memuat data tata usaha: ' . $e->getMessage());
        }
    }

    public function update()
    {
        $this->validate();

        try {
            $tataUsaha = TataUsaha::findOrFail($this->editingTataUsahaId);
            
            $tataUsaha->update([
                'nama_tata_usaha' => $this->nama_tata_usaha,
                'nip' => $this->nip,
                'email' => $this->email,
                'telepon' => $this->telepon,
                'jabatan' => $this->jabatan,
                'bidang_tugas' => $this->bidang_tugas,
                'is_active' => $this->is_active,
            ]);

            $this->resetForm();
            $this->dispatch('tata-usaha-updated', 'Tata Usaha berhasil diperbarui!');
        } catch (\Exception $e) {
            $this->dispatch('tata-usaha-error', 'Gagal memperbarui tata usaha: ' . $e->getMessage());
        }
    }

    public function delete($tataUsahaId)
    {
        try {
            $tataUsaha = TataUsaha::findOrFail($tataUsahaId);
            
            $tataUsaha->delete();
            
            $this->dispatch('tata-usaha-deleted', 'Tata Usaha berhasil dihapus!');
        } catch (\Exception $e) {
            $this->dispatch('tata-usaha-error', 'Gagal menghapus tata usaha: ' . $e->getMessage());
        }
    }

    public function generateAccount($tataUsahaId)
    {
        try {
            $tataUsaha = TataUsaha::findOrFail($tataUsahaId);
            
            // Check if tata usaha already has email
            if (empty($tataUsaha->email)) {
                $this->dispatch('tata-usaha-error', 'Tata Usaha harus memiliki email terlebih dahulu untuk generate akun!');
                return;
            }
            
            // Check if user account already exists
            $existingUser = User::where('email', $tataUsaha->email)->first();
            if ($existingUser) {
                $this->dispatch('tata-usaha-error', 'Akun dengan email ini sudah ada!');
                return;
            }
            
            DB::transaction(function () use ($tataUsaha) {
                // Create user account
                $user = User::create([
                    'name' => $tataUsaha->nama_tata_usaha,
                    'email' => $tataUsaha->email,
                    'password' => Hash::make('password123'), // Default password
                    'role' => 'tata_usaha'
                ]);
                
                // Assign tata usaha role
                $tataUsahaRole = Role::where('name', 'tata_usaha')->first();
                if ($tataUsahaRole) {
                    $user->assignRole($tataUsahaRole);
                }
            });
            
            $this->dispatch('tata-usaha-account-generated', 'Akun berhasil dibuat untuk tata usaha ' . $tataUsaha->nama_tata_usaha . '! Password default: password123');
        } catch (\Exception $e) {
            $this->dispatch('tata-usaha-error', 'Gagal membuat akun: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = TataUsaha::with(['user'])
            ->when($this->search, function ($query) {
                $query->where('nama_tata_usaha', 'like', '%' . $this->search . '%')
                      ->orWhere('nip', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('jabatan', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterActive !== '', function ($query) {
                $query->where('is_active', $this->filterActive);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $tataUsahas = $query->paginate($this->perPage);
        
        return view('livewire.admin.tata-usaha-management', [
            'tataUsahas' => $tataUsahas,
        ])->layout('layouts.app');
    }
}