<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard permissions
            'view-dashboard',
            
            // User management
            'manage-users',
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'manage-menu',
            
            // Academic management
            'manage-tahun-pelajaran',
            'manage-mata-pelajaran',
            'manage-kelas',
            'manage-siswa',
            'manage-guru',
            'manage-tata-usaha',
            
            // Academic operations
            'manage-jadwal',
            'manage-tugas',
            'manage-nilai',
            'manage-presensi',
            'view-presensi',
            'manage-jurnal-mengajar',
            
            // Library management
            'manage-perpustakaan',
            'manage-library-books',
            'manage-library-borrowing',
            'manage-library-staff',
            'view-library-reports',
            'manage-library-attendance',
            
            // Reports and statistics
            'view-reports',
            'view-statistics',
            'export-data',
            
            // Administrative
            'manage-surat',
            'manage-pelanggaran',
            'manage-curhat',
            'import-data',
            
            // Games and activities
            'manage-sudoku-room',
            'manage-typing-race',
            'play-games',
            
            // Student specific
            'view-own-grades',
            'view-own-attendance',
            'view-own-assignments',
            'submit-assignments',
            
            // Teacher specific
            'manage-own-classes',
            'grade-assignments',
            'take-attendance',
            'create-assignments',
            
            // Tata Usaha specific
            'manage-administrative-data',
            'process-documents',
            'manage-student-records',
            
            // Security
            'generate-secure-code',
            'scan-qr-presensi',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Admin Role - Full access
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        // Guru Role - Teaching related permissions
        $guruRole = Role::firstOrCreate(['name' => 'guru']);
        $guruRole->syncPermissions([
            'view-dashboard',
            'view-users',
            'manage-own-classes',
            'manage-tugas',
            'manage-nilai',
            'grade-assignments',
            'create-assignments',
            'manage-presensi',
            'take-attendance',
            'view-presensi',
            'manage-jurnal-mengajar',
            'view-reports',
            'export-data',
            'play-games',
        ]);

        // Siswa Role - Student permissions
        $siswaRole = Role::firstOrCreate(['name' => 'siswa']);
        $siswaRole->syncPermissions([
            'view-dashboard',
            'view-own-grades',
            'view-own-attendance',
            'view-own-assignments',
            'submit-assignments',
            'play-games',
        ]);

        // Tata Usaha Role - Administrative permissions
        $tataUsahaRole = Role::firstOrCreate(['name' => 'tata_usaha']);
        $tataUsahaRole->syncPermissions([
            'view-dashboard',
            'manage-users',
            'view-users',
            'create-users',
            'edit-users',
            'manage-tahun-pelajaran',
            'manage-mata-pelajaran',
            'manage-kelas',
            'manage-siswa',
            'manage-guru',
            'manage-tata-usaha',
            'manage-perpustakaan',
            'manage-administrative-data',
            'process-documents',
            'manage-student-records',
            'manage-surat',
            'import-data',
            'view-reports',
            'view-statistics',
            'export-data',
        ]);

        // BK (Bimbingan Konseling) Role - Guidance counselor permissions
        $bkRole = Role::firstOrCreate(['name' => 'bk']);
        $bkRole->syncPermissions([
            'view-dashboard',
            'view-users',
            'manage-pelanggaran',
            'manage-curhat',
            'manage-surat',
            'view-reports',
            'view-statistics',
            'export-data',
            'manage-siswa',
            'view-presensi',
        ]);

        // Create default admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@digiclass.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ]
        );
        $adminUser->assignRole('admin');

        // Create sample guru user
        $guruUser = User::firstOrCreate(
            ['email' => 'guru@digiclass.com'],
            [
                'name' => 'Guru Sample',
                'password' => Hash::make('guru123'),
                'role' => 'guru'
            ]
        );
        $guruUser->assignRole('guru');

        // Create sample siswa user
        $siswaUser = User::firstOrCreate(
            ['email' => 'siswa@digiclass.com'],
            [
                'name' => 'Siswa Sample',
                'password' => Hash::make('siswa123'),
                'role' => 'siswa'
            ]
        );
        $siswaUser->assignRole('siswa');

        // Create sample tata usaha user
        $tataUsahaUser = User::firstOrCreate(
            ['email' => 'tatausaha@digiclass.com'],
            [
                'name' => 'Tata Usaha Sample',
                'password' => Hash::make('tatausaha123'),
                'role' => 'tata_usaha'
            ]
        );
        $tataUsahaUser->assignRole('tata_usaha');

        // Create sample BK user
        $bkUser = User::firstOrCreate(
            ['email' => 'bk@digiclass.com'],
            [
                'name' => 'Guru BK Sample',
                'password' => Hash::make('bk123'),
                'role' => 'bk'
            ]
        );
        $bkUser->assignRole('bk');

        // Petugas Perpustakaan Role - Library management permissions
        $petugasPerpustakaanRole = Role::firstOrCreate(['name' => 'petugas_perpustakaan']);
        $petugasPerpustakaanRole->syncPermissions([
            'view-dashboard',
            'manage-library-books',
            'manage-library-borrowing',
            'manage-library-staff',
            'view-library-reports',
            'manage-library-attendance',
            'manage-perpustakaan',
            'view-reports',
            'export-data',
        ]);

        // Create sample petugas perpustakaan user
        $petugasPerpustakaanUser = User::firstOrCreate(
            ['email' => 'perpustakaan@digiclass.com'],
            [
                'name' => 'Petugas Perpustakaan',
                'password' => Hash::make('perpustakaan123'),
                'role' => 'petugas_perpustakaan'
            ]
        );
        $petugasPerpustakaanUser->assignRole('petugas_perpustakaan');
    }
}