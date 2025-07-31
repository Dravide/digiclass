<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class LinkUserToGuruSiswa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:link {email} {--type=} {--target-email=} {--nip=} {--nis=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Link user account to guru or siswa data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userEmail = $this->argument('email');
        $type = $this->option('type');
        $targetEmail = $this->option('target-email');
        $nip = $this->option('nip');
        $nis = $this->option('nis');

        // Find user
        $user = User::where('email', $userEmail)->first();
        if (!$user) {
            $this->error("User dengan email {$userEmail} tidak ditemukan.");
            return 1;
        }

        // If no type specified, ask user
        if (!$type) {
            $type = $this->choice('Pilih tipe akun:', ['guru', 'siswa']);
        }

        if ($type === 'guru') {
            return $this->linkToGuru($user, $targetEmail, $nip);
        } elseif ($type === 'siswa') {
            return $this->linkToSiswa($user, $targetEmail, $nis);
        } else {
            $this->error('Tipe harus guru atau siswa.');
            return 1;
        }
    }

    private function linkToGuru(User $user, $targetEmail = null, $nip = null)
    {
        // If target email provided, find guru by email
        if ($targetEmail) {
            $guru = Guru::where('email', $targetEmail)->first();
            if (!$guru) {
                $this->error("Guru dengan email {$targetEmail} tidak ditemukan.");
                return 1;
            }
        }
        // If NIP provided, find guru by NIP
        elseif ($nip) {
            $guru = Guru::where('nip', $nip)->first();
            if (!$guru) {
                $this->error("Guru dengan NIP {$nip} tidak ditemukan.");
                return 1;
            }
        }
        // Otherwise, show list of guru without email
        else {
            $gurusWithoutEmail = Guru::whereNull('email')->orWhere('email', '')->get();
            
            if ($gurusWithoutEmail->isEmpty()) {
                $this->info('Semua guru sudah memiliki email. Menampilkan semua guru:');
                $gurusWithoutEmail = Guru::all();
            }

            $choices = [];
            foreach ($gurusWithoutEmail as $g) {
                $choices[$g->id] = "{$g->nama_guru} (NIP: {$g->nip})" . ($g->email ? " - Email: {$g->email}" : '');
            }

            if (empty($choices)) {
                $this->error('Tidak ada data guru yang tersedia.');
                return 1;
            }

            $selectedId = $this->choice('Pilih guru:', $choices);
            $guru = $gurusWithoutEmail->find(array_search($selectedId, $choices));
        }

        // Update guru email
        DB::beginTransaction();
        try {
            $guru->update(['email' => $user->email]);
            
            // Update user role if needed
            if ($user->role !== 'guru') {
                $user->update(['role' => 'guru']);
                $this->info("Role user diubah menjadi 'guru'.");
            }
            
            DB::commit();
            $this->info("Berhasil menghubungkan user {$user->email} dengan guru {$guru->nama_guru} (NIP: {$guru->nip}).");
            return 0;
        } catch (\Exception $e) {
            DB::rollback();
            $this->error("Gagal menghubungkan: " . $e->getMessage());
            return 1;
        }
    }

    private function linkToSiswa(User $user, $targetEmail = null, $nis = null)
    {
        // If target email provided, find siswa by email
        if ($targetEmail) {
            $siswa = Siswa::where('email', $targetEmail)->first();
            if (!$siswa) {
                $this->error("Siswa dengan email {$targetEmail} tidak ditemukan.");
                return 1;
            }
        }
        // If NIS provided, find siswa by NIS
        elseif ($nis) {
            $siswa = Siswa::where('nis', $nis)->first();
            if (!$siswa) {
                $this->error("Siswa dengan NIS {$nis} tidak ditemukan.");
                return 1;
            }
        }
        // Otherwise, show list of siswa without email
        else {
            $siswaWithoutEmail = Siswa::whereNull('email')->orWhere('email', '')->get();
            
            if ($siswaWithoutEmail->isEmpty()) {
                $this->info('Semua siswa sudah memiliki email. Menampilkan semua siswa:');
                $siswaWithoutEmail = Siswa::all();
            }

            $choices = [];
            foreach ($siswaWithoutEmail as $s) {
                $choices[$s->id] = "{$s->nama_siswa} (NIS: {$s->nis})" . ($s->email ? " - Email: {$s->email}" : '');
            }

            if (empty($choices)) {
                $this->error('Tidak ada data siswa yang tersedia.');
                return 1;
            }

            $selectedId = $this->choice('Pilih siswa:', $choices);
            $siswa = $siswaWithoutEmail->find(array_search($selectedId, $choices));
        }

        // Update siswa email
        DB::beginTransaction();
        try {
            $siswa->update(['email' => $user->email]);
            
            // Update user role if needed
            if ($user->role !== 'siswa') {
                $user->update(['role' => 'siswa']);
                $this->info("Role user diubah menjadi 'siswa'.");
            }
            
            DB::commit();
            $this->info("Berhasil menghubungkan user {$user->email} dengan siswa {$siswa->nama_siswa} (NIS: {$siswa->nis}).");
            return 0;
        } catch (\Exception $e) {
            DB::rollback();
            $this->error("Gagal menghubungkan: " . $e->getMessage());
            return 1;
        }
    }
}