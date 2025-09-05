<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\HariLiburService;
use Carbon\Carbon;

class SinkronisasiHariLibur extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hari-libur:sync {tahun?} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi data hari libur nasional dari API dayoffapi.vercel.app';

    /**
     * Execute the console command.
     */
    public function handle(HariLiburService $hariLiburService)
    {
        $tahun = $this->argument('tahun') ?? Carbon::now()->year;
        $force = $this->option('force');

        $this->info("Memulai sinkronisasi hari libur untuk tahun {$tahun}...");
        
        if ($force) {
            $this->warn('Mode force aktif - akan menimpa data yang sudah ada');
        }

        try {
            // Progress bar
            $this->output->progressStart(3);
            
            // Step 1: Ambil data dari API
            $this->output->progressAdvance();
            $this->line('\nMengambil data dari API...');
            
            // Step 2: Sinkronisasi ke database
            $this->output->progressAdvance();
            $this->line('Menyimpan ke database...');
            
            $result = $hariLiburService->sinkronisasiHariLibur($tahun);
            
            // Step 3: Selesai
            $this->output->progressAdvance();
            $this->output->progressFinish();
            
            if ($result['success']) {
                $this->info('\n✅ Sinkronisasi berhasil!');
                $this->table(
                    ['Keterangan', 'Jumlah'],
                    [
                        ['Total Data', $result['total_data']],
                        ['Data Baru', $result['data_baru']],
                        ['Data Diupdate', $result['data_diupdate']]
                    ]
                );
                
                $this->info($result['message']);
                return Command::SUCCESS;
            } else {
                $this->error('❌ Sinkronisasi gagal: ' . $result['message']);
                return Command::FAILURE;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Terjadi kesalahan: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
