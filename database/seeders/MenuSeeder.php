<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Helpers\MenuHelper;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing menus
        Menu::truncate();

        // Get menu items from MenuHelper
        $menuItems = $this->getStaticMenuItems();
        
        $order = 1;
        
        foreach ($menuItems as $section => $items) {
            foreach ($items as $item) {
                Menu::create([
                    'title' => $item['title'],
                    'route' => $item['route'] ?? null,
                    'icon' => $item['icon'],
                    'permission' => $item['permission'],
                    'section' => $section,
                    'roles' => $item['roles'] ?? ['admin'], // Default to admin if not specified
                    'order' => $order++,
                    'is_active' => true,
                    'has_submenu' => isset($item['submenu']) && !empty($item['submenu']),
                    'parent_id' => null,
                    'description' => $item['description'] ?? null
                ]);
                
                // Handle submenu if exists
                if (isset($item['submenu']) && !empty($item['submenu'])) {
                    $parentMenu = Menu::where('title', $item['title'])->first();
                    
                    foreach ($item['submenu'] as $submenuItem) {
                        Menu::create([
                            'title' => $submenuItem['title'],
                            'route' => $submenuItem['route'] ?? null,
                            'icon' => $submenuItem['icon'] ?? 'ri-arrow-right-s-line',
                            'permission' => $submenuItem['permission'] ?? $item['permission'],
                            'section' => $section,
                            'roles' => $submenuItem['roles'] ?? $item['roles'] ?? ['admin'],
                            'order' => $order++,
                            'is_active' => true,
                            'has_submenu' => false,
                            'parent_id' => $parentMenu->id,
                            'description' => $submenuItem['description'] ?? null
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Get static menu items (extracted from MenuHelper for seeding)
     */
    private function getStaticMenuItems(): array
    {
        return [
            'Menu' => [
                [
                    'title' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' => 'ri-dashboard-line',
                    'permission' => 'view-dashboard',
                    'roles' => ['admin', 'guru', 'siswa', 'tata_usaha', 'bk']
                ]
            ],
            'Pengaturan Dasar' => [
                [
                    'title' => 'Tahun Pelajaran',
                    'route' => 'tahun-pelajaran-management',
                    'icon' => 'ri-calendar-line',
                    'permission' => 'manage-tahun-pelajaran',
                    'roles' => ['admin', 'tata_usaha']
                ],
                [
                    'title' => 'Mata Pelajaran',
                    'route' => 'mata-pelajaran-management',
                    'icon' => 'ri-book-line',
                    'permission' => 'manage-mata-pelajaran',
                    'roles' => ['admin', 'tata_usaha']
                ]
            ],
            'Manajemen Data' => [
                [
                    'title' => 'Manajemen Users',
                    'route' => 'user-management',
                    'icon' => 'ri-user-settings-line',
                    'permission' => 'manage-users',
                    'roles' => ['admin']
                ],
                [
                    'title' => 'Manajemen Menu',
                    'route' => 'menu-management',
                    'icon' => 'ri-menu-line',
                    'permission' => 'manage-menu',
                    'roles' => ['admin']
                ],
                [
                    'title' => 'Data Guru',
                    'route' => 'guru-management',
                    'icon' => 'ri-user-3-line',
                    'permission' => 'manage-guru',
                    'roles' => ['admin', 'tata_usaha']
                ],
                [
                    'title' => 'Data Kelas',
                    'route' => 'kelas-management',
                    'icon' => 'ri-building-line',
                    'permission' => 'manage-kelas',
                    'roles' => ['admin', 'tata_usaha']
                ],
                [
                    'title' => 'Data Siswa',
                    'route' => 'class-management',
                    'icon' => 'ri-group-line',
                    'permission' => 'manage-siswa',
                    'roles' => ['admin', 'tata_usaha']
                ],
                [
                    'title' => 'Siswa Tidak Aktif',
                    'route' => 'inactive-siswa-management',
                    'icon' => 'ri-user-unfollow-line',
                    'permission' => 'manage-siswa',
                    'roles' => ['admin', 'tata_usaha']
                ],
                [
                    'title' => 'Data Perpustakaan',
                    'route' => 'perpustakaan-management',
                    'icon' => 'ri-book-open-line',
                    'permission' => 'manage-perpustakaan',
                    'roles' => ['admin', 'tata_usaha']
                ],
                [
                    'title' => 'Jadwal Guru',
                    'route' => 'jadwal-management',
                    'icon' => 'ri-calendar-2-line',
                    'permission' => 'manage-jadwal',
                    'roles' => ['admin']
                ]
            ],
            'Manajemen Pelanggaran' => [
                [
                    'title' => 'Kategori Pelanggaran',
                    'route' => 'kategori-pelanggaran-management',
                    'icon' => 'ri-folder-line',
                    'permission' => 'manage-pelanggaran',
                    'roles' => ['admin', 'bk']
                ],
                [
                    'title' => 'Jenis Pelanggaran',
                    'route' => 'jenis-pelanggaran-management',
                    'icon' => 'ri-list-check-line',
                    'permission' => 'manage-pelanggaran',
                    'roles' => ['admin', 'bk']
                ],
                [
                    'title' => 'Sanksi Pelanggaran',
                    'route' => 'sanksi-pelanggaran-management',
                    'icon' => 'ri-scales-line',
                    'permission' => 'manage-pelanggaran',
                    'roles' => ['admin', 'bk']
                ],
                [
                    'title' => 'Data Pelanggaran Siswa',
                    'route' => 'pelanggaran-management',
                    'icon' => 'ri-alert-line',
                    'permission' => 'manage-pelanggaran',
                    'roles' => ['admin', 'bk']
                ]
            ],
            'Bimbingan Konseling' => [
                [
                    'title' => 'Curhat Siswa',
                    'route' => 'guru.curhat-siswa-management',
                    'icon' => 'ri-chat-heart-line',
                    'permission' => 'manage-curhat',
                    'roles' => ['admin', 'guru', 'bk']
                ]
            ],
            'Operasional' => [
                [
                    'title' => 'Presensi Siswa',
                    'route' => 'presensi',
                    'icon' => 'ri-user-check-line',
                    'permission' => 'manage-presensi',
                    'roles' => ['admin', 'guru']
                ],
                [
                    'title' => 'Rekap Presensi',
                    'route' => 'rekap-presensi',
                    'icon' => 'ri-file-list-3-line',
                    'permission' => 'view-presensi',
                    'roles' => ['admin', 'guru']
                ],
                [
                    'title' => 'Manajemen Tugas',
                    'route' => 'tugas-management',
                    'icon' => 'ri-task-line',
                    'permission' => 'manage-tugas',
                    'roles' => ['admin', 'guru']
                ],
                [
                    'title' => 'Manajemen Nilai',
                    'route' => 'nilai-management',
                    'icon' => 'ri-award-line',
                    'permission' => 'manage-nilai',
                    'roles' => ['admin', 'guru']
                ],
                [
                    'title' => 'Jurnal Mengajar',
                    'route' => 'jurnal-mengajar',
                    'icon' => 'ri-file-text-line',
                    'permission' => 'manage-jurnal-mengajar',
                    'roles' => ['admin', 'guru']
                ],
                [
                    'title' => 'Rekap Nilai Siswa',
                    'route' => 'rekap-nilai',
                    'icon' => 'ri-file-chart-line',
                    'permission' => 'view-reports',
                    'roles' => ['admin', 'guru']
                ],
                [
                    'title' => 'Surat Otomatis',
                    'route' => 'surat-management',
                    'icon' => 'ri-file-text-line',
                    'permission' => 'manage-surat',
                    'roles' => ['admin', 'tata_usaha']
                ],
                [
                    'title' => 'Import Data Excel',
                    'route' => 'import-management',
                    'icon' => 'ri-file-upload-line',
                    'permission' => 'import-data',
                    'roles' => ['admin', 'tata_usaha']
                ],
                [
                    'title' => 'Statistik Sekolah',
                    'route' => 'statistik-management',
                    'icon' => 'ri-bar-chart-line',
                    'permission' => 'view-statistics',
                    'roles' => ['admin', 'tata_usaha']
                ],
                [
                    'title' => 'Role & Permission',
                    'route' => 'role-permission-management',
                    'icon' => 'ri-user-settings-line',
                    'permission' => 'manage-roles',
                    'roles' => ['admin']
                ],
                [
                    'title' => 'Laporan',
                    'icon' => 'ri-pie-chart-line',
                    'permission' => 'view-reports',
                    'roles' => ['admin'],
                    'submenu' => [
                        ['title' => 'Laporan Siswa'],
                        ['title' => 'Laporan Perpustakaan'],
                        ['title' => 'Laporan Kelas'],
                        ['title' => 'Laporan Presensi', 'route' => 'rekap-presensi']
                    ]
                ]
            ],
            'Pengajaran' => [
                [
                    'title' => 'Kelas Saya',
                    'route' => 'my-classes',
                    'icon' => 'ri-building-line',
                    'permission' => 'manage-own-classes',
                    'roles' => ['guru']
                ]
            ],
            'Akademik' => [
                [
                    'title' => 'Nilai Saya',
                    'route' => 'my-grades',
                    'icon' => 'ri-award-line',
                    'permission' => 'view-own-grades',
                    'roles' => ['siswa']
                ],
                [
                    'title' => 'Presensi Saya',
                    'route' => 'my-attendance',
                    'icon' => 'ri-calendar-check-line',
                    'permission' => 'view-own-attendance',
                    'roles' => ['siswa']
                ],
                [
                    'title' => 'Tugas Saya',
                    'route' => 'my-assignments',
                    'icon' => 'ri-task-line',
                    'permission' => 'view-own-assignments',
                    'roles' => ['siswa']
                ],
                [
                    'title' => 'Jadwal Pelajaran',
                    'route' => 'my-schedule',
                    'icon' => 'ri-calendar-2-line',
                    'permission' => 'view-own-schedule',
                    'roles' => ['siswa']
                ],
                [
                    'title' => 'Rapor Online',
                    'route' => 'my-report-card',
                    'icon' => 'ri-file-text-line',
                    'permission' => 'view-own-report',
                    'roles' => ['siswa']
                ]
            ],
            'Layanan Siswa' => [
                [
                    'title' => 'Curhat BK',
                    'route' => 'curhat-bk',
                    'icon' => 'ri-chat-heart-line',
                    'permission' => 'create-curhat',
                    'roles' => ['siswa']
                ],
                [
                    'title' => 'Perpustakaan',
                    'route' => 'library',
                    'icon' => 'ri-book-line',
                    'permission' => 'access-library',
                    'roles' => ['siswa']
                ],
                [
                    'title' => 'Ekstrakurikuler',
                    'route' => 'extracurricular',
                    'icon' => 'ri-team-line',
                    'permission' => 'view-extracurricular',
                    'roles' => ['siswa']
                ],
                [
                    'title' => 'Pengumuman',
                    'route' => 'announcements',
                    'icon' => 'ri-megaphone-line',
                    'permission' => 'view-announcements',
                    'roles' => ['siswa']
                ]
            ],
            'Komunikasi' => [
                [
                    'title' => 'Pesan Guru',
                    'route' => 'teacher-messages',
                    'icon' => 'ri-mail-line',
                    'permission' => 'view-messages',
                    'roles' => ['siswa']
                ],
                [
                    'title' => 'Forum Diskusi',
                    'route' => 'discussion-forum',
                    'icon' => 'ri-discuss-line',
                    'permission' => 'access-forum',
                    'roles' => ['siswa']
                ]
            ],
            'Administrasi' => [
                [
                    'title' => 'Surat Otomatis',
                    'route' => 'surat-management',
                    'icon' => 'ri-file-text-line',
                    'permission' => 'manage-surat',
                    'roles' => ['tata_usaha']
                ],
                [
                    'title' => 'Import Data Excel',
                    'route' => 'import-management',
                    'icon' => 'ri-file-upload-line',
                    'permission' => 'import-data',
                    'roles' => ['tata_usaha']
                ],
                [
                    'title' => 'Statistik Sekolah',
                    'route' => 'statistik-management',
                    'icon' => 'ri-bar-chart-line',
                    'permission' => 'view-statistics',
                    'roles' => ['tata_usaha']
                ]
            ]
        ];
    }
}