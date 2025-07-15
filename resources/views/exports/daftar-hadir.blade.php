<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Hadir - {{ $kelas->nama_kelas }}</title>
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
            width: 25px;
        }
        
        .nama-col {
            width: 120px;
            text-align: left;
            padding-left: 3px;
        }
        
        .jk-col {
            width: 20px;
        }
        
        .tanggal-col {
            width: 15px;
            font-size: 6px;
        }
        
        .rekap-col {
            width: 20px;
            font-size: 6px;
            background-color: #f8f8f8;
        }
        

        
        .footer-info {
            margin-top: 10px;
            font-size: 7px;
            text-align: left;
        }
        
        .summary-box {
            border: 1px solid #000;
            padding: 5px;
            margin-top: 8px;
            font-size: 8px;
        }
        
        .summary-title {
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .summary-item {
            margin-bottom: 1px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="text-align: left; font-weight: bold; font-size: 10px; border: 0px solid #000; padding: 3px;">SMP NEGERI 1 CIPANAS</td>
            <td style="text-align: left; font-weight: bold; font-size: 10px; border: 0px solid #000; padding: 3px;">DAFTAR TATAP MUKA : {{ $kelas->nama_kelas }}</td>
            <td style="text-align: left; font-weight: bold; font-size: 10px; border: 0px solid #000; padding: 3px;">BULAN :</td>
            <td style="text-align: right; font-weight: bold; font-size: 10px; border: 0px solid #000; padding: 3px;">TAHUN PELAJARAN: {{ $tahunPelajaran->nama_tahun_pelajaran }}</td>
        </tr>
    </table>
    
    <table>
        <thead>
            <tr>
                <th rowspan="2" class="no-col">NO</th>
                <th rowspan="2" class="nama-col">NAMA PESERTA DIDIK</th>
                <th rowspan="2" class="jk-col">L/P</th>
                <th colspan="{{ count($tanggalList) }}" style="font-size: 8px;">TANGGAL</th>
                <th colspan="3" style="font-size: 8px; background-color: #f8f8f8;">REKAP</th>
            </tr>
            <tr>
                @foreach($tanggalList as $tanggal)
                    <th class="tanggal-col">{{ $tanggal }}</th>
                @endforeach
                <th class="rekap-col">S</th>
                <th class="rekap-col">I</th>
                <th class="rekap-col">A</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswaList as $index => $siswa)
                <tr>
                    <td class="no-col">{{ $index + 1 }}</td>
                    <td class="nama-col">{{ strtoupper($siswa->nama_siswa) }}</td>
                    <td class="jk-col">{{ $siswa->jk }}</td>
                    @foreach($tanggalList as $tanggal)
                        <td class="tanggal-col">&nbsp;</td>
                    @endforeach
                    <td class="rekap-col">&nbsp;</td>
                    <td class="rekap-col">&nbsp;</td>
                    <td class="rekap-col">&nbsp;</td>
                </tr>
            @endforeach
            
            <!-- Baris kosong untuk tambahan siswa sampai maksimal 40 -->
            @for($i = $totalSiswa + 1; $i <= 40; $i++)
                <tr>
                    <td class="no-col">{{ $i }}</td>
                    <td class="nama-col">&nbsp;</td>
                    <td class="jk-col">&nbsp;</td>
                    @foreach($tanggalList as $tanggal)
                        <td class="tanggal-col">&nbsp;</td>
                    @endforeach
                    <td class="rekap-col">&nbsp;</td>
                    <td class="rekap-col">&nbsp;</td>
                    <td class="rekap-col">&nbsp;</td>
                </tr>
            @endfor
        </tbody>
    </table>
    
    <div class="summary-box">
        <div class="summary-title">REKAPITULASI:</div>
        <div class="summary-item">Laki-laki: {{ $siswaList->where('jk', 'L')->count() }} siswa</div>
        <div class="summary-item">Perempuan: {{ $siswaList->where('jk', 'P')->count() }} siswa</div>
        <div class="summary-item">Total: {{ $totalSiswa }} siswa</div>
    </div>
    
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