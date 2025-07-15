<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Nilai - {{ $kelas->nama_kelas }}</title>
    <style>
        @page {
            margin: 10mm;
            size: A4 landscape;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            margin: 0;
            padding: 0;
        }
        
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        
        th, td {
            border: 1px solid #000;
            text-align: center;
            vertical-align: middle;
            padding: 1px;
            font-size: 7px;
        }
        
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 7px;
        }
        
        .no-col {
            width: 20px;
        }
        
        .nama-col {
            width: 100px;
            text-align: left;
            padding-left: 3px;
        }
        
        .jk-col {
            width: 15px;
        }
        
        .materi-col {
            width: 15px;
            font-size: 6px;
        }
        
        .sumatif-col {
            width: 15px;
            font-size: 6px;
            background-color: #f8f8f8;
        }
        
        .footer-info {
            margin-top: 10px;
            font-size: 7px;
            text-align: left;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="text-align: left; font-weight: bold; font-size: 10px; border: 0px solid #000; padding: 3px;">SMP NEGERI 1 CIPANAS</td>
            <td style="text-align: center; font-weight: bold; font-size: 10px; border: 0px solid #000; padding: 3px;">DAFTAR NILAI KELAS : {{ $kelas->nama_kelas }}</td>
            <td style="text-align: center; font-weight: bold; font-size: 10px; border: 0px solid #000; padding: 3px;">MATA PELAJARAN : {{ $mataPelajaran->nama_mapel }}</td>
            <td style="text-align: right; font-weight: bold; font-size: 10px; border: 0px solid #000; padding: 3px;">TAHUN PELAJARAN: {{ $tahunPelajaran->nama_tahun_pelajaran }}</td>
        </tr>
    </table>
    
    <table>
        <thead>
            <tr>
                <th rowspan="3" class="no-col">NOMOR URUT</th>
                <th rowspan="3" class="no-col">NOMOR INDUK</th>
                <th rowspan="3" class="nama-col">NAMA PESERTA DIDIK</th>
                <th rowspan="3" class="jk-col">L/P</th>
                <th colspan="12" style="font-size: 8px;">LINGKUP MATERI 1</th>
                <th colspan="12" style="font-size: 8px;">LINGKUP MATERI 2</th>
                <th colspan="12" style="font-size: 8px;">LINGKUP MATERI 3</th>
                <th colspan="3" style="font-size: 8px; background-color: #f8f8f8;">SUMATIF LINGKUP MATERI</th>
            </tr>
            <tr>
                @for($i = 1; $i <= 3; $i++)
                    @for($j = 1; $j <= 4; $j++)
                        <th colspan="3" class="materi-col">TP{{ $j }}</th>
                    @endfor
                @endfor
                @for($i = 1; $i <= 3; $i++)
                    <th class="sumatif-col">LM{{ $i }}</th>
                @endfor
            </tr>
            <tr>
                @for($i = 1; $i <= 3; $i++)
                    @for($j = 1; $j <= 4; $j++)
                        <th class="materi-col">TP1</th>
                        <th class="materi-col">TP2</th>
                        <th class="materi-col">TP3</th>
                    @endfor
                @endfor
                @for($i = 1; $i <= 3; $i++)
                    <th class="sumatif-col">&nbsp;</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach($siswaList as $index => $siswa)
                <tr>
                    <td class="no-col">{{ $index + 1 }}</td>
                    <td class="no-col">{{ $siswa->nis ?? '' }}</td>
                    <td class="nama-col">{{ strtoupper($siswa->nama_siswa) }}</td>
                    <td class="jk-col">{{ $siswa->jk }}</td>
                    @for($i = 1; $i <= 36; $i++)
                        <td class="materi-col">&nbsp;</td>
                    @endfor
                    @for($i = 1; $i <= 3; $i++)
                        <td class="sumatif-col">&nbsp;</td>
                    @endfor
                </tr>
            @endforeach
            
            <!-- Baris kosong untuk tambahan siswa sampai maksimal 40 -->
            @for($i = $totalSiswa + 1; $i <= 40; $i++)
                <tr>
                    <td class="no-col">{{ $i }}</td>
                    <td class="no-col">&nbsp;</td>
                    <td class="nama-col">&nbsp;</td>
                    <td class="jk-col">&nbsp;</td>
                    @for($j = 1; $j <= 36; $j++)
                        <td class="materi-col">&nbsp;</td>
                    @endfor
                    @for($j = 1; $j <= 3; $j++)
                        <td class="sumatif-col">&nbsp;</td>
                    @endfor
                </tr>
            @endfor
        </tbody>
    </table>
    
    <table style="margin-top: 15px; width: 100%; border: none;">
        <tr>
            <td style="width: 80%; text-align: left; border: none; font-size: 8px; padding: 10px; vertical-align: top;">
                <div>Mengetahui</div>
                <div>Kepala Sekolah</div>
                <div style="height: 40px;"></div>
                <div style="border-bottom: 0px solid #000; margin-bottom: 3px;"></div>
                <div><strong>JAMIN, S.Pd, M.Pd</strong></div>
                <div>NIP 196804119890331004</div>
            </td>
            <td style="width: 20%; text-align: left; border: none; font-size: 8px; padding: 10px; vertical-align: top;">
                <div>Cipanas,</div>
                <div>Guru Mata Pelajaran</div>
                <div style="height: 40px;"></div>
                <div style="border-bottom: 0px solid #000; margin-bottom: 3px;"></div>
                <div><strong>______________________</strong></div>
                <div>NIP.</div>
            </td>
        </tr>
    </table>
    
    <div class="footer-info">
        <div>Dicetak pada: {{ $generatedAt }} | Sistem Informasi Manajemen Kelas - SMP Negeri 1 Cipanas</div>
    </div>
</body>
</html>