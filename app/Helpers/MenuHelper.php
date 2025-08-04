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
            $menuItems['Pengaturan Dasar'] = [
                [
                    'title' => 'Tahun Pelajaran',
                    'route' => 'tahun-pelajaran-management',
                    'icon' => 'ri-calendar-line',
                    'permission' => 'manage-tahun-pelajaran'
                ],
                [
                    'title' => 'Mata Pelajaran',
                    'route' => 'mata-pelajaran-management',
                    'icon' => 'ri-book-line',
                    'permission' => 'manage-mata-pelajaran'
                ]
            ];
            
            $menuItems['Manajemen Data'] = [
                [
                    'title' => 'Manajemen Users',
                    'route' => 'user-management',
                    'icon' => 'ri-user-settings-line',
                    'permission' => 'manage-users'
                ],
                [
                    'title' => 'Manajemen Menu',
                    'route' => 'menu-management',
                    'icon' => 'ri-menu-line',
                    'permission' => 'manage-menu'
                ],
                [
                    'title' => 'Data Guru',
                    'route' => 'guru-management',
                    'icon' => 'ri-user-3-line',
                    'permission' => 'manage-guru'
                ],
                [
                    'title' => 'Data Kelas',
                    'route' => 'kelas-management',
                    'icon' => 'ri-building-line',
                    'permission' => 'manage-kelas'
                ],
                [
                    'title' => 'Data Siswa',
                    'route' => 'class-management',
                    'icon' => 'ri-group-line',
                    'permission' => 'manage-siswa'
                ],
                [
                    'title' => 'Siswa Tidak Aktif',
                    'route' => 'inactive-siswa-management',
                    'icon' => 'ri-user-unfollow-line',
                    'permission' => 'manage-siswa'
                ],
                [
                    'title' => 'Data Perpustakaan',
                    'route' => 'perpustakaan-management',
                    'icon' => 'ri-book-open-line',
                    'permission' => 'manage-perpustakaan'
                ],
                [
                    'title' => 'Jadwal Guru',
                    'route' => 'jadwal-management',
                    'icon' => 'ri-calendar-2-line',
                    'permission' => 'manage-jadwal'
                ]
            ];
            
            $menuItems['Manajemen Pelanggaran'] = [
                [
                    'title' => 'Kategori Pelanggaran',
                    'route' => 'kategori-pelanggaran-management',
                    'icon' => 'ri-folder-line',
                    'permission' => 'manage-pelanggaran'
                ],
                [
                    'title' => 'Jenis Pelanggaran',
                    'route' => 'jenis-pelanggaran-management',
                    'icon' => 'ri-list-check-line',
                    'permission' => 'manage-pelanggaran'
                ],
                [
                    'title' => 'Sanksi Pelanggaran',
                    'route' => 'sanksi-pelanggaran-management',
                    'icon' => 'ri-scales-line',
                    'permission' => 'manage-pelanggaran'
                ],
                [
                    'title' => 'Data Pelanggaran Siswa',
                    'route' => 'pelanggaran-management',
                    'icon' => 'ri-alert-line',
                    'permission' => 'manage-pelanggaran'
                ]
            ];
            
            $menuItems['Bimbingan Konseling'] = [
                [
                    'title' => 'Curhat Siswa',
                    'route' => 'guru.curhat-siswa-management',
                    'icon' => 'ri-chat-heart-line',
                    'permission' => 'manage-curhat'
                ]
            ];
            
            $menuItems['Operasional'] = [
                [
                    'title' => 'Presensi Siswa',
                    'route' => 'presensi',
                    'icon' => 'ri-user-check-line',
                    'permission' => 'manage-presensi'
                ],
                [
                    'title' => 'Rekap Presensi',
                    'route' => 'rekap-presensi',
                    'icon' => 'ri-file-list-3-line',
                    'permission' => 'view-presensi'
                ],
                [
                    'title' => 'Manajemen Tugas',
                    'route' => 'tugas-management',
                    'icon' => 'ri-task-line',
                    'permission' => 'manage-tugas'
                ],
                [
                    'title' => 'Manajemen Nilai',
                    'route' => 'nilai-management',
                    'icon' => 'ri-award-line',
                    'permission' => 'manage-nilai'
                ],
                [
                    'title' => 'Jurnal Mengajar',
                    'route' => 'jurnal-mengajar',
                    'icon' => 'ri-file-text-line',
                    'permission' => 'manage-jurnal-mengajar'
                ],
                [
                    'title' => 'Rekap Nilai Siswa',
                    'route' => 'rekap-nilai',
                    'icon' => 'ri-file-chart-line',
                    'permission' => 'view-reports'
                ],
                [
                    'title' => 'Surat Otomatis',
                    'route' => 'surat-management',
                    'icon' => 'ri-file-text-line',
                    'permission' => 'manage-surat'
                ],

                [
                    'title' => 'Import Data Excel',
                    'route' => 'import-management',
                    'icon' => 'ri-file-upload-line',
                    'permission' => 'import-data'
                ],
                [
                    'title' => 'Statistik Sekolah',
                    'route' => 'statistik-management',
                    'icon' => 'ri-bar-chart-line',
                    'permission' => 'view-statistics'
                ],
                [
                    'title' => 'Role & Permission',
                    'route' => 'role-permission-management',
                    'icon' => 'ri-user-settings-line',
                    'permission' => 'manage-roles'
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
        }

        // Guru Menu Items
        if ($user->hasRole('guru')) {
            $menuItems['Pengajaran'] = [
                [
                    'title' => 'Kelas Saya',
                    'route' => 'my-classes',
                    'icon' => 'ri-building-line',
                    'permission' => 'manage-own-classes'
                ],
                [
                    'title' => 'Manajemen Tugas',
                    'route' => 'tugas-management',
                    'icon' => 'ri-task-line',
                    'permission' => 'manage-tugas'
                ],
                [
                    'title' => 'Manajemen Nilai',
                    'route' => 'nilai-management',
                    'icon' => 'ri-award-line',
                    'permission' => 'manage-nilai'
                ],
                [
                    'title' => 'Presensi Siswa',
                    'route' => 'presensi',
                    'icon' => 'ri-user-check-line',
                    'permission' => 'manage-presensi'
                ],
                [
                    'title' => 'Jurnal Mengajar',
                    'route' => 'jurnal-mengajar',
                    'icon' => 'ri-file-text-line',
                    'permission' => 'manage-jurnal-mengajar'
                ]
            ];
            
            $menuItems['Bimbingan Konseling'] = [
                [
                    'title' => 'Curhat Siswa',
                    'route' => 'guru.curhat-siswa-management',
                    'icon' => 'ri-chat-heart-line',
                    'permission' => 'manage-curhat'
                ]
            ];
            
            $menuItems['Laporan'] = [
                [
                    'title' => 'Rekap Presensi',
                    'route' => 'rekap-presensi',
                    'icon' => 'ri-file-list-3-line',
                    'permission' => 'view-presensi'
                ],
                [
                    'title' => 'Rekap Nilai',
                    'route' => 'rekap-nilai',
                    'icon' => 'ri-file-chart-line',
                    'permission' => 'view-reports'
                ]
            ];
        }

        // Siswa Menu Items
        if ($user->hasRole('siswa')) {
            $menuItems['Akademik'] = [
                [
                    'title' => 'Nilai Saya',
                    'route' => 'my-grades',
                    'icon' => 'ri-award-line',
                    'permission' => 'view-own-grades'
                ],
                [
                    'title' => 'Presensi Saya',
                    'route' => 'my-attendance',
                    'icon' => 'ri-calendar-check-line',
                    'permission' => 'view-own-attendance'
                ],
                [
                    'title' => 'Tugas Saya',
                    'route' => 'my-assignments',
                    'icon' => 'ri-task-line',
                    'permission' => 'view-own-assignments'
                ],
                [
                    'title' => 'Jadwal Pelajaran',
                    'route' => 'my-schedule',
                    'icon' => 'ri-calendar-2-line',
                    'permission' => 'view-own-schedule'
                ],
                [
                    'title' => 'Rapor Online',
                    'route' => 'my-report-card',
                    'icon' => 'ri-file-text-line',
                    'permission' => 'view-own-report'
                ]
            ];
            
            $menuItems['Layanan Siswa'] = [
                [
                    'title' => 'Curhat BK',
                    'route' => 'curhat-bk',
                    'icon' => 'ri-chat-heart-line',
                    'permission' => 'create-curhat'
                ],
                [
                    'title' => 'Perpustakaan',
                    'route' => 'library',
                    'icon' => 'ri-book-line',
                    'permission' => 'access-library'
                ],
                [
                    'title' => 'Ekstrakurikuler',
                    'route' => 'extracurricular',
                    'icon' => 'ri-team-line',
                    'permission' => 'view-extracurricular'
                ],
                [
                    'title' => 'Pengumuman',
                    'route' => 'announcements',
                    'icon' => 'ri-megaphone-line',
                    'permission' => 'view-announcements'
                ]
            ];
            
            $menuItems['Komunikasi'] = [
                [
                    'title' => 'Pesan Guru',
                    'route' => 'teacher-messages',
                    'icon' => 'ri-mail-line',
                    'permission' => 'view-messages'
                ],
                [
                    'title' => 'Forum Diskusi',
                    'route' => 'discussion-forum',
                    'icon' => 'ri-discuss-line',
                    'permission' => 'access-forum'
                ]
            ];

        }

        // Tata Usaha Menu Items
        if ($user->hasRole('tata_usaha')) {
            $menuItems['Pengaturan Dasar'] = [
                [
                    'title' => 'Tahun Pelajaran',
                    'route' => 'tahun-pelajaran-management',
                    'icon' => 'ri-calendar-line',
                    'permission' => 'manage-tahun-pelajaran'
                ],
                [
                    'title' => 'Mata Pelajaran',
                    'route' => 'mata-pelajaran-management',
                    'icon' => 'ri-book-line',
                    'permission' => 'manage-mata-pelajaran'
                ]
            ];
            
            $menuItems['Manajemen Data'] = [
                [
                    'title' => 'Data Guru',
                    'route' => 'guru-management',
                    'icon' => 'ri-user-3-line',
                    'permission' => 'manage-guru'
                ],
                [
                    'title' => 'Data Kelas',
                    'route' => 'kelas-management',
                    'icon' => 'ri-building-line',
                    'permission' => 'manage-kelas'
                ],
                [
                    'title' => 'Data Siswa',
                    'route' => 'class-management',
                    'icon' => 'ri-group-line',
                    'permission' => 'manage-siswa'
                ],
                [
                    'title' => 'Siswa Tidak Aktif',
                    'route' => 'inactive-siswa-management',
                    'icon' => 'ri-user-unfollow-line',
                    'permission' => 'manage-siswa'
                ],
                [
                    'title' => 'Data Perpustakaan',
                    'route' => 'perpustakaan-management',
                    'icon' => 'ri-book-open-line',
                    'permission' => 'manage-perpustakaan'
                ]
            ];
            
            $menuItems['Administrasi'] = [
                [
                    'title' => 'Surat Otomatis',
                    'route' => 'surat-management',
                    'icon' => 'ri-file-text-line',
                    'permission' => 'manage-surat'
                ],
                [
                    'title' => 'Import Data Excel',
                    'route' => 'import-management',
                    'icon' => 'ri-file-upload-line',
                    'permission' => 'import-data'
                ],
                [
                    'title' => 'Statistik Sekolah',
                    'route' => 'statistik-management',
                    'icon' => 'ri-bar-chart-line',
                    'permission' => 'view-statistics'
                ]
            ];
        }

        // BK (Bimbingan Konseling) Menu Items
        if ($user->hasRole('bk')) {
            $menuItems['Manajemen Pelanggaran'] = [
                [
                    'title' => 'Kategori Pelanggaran',
                    'route' => 'kategori-pelanggaran-management',
                    'icon' => 'ri-folder-line',
                    'permission' => 'manage-pelanggaran'
                ],
                [
                    'title' => 'Jenis Pelanggaran',
                    'route' => 'jenis-pelanggaran-management',
                    'icon' => 'ri-list-check-line',
                    'permission' => 'manage-pelanggaran'
                ],
                [
                    'title' => 'Sanksi Pelanggaran',
                    'route' => 'sanksi-pelanggaran-management',
                    'icon' => 'ri-scales-line',
                    'permission' => 'manage-pelanggaran'
                ],
                [
                    'title' => 'Data Pelanggaran Siswa',
                    'route' => 'pelanggaran-management',
                    'icon' => 'ri-alert-line',
                    'permission' => 'manage-pelanggaran'
                ]
            ];
            
            $menuItems['Bimbingan Konseling'] = [
                [
                    'title' => 'Curhat Siswa',
                    'route' => 'guru.curhat-siswa-management',
                    'icon' => 'ri-chat-heart-line',
                    'permission' => 'manage-curhat'
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