<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Magic Links - {{ $kelas->nama_kelas }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }
        .header h2 {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
            font-weight: normal;
        }
        .info {
            margin-bottom: 20px;
        }
        .info-row {
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .magic-link {
            word-break: break-all;
            font-size: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .qr-info {
            margin-top: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-left: 4px solid #007bff;
        }
        .qr-info h4 {
            margin: 0 0 10px 0;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DAFTAR MAGIC LINKS SISWA</h1>
        <h2>{{ $kelas->nama_kelas }}</h2>
    </div>

    <div class="info">
        <div class="info-row">
            <span class="info-label">Kelas:</span>
            {{ $kelas->nama_kelas }}
        </div>
        <div class="info-row">
            <span class="info-label">Tahun Pelajaran:</span>
            {{ $kelas->tahunPelajaran->nama_tahun_pelajaran ?? '-' }}
        </div>
        <div class="info-row">
            <span class="info-label">Wali Kelas:</span>
            {{ $kelas->guru->nama_guru ?? '-' }}
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Cetak:</span>
            {{ $tanggal }}
        </div>
        <div class="info-row">
            <span class="info-label">Jumlah Siswa:</span>
            {{ count($siswaList) }} siswa
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Nama Siswa</th>
                <th style="width: 15%;">NIS</th>
                <th style="width: 15%;">NISN</th>
                <th style="width: 40%;">Magic Link</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswaList as $index => $siswa)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $siswa['nama_siswa'] }}</td>
                <td>{{ $siswa['nis'] }}</td>
                <td>{{ $siswa['nisn'] }}</td>
                <td class="magic-link">{{ $siswa['magic_link'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="qr-info">
        <h4>Informasi Magic Link:</h4>
        <ul style="margin: 0; padding-left: 20px;">
            <li>Magic link berlaku hingga 1 Juli 2026</li>
            <li>Link dapat digunakan untuk mengakses form pelanggaran siswa</li>
            <li>Setiap siswa memiliki link unik yang tidak dapat digunakan oleh siswa lain</li>
            <li>Pastikan link disimpan dengan aman dan tidak dibagikan kepada pihak yang tidak berwenang</li>
        </ul>
    </div>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem DigiClass</p>
        <p>Tanggal: {{ $tanggal }} | Kelas: {{ $kelas->nama_kelas }}</p>
    </div>
</body>
</html>