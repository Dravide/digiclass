@extends('layouts.app')

@section('title', 'Manajemen Pelanggaran Siswa')

@section('content')
<div class="min-h-screen bg-gray-50">
    @livewire('pelanggaran-management')
</div>
@endsection

@push('styles')
<style>
    /* Custom styles untuk pelanggaran management */
    .table-responsive {
        overflow-x: auto;
    }
    
    .badge-status {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .badge-belum-ditangani {
        background-color: #fef2f2;
        color: #dc2626;
    }
    
    .badge-dalam-proses {
        background-color: #fefbf2;
        color: #d97706;
    }
    
    .badge-selesai {
        background-color: #f0fdf4;
        color: #16a34a;
    }
    
    .poin-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .poin-rendah {
        background-color: #dcfce7;
        color: #166534;
    }
    
    .poin-sedang {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .poin-tinggi {
        background-color: #fecaca;
        color: #991b1b;
    }
    
    .modal-overlay {
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .hover-scale {
        transition: transform 0.2s ease-in-out;
    }
    
    .hover-scale:hover {
        transform: scale(1.05);
    }
    
    /* Loading spinner */
    .loading-spinner {
        border: 2px solid #f3f4f6;
        border-top: 2px solid #3b82f6;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Custom scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Form focus styles */
    .form-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }
    
    /* Button hover effects */
    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }
    
    .btn-secondary {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }
    
    /* Card hover effects */
    .card-hover {
        transition: all 0.3s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    /* Status indicators */
    .status-indicator {
        position: relative;
    }
    
    .status-indicator::before {
        content: '';
        position: absolute;
        left: -8px;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background-color: currentColor;
    }
    
    /* Responsive table */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .table-responsive th,
        .table-responsive td {
            padding: 0.5rem 0.25rem;
        }
        
        .modal-content {
            margin: 1rem;
            width: calc(100% - 2rem);
        }
    }
    
    /* Print styles */
    @media print {
        .no-print {
            display: none !important;
        }
        
        .print-only {
            display: block !important;
        }
        
        body {
            background: white !important;
        }
        
        .table {
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            border: 1px solid #000;
            padding: 8px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Custom JavaScript untuk pelanggaran management
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide flash messages
        const flashMessages = document.querySelectorAll('[role="alert"]');
        flashMessages.forEach(function(message) {
            setTimeout(function() {
                message.style.transition = 'opacity 0.5s ease-out';
                message.style.opacity = '0';
                setTimeout(function() {
                    message.remove();
                }, 500);
            }, 5000);
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + N untuk tambah pelanggaran baru
            if (e.ctrlKey && e.key === 'n') {
                e.preventDefault();
                const addButton = document.querySelector('[wire\\:click="openModal"]');
                if (addButton) {
                    addButton.click();
                }
            }
            
            // Escape untuk tutup modal
            if (e.key === 'Escape') {
                const closeButtons = document.querySelectorAll('[wire\\:click="closeModal"]');
                closeButtons.forEach(button => {
                    if (button.offsetParent !== null) { // Check if visible
                        button.click();
                    }
                });
            }
        });
        
        // Tooltip initialization (jika menggunakan library tooltip)
        const tooltipElements = document.querySelectorAll('[data-tooltip]');
        tooltipElements.forEach(function(element) {
            element.addEventListener('mouseenter', function() {
                // Implementasi tooltip custom atau library
                console.log('Tooltip:', this.getAttribute('data-tooltip'));
            });
        });
        
        // Smooth scroll untuk anchor links
        const anchorLinks = document.querySelectorAll('a[href^="#"]');
        anchorLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Auto-refresh data setiap 5 menit (opsional)
        // setInterval(function() {
        //     if (typeof Livewire !== 'undefined') {
        //         Livewire.emit('refreshData');
        //     }
        // }, 300000); // 5 menit
    });
    
    // Fungsi untuk export data
    function exportData(format) {
        const currentUrl = new URL(window.location.href);
        const params = new URLSearchParams(currentUrl.search);
        params.set('export', format);
        
        window.open(currentUrl.pathname + '?' + params.toString(), '_blank');
    }
    
    // Fungsi untuk print halaman
    function printPage() {
        window.print();
    }
    
    // Fungsi untuk konfirmasi delete
    function confirmDelete(message) {
        return confirm(message || 'Apakah Anda yakin ingin menghapus data ini?');
    }
    
    // Fungsi untuk format angka
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }
    
    // Fungsi untuk format tanggal
    function formatDate(dateString) {
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }).format(date);
    }
    
    // Event listener untuk Livewire events
    document.addEventListener('livewire:load', function () {
        // Event ketika data berhasil disimpan
        Livewire.on('pelanggaranSaved', function() {
            // Bisa ditambahkan notifikasi atau aksi lainnya
            console.log('Data pelanggaran berhasil disimpan');
        });
        
        // Event ketika data berhasil dihapus
        Livewire.on('pelanggaranDeleted', function() {
            console.log('Data pelanggaran berhasil dihapus');
        });
        
        // Event untuk refresh data
        Livewire.on('refreshData', function() {
            console.log('Data sedang di-refresh');
        });
    });
</script>
@endpush