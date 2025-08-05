<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\PaktaIntegritas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaktaIntegritasManagement extends Component
{
    use WithFileUploads, WithPagination;

    // Form properties
    public $file;
    public $deskripsi = '';
    public $is_active = true;

    // Modal state
    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $editingId = null;
    public $deletingId = null;

    // Search and filter
    public $search = '';
    public $perPage = 10;

    protected $rules = [
        'file' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
        'deskripsi' => 'nullable|string|max:500',
        'is_active' => 'boolean'
    ];

    protected $messages = [
        'file.required' => 'File pakta integritas wajib dipilih.',
        'file.mimes' => 'File harus berformat PDF, DOC, atau DOCX.',
        'file.max' => 'Ukuran file maksimal 10MB.',
        'deskripsi.max' => 'Deskripsi maksimal 500 karakter.'
    ];

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->showModal = false;
    }

    public function resetForm()
    {
        $this->file = null;
        $this->deskripsi = '';
        $this->is_active = true;
        $this->editMode = false;
        $this->editingId = null;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        try {
            // Allow multiple active files - no need to deactivate others

            // Store file
            $fileName = $this->file->getClientOriginalName();
            $filePath = $this->file->store('pakta-integritas', 'public');
            $fileSize = $this->file->getSize();
            $fileType = $this->file->getClientOriginalExtension();

            // Create record
            PaktaIntegritas::create([
                'nama_file' => $fileName,
                'file_path' => $filePath,
                'file_type' => $fileType,
                'file_size' => $fileSize,
                'deskripsi' => $this->deskripsi,
                'is_active' => $this->is_active,
                'uploaded_by' => Auth::user()->name
            ]);

            $this->dispatch('pakta-integritas-saved');
            $this->closeModal();
            session()->flash('success', 'File pakta integritas berhasil diupload.');

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengupload file: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $paktaIntegritas = PaktaIntegritas::findOrFail($id);
        
        $this->editingId = $id;
        $this->deskripsi = $paktaIntegritas->deskripsi;
        $this->is_active = $paktaIntegritas->is_active;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function update()
    {
        $rules = [
            'deskripsi' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ];

        // Only validate file if a new one is uploaded
        if ($this->file) {
            $rules['file'] = 'file|mimes:pdf,doc,docx|max:10240';
        }

        $this->validate($rules);

        try {
            $paktaIntegritas = PaktaIntegritas::findOrFail($this->editingId);

            // Allow multiple active files - no need to deactivate others

            $updateData = [
                'deskripsi' => $this->deskripsi,
                'is_active' => $this->is_active
            ];

            // If new file is uploaded, replace the old one
            if ($this->file) {
                // Delete old file
                if ($paktaIntegritas->fileExists()) {
                    Storage::disk('public')->delete($paktaIntegritas->file_path);
                }

                // Store new file
                $fileName = $this->file->getClientOriginalName();
                $filePath = $this->file->store('pakta-integritas', 'public');
                $fileSize = $this->file->getSize();
                $fileType = $this->file->getClientOriginalExtension();

                $updateData = array_merge($updateData, [
                    'nama_file' => $fileName,
                    'file_path' => $filePath,
                    'file_type' => $fileType,
                    'file_size' => $fileSize
                ]);
            }

            $paktaIntegritas->update($updateData);

            $this->dispatch('pakta-integritas-updated');
            $this->closeModal();
            session()->flash('success', 'File pakta integritas berhasil diperbarui.');

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui file: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $paktaIntegritas = PaktaIntegritas::findOrFail($this->deletingId);
            $paktaIntegritas->delete();

            $this->showDeleteModal = false;
            $this->deletingId = null;
            session()->flash('success', 'File pakta integritas berhasil dihapus.');

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus file: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $paktaIntegritas = PaktaIntegritas::findOrFail($id);
            
            if (!$paktaIntegritas->is_active) {
                // Activate this file (allow multiple active files)
                $paktaIntegritas->update(['is_active' => true]);
                session()->flash('success', 'File pakta integritas berhasil diaktifkan.');
            } else {
                $paktaIntegritas->update(['is_active' => false]);
                session()->flash('success', 'File pakta integritas berhasil dinonaktifkan.');
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengubah status file: ' . $e->getMessage());
        }
    }

    public function downloadFile($id)
    {
        try {
            $paktaIntegritas = PaktaIntegritas::findOrFail($id);
            
            if (!$paktaIntegritas->fileExists()) {
                session()->flash('error', 'File tidak ditemukan.');
                return;
            }

            return Storage::disk('public')->download($paktaIntegritas->file_path, $paktaIntegritas->nama_file);

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengunduh file: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $paktaIntegritas = PaktaIntegritas::query()
            ->when($this->search, function ($query) {
                $query->where('nama_file', 'like', '%' . $this->search . '%')
                      ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                      ->orWhere('uploaded_by', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.pakta-integritas-management', [
            'paktaIntegritas' => $paktaIntegritas
        ])->layout('layouts.app');
    }
}