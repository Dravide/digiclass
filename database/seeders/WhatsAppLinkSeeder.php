<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelas;

class WhatsAppLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelasData = [
            '10 IPA 1' => 'https://chat.whatsapp.com/10IPA1DigiClass2024',
            '10 IPA 2' => 'https://chat.whatsapp.com/10IPA2DigiClass2024',
            '10 IPS 1' => 'https://chat.whatsapp.com/10IPS1DigiClass2024',
            '11 IPA 1' => 'https://chat.whatsapp.com/11IPA1DigiClass2024',
            '11 IPA 2' => 'https://chat.whatsapp.com/11IPA2DigiClass2024',
            '11 IPS 1' => 'https://chat.whatsapp.com/11IPS1DigiClass2024',
            '12 IPA 1' => 'https://chat.whatsapp.com/12IPA1DigiClass2024',
            '12 IPA 2' => 'https://chat.whatsapp.com/12IPA2DigiClass2024',
            '12 IPS 1' => 'https://chat.whatsapp.com/12IPS1DigiClass2024',
        ];

        foreach ($kelasData as $namaKelas => $linkWa) {
            $kelas = Kelas::where('nama_kelas', $namaKelas)->first();
            if ($kelas) {
                $kelas->update(['link_wa' => $linkWa]);
                $this->command->info("Updated WhatsApp link for class: {$namaKelas}");
            }
        }

        $this->command->info('WhatsApp links seeded successfully!');
    }
}