<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class MenuHelper
{
    public static function getMenuItems()
    {
        $user = Auth::user();
        
        if (!$user) {
            return [];
        }

        $menuItems = [];

        // Main Menu Section
        $menuItems['Menu'] = [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'ri-dashboard-line',
                'permission' => 'view-dashboard'
            ]
        ];

        // Admin Menu Items
        if ($user->hasRole('admin')) {
            $menuItems['Manajemen Data'] = [
                [
                    'title' => 'Data Master',
                    'icon' => 'ri-database-line',
                    'permission' => 'manage-users',
                    'submenu' => [
                        ['title' => 'Tahun Pelajaran', 'route' => 'tahun-pelajaran-management', 'permission' => 'manage-tahun-pelajaran'],
                        ['title' => 'Mata Pelajaran', 'route' => 'mata-pelajaran-management', 'permission' => 'manage-mata-pelajaran'],
                        ['title' => 'Data Kelas', 'route' => 'kelas-management', 'permission' => 'manage-kelas'],
                        ['title' => 'Jadwal Guru', 'route' => 'jadwal-management', 'permission' => 'manage-jadwal']
                    ]
                ],
                [
                    'title' => 'Manajemen Users',
                    'icon' => 'ri-team-line',
                    'permission' => 'manage-users',
                    'submenu' => [
                        ['title' => 'Data Guru', 'route' => 'guru-management', 'permission' => 'manage-guru'],
                        ['title' => 'Data Tata Usaha', 'route' => 'tata-usaha-management', 'permission' => 'manage-tata-usaha'],
                        ['title' => 'Data Siswa', 'route' => 'class-management', 'permission' => 'manage-siswa'],
                        ['title' => 'Siswa Tidak Aktif', 'route' => 'inactive-siswa-management', 'permission' => 'manage-siswa'],
                        ['title' => 'User Management', 'route' => 'user-management', 'permission' => 'manage-users']
                    ]
                ],
                [
                    'title' => 'Sistem & Keamanan',
                    'icon' => 'ri-settings-3-line',
                    'permission' => 'manage-users',
                    'submenu' => [
                        ['title' => 'Menu Management', 'route' => 'menu-management', 'permission' => 'manage-menu'],
                        ['title' => 'Role & Permission', 'route' => 'role-permission-management', 'permission' => 'manage-roles'],
                        ['title' => 'Pakta Integritas', 'route' => 'pakta-integritas-management', 'permission' => 'manage-users'],
                        ['title' => 'Magic Link & QR', 'route' => 'magic-link-management', 'permission' => 'manage-siswa'],
                        ['title' => 'Data Perpustakaan', 'route' => 'perpustakaan-management', 'permission' => 'manage-perpustakaan']
                    ]
                ]
            ];
            

            
            $menuItems['Operasional'] = [
                [
                    'title' => 'Presensi & Kehadiran',
                    'icon' => 'ri-user-check-line',
                    'permission' => 'manage-presensi',
                    'submenu' => [
                        ['title' => 'Presensi Siswa', 'route' => 'presensi', 'permission' => 'manage-presensi'],
                        ['title' => 'Rekap Presensi Siswa', 'route' => 'rekap-presensi', 'permission' => 'view-presensi'],
                        ['title' => 'Rekap Presensi Guru', 'route' => 'rekap-presensi-guru', 'permission' => 'view-presensi-guru'],
                        ['title' => 'Rekap Presensi Tata Usaha', 'route' => 'rekap-presensi-tata-usaha', 'permission' => 'view-presensi-tata-usaha'],
                        ['title' => 'Pengaturan Jam Presensi', 'route' => 'pengaturan-jam-presensi', 'permission' => 'manage-users']
                    ]
                ],
                [
                    'title' => 'Akademik',
                    'icon' => 'ri-book-open-line',
                    'permission' => 'manage-tugas',
                    'submenu' => [
                        ['title' => 'Manajemen Tugas', 'route' => 'tugas-management', 'permission' => 'manage-tugas'],
                        ['title' => 'Manajemen Nilai', 'route' => 'nilai-management', 'permission' => 'manage-nilai'],
                        ['title' => 'Jurnal Mengajar', 'route' => 'jurnal-mengajar', 'permission' => 'manage-jurnal-mengajar'],
                        ['title' => 'Rekap Nilai Siswa', 'route' => 'rekap-nilai', 'permission' => 'view-reports']
                    ]
                ],
                [
                    'title' => 'Administrasi',
                    'icon' => 'ri-file-text-line',
                    'permission' => 'manage-surat',
                    'submenu' => [
                        ['title' => 'Surat Otomatis', 'route' => 'surat-management', 'permission' => 'manage-surat'],
                        ['title' => 'Import Data Excel', 'route' => 'import-management', 'permission' => 'import-data'],
                        ['title' => 'Statistik Sekolah', 'route' => 'statistik-management', 'permission' => 'view-statistics']
                    ]
                ],
                [
                    'title' => 'Laporan',
                    'icon' => 'ri-pie-chart-line',
                    'permission' => 'view-reports',
                    'submenu' => [
                        ['title' => 'Laporan Siswa'],
                        ['title' => 'Laporan Perpustakaan'],
                        ['title' => 'Laporan Kelas'],
                        ['title' => 'Laporan Presensi', 'route' => 'rekap-presensi']
                    ]
                ]
            ];
            
            $menuItems['Tools & QR'] = [
                [
                    'title' => 'Generator Secure Code',
                    'route' => 'secure-code-generator',
                    'icon' => 'ri-key-2-line',
                    'permission' => 'generate-secure-code'
                ],
                [
                    'title' => 'Presensi & Kehadiran',
                    'icon' => 'ri-user-check-line',
                    'permission' => 'scan-qr-presensi',
                    'submenu' => [
                        ['title' => 'Presensi QR Code', 'route' => 'presensi-qr', 'permission' => 'scan-qr-presensi']
                    ]
                ]
            ];
        }

        // Guru Menu Items
        if ($user->hasRole('guru')) {
            $menuItems['Akademik'] = [
                [
                    'title' => 'Pembelajaran',
                    'icon' => 'ri-book-2-line',
                    'permission' => 'manage-tugas',
                    'submenu' => [
                        ['title' => 'Kelas Saya', 'route' => 'my-classes', 'permission' => 'manage-own-classes'],
                        ['title' => 'Manajemen Tugas', 'route' => 'tugas-management', 'permission' => 'manage-tugas'],
                        ['title' => 'Manajemen Nilai', 'route' => 'nilai-management', 'permission' => 'manage-nilai'],
                        ['title' => 'Jurnal Mengajar', 'route' => 'jurnal-mengajar', 'permission' => 'manage-jurnal-mengajar']
                    ]
                ],
                [
                    'title' => 'Presensi & Kehadiran',
                    'icon' => 'ri-user-check-line',
                    'permission' => 'manage-presensi',
                    'submenu' => [
                        ['title' => 'Presensi Siswa', 'route' => 'presensi', 'permission' => 'manage-presensi'],
                        ['title' => 'Presensi QR Code', 'route' => 'presensi-qr', 'permission' => 'scan-qr-presensi']
                    ]
                ]
            ];
            
            $menuItems['Layanan Siswa'] = [
                [
                    'title' => 'Bimbingan Konseling',
                    'icon' => 'ri-chat-heart-line',
                    'permission' => 'manage-curhat',
                    'submenu' => [
                        ['title' => 'Curhat Siswa', 'route' => 'guru.curhat-siswa-management', 'permission' => 'manage-curhat']
                    ]
                ],
                [
                    'title' => 'Laporan',
                    'icon' => 'ri-file-list-3-line',
                    'permission' => 'view-presensi',
                    'submenu' => [
                        ['title' => 'Rekap Presensi', 'route' => 'rekap-presensi', 'permission' => 'view-presensi'],
                        ['title' => 'Rekap Nilai', 'route' => 'rekap-nilai', 'permission' => 'view-reports']
                    ]
                ]
            ];
            
        }

        // Siswa Menu Items
        if ($user->hasRole('siswa')) {
            $menuItems['Akademik'] = [
                [
                    'title' => 'Pembelajaran',
                    'icon' => 'ri-book-2-line',
                    'permission' => 'view-own-assignments',
                    'submenu' => [
                        ['title' => 'Tugas Saya', 'route' => 'my-assignments', 'permission' => 'view-own-assignments'],
                        ['title' => 'Nilai Saya', 'route' => 'my-grades', 'permission' => 'view-own-grades'],
                        ['title' => 'Jadwal Pelajaran', 'route' => 'my-schedule', 'permission' => 'view-own-schedule'],
                        ['title' => 'Rapor Online', 'route' => 'my-report-card', 'permission' => 'view-own-report']
                    ]
                ],
                [
                    'title' => 'Kehadiran',
                    'icon' => 'ri-calendar-check-line',
                    'permission' => 'view-own-attendance',
                    'submenu' => [
                        ['title' => 'Presensi Saya', 'route' => 'my-attendance', 'permission' => 'view-own-attendance']
                    ]
                ]
            ];
            
            $menuItems['Layanan Sekolah'] = [
                [
                    'title' => 'Fasilitas',
                    'icon' => 'ri-building-line',
                    'permission' => 'access-library',
                    'submenu' => [
                        ['title' => 'Perpustakaan', 'route' => 'library', 'permission' => 'access-library'],
                        ['title' => 'Ekstrakurikuler', 'route' => 'extracurricular', 'permission' => 'view-extracurricular']
                    ]
                ],
                [
                    'title' => 'Komunikasi',
                    'icon' => 'ri-chat-3-line',
                    'permission' => 'create-curhat',
                    'submenu' => [
                        ['title' => 'Curhat BK', 'route' => 'curhat-bk', 'permission' => 'create-curhat'],
                        ['title' => 'Pesan Guru', 'route' => 'teacher-messages', 'permission' => 'view-messages'],
                        ['title' => 'Forum Diskusi', 'route' => 'discussion-forum', 'permission' => 'access-forum'],
                        ['title' => 'Pengumuman', 'route' => 'announcements', 'permission' => 'view-announcements']
                    ]
                ]
            ];

        }

        // Tata Usaha Menu Items
        if ($user->hasRole('tata_usaha')) {
            
            $menuItems['Data Master'] = [
                [
                    'title' => 'Pengaturan Sekolah',
                    'icon' => 'ri-settings-3-line',
                    'permission' => 'manage-tahun-pelajaran',
                    'submenu' => [
                        ['title' => 'Tahun Pelajaran', 'route' => 'tahun-pelajaran-management', 'permission' => 'manage-tahun-pelajaran'],
                        ['title' => 'Mata Pelajaran', 'route' => 'mata-pelajaran-management', 'permission' => 'manage-mata-pelajaran']
                    ]
                ],
                [
                    'title' => 'Manajemen Users',
                    'icon' => 'ri-team-line',
                    'permission' => 'manage-guru',
                    'submenu' => [
                        ['title' => 'Data Guru', 'route' => 'guru-management', 'permission' => 'manage-guru'],
                        ['title' => 'Data Kelas', 'route' => 'kelas-management', 'permission' => 'manage-kelas'],
                        ['title' => 'Data Siswa', 'route' => 'class-management', 'permission' => 'manage-siswa'],
                        ['title' => 'Siswa Tidak Aktif', 'route' => 'inactive-siswa-management', 'permission' => 'manage-siswa'],
                        ['title' => 'Data Perpustakaan', 'route' => 'perpustakaan-management', 'permission' => 'manage-perpustakaan']
                    ]
                ]
            ];
            
            $menuItems['Operasional'] = [
                [
                    'title' => 'Administrasi',
                    'icon' => 'ri-file-text-line',
                    'permission' => 'manage-surat',
                    'submenu' => [
                        ['title' => 'Surat Otomatis', 'route' => 'surat-management', 'permission' => 'manage-surat'],
                        ['title' => 'Import Data Excel', 'route' => 'import-management', 'permission' => 'import-data'],
                        ['title' => 'Statistik Sekolah', 'route' => 'statistik-management', 'permission' => 'view-statistics']
                    ]
                ],
                [
                    'title' => 'Presensi QR Code',
                    'route' => 'presensi-qr',
                    'icon' => 'ri-qr-scan-2-line',
                    'permission' => 'scan-qr-presensi'
                ]
            ];
        }

        // BK (Bimbingan Konseling) Menu Items
        if ($user->hasRole('bk')) {
            $menuItems['Bimbingan Konseling'] = [
                [
                    'title' => 'Manajemen Pelanggaran',
                    'icon' => 'ri-alert-line',
                    'permission' => 'manage-pelanggaran',
                    'submenu' => [
                        ['title' => 'Kategori Pelanggaran', 'route' => 'kategori-pelanggaran-management', 'permission' => 'manage-pelanggaran'],
                        ['title' => 'Jenis Pelanggaran', 'route' => 'jenis-pelanggaran-management', 'permission' => 'manage-pelanggaran'],
                        ['title' => 'Sanksi Pelanggaran', 'route' => 'sanksi-pelanggaran-management', 'permission' => 'manage-pelanggaran'],
                        ['title' => 'Data Pelanggaran Siswa', 'route' => 'pelanggaran-management', 'permission' => 'manage-pelanggaran'],
                        ['title' => 'Notifikasi Sanksi Siswa', 'route' => 'notifikasi-sanksi-siswa', 'permission' => 'manage-pelanggaran']
                    ]
                ],
                [
                    'title' => 'Konseling Siswa',
                    'icon' => 'ri-chat-heart-line',
                    'permission' => 'manage-curhat',
                    'submenu' => [
                        ['title' => 'Curhat Siswa', 'route' => 'guru.curhat-siswa-management', 'permission' => 'manage-curhat']
                    ]
                ]
            ];
        }

        // Filter menu items based on user permissions
        $filteredMenuItems = [];
        foreach ($menuItems as $section => $items) {
            $filteredItems = array_filter($items, function ($item) use ($user) {
                return $user->can($item['permission']);
            });
            
            if (!empty($filteredItems)) {
                $filteredMenuItems[$section] = $filteredItems;
            }
        }
        
        return $filteredMenuItems;
    }

    public static function hasPermission($permission)
    {
        $user = Auth::user();
        return $user && $user->can($permission);
    }

    public static function hasRole($role)
    {
        $user = Auth::user();
        return $user && $user->hasRole($role);
    }

    public static function hasAnyRole($roles)
    {
        $user = Auth::user();
        return $user && $user->hasAnyRole($roles);
    }
}