<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan Akun - {{ $siswa_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 30px;
            background: white;
            color: #000;
            line-height: 1.6;
        }
        
        .letter-container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        
        .logo {
            width: 80px;
            height: 60px;
            margin: 0 auto 10px;
            display: block;
        }
        
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0;
            text-transform: uppercase;
        }
        
        .header h2 {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
            text-transform: uppercase;
        }
        
        .content {
            margin: 30px 0;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .info-table td {
            padding: 8px;
            border: 1px solid #000;
            font-size: 14px;
        }
        
        .info-table .label {
            width: 200px;
            font-weight: bold;
            background: #f5f5f5;
        }
        
        .info-table .colon {
            width: 20px;
            text-align: center;
            font-weight: bold;
        }
        
        .description {
            text-align: justify;
            margin: 20px 0;
            font-size: 14px;
        }
        
        .qr-section {
            text-align: center;
            margin: 30px 0;
        }
        
        .qr-code {
            width: 150px;
            height: 150px;
            border: 2px solid #000;
            margin: 10px auto;
        }
        
        .footer-note {
            font-style: italic;
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            border-top: 1px solid #000;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="letter-container">
        <div class="header">
            <img src="{{ public_path('assets/images/logo-dark.png') }}" alt="DigiClass Logo" class="logo">
            <h1>KARTU INFORMASI AKUN</h1>
             <h2>SISTEM PELAPORAN PELANGGARAN SISWA 2024</h2>
        </div>
        
        <div class="content">
            <table class="info-table">
                <tr>
                     <td class="label">No. Identitas NISN</td>
                     <td class="colon">:</td>
                     <td>{{ $siswa_nisn }}</td>
                 </tr>
                 <tr>
                     <td class="label">Nama Akun</td>
                     <td class="colon">:</td>
                     <td>{{ $siswa_name }}</td>
                 </tr>
                 <tr>
                     <td class="label">Nama sesuai Data Siswa</td>
                     <td class="colon">:</td>
                     <td>{{ $siswa_name }}</td>
                 </tr>
                 <tr>
                     <td class="label">NIS</td>
                     <td class="colon">:</td>
                     <td>{{ $siswa_nis }}</td>
                 </tr>
                 <tr>
                     <td class="label">Kelas</td>
                     <td class="colon">:</td>
                     <td>{{ $kelas }}</td>
                 </tr>
                 <tr>
                     <td class="label">Jenis Kelamin</td>
                     <td class="colon">:</td>
                     <td>-</td>
                 </tr>
                 <tr>
                     <td class="label">Tgl / Jam Pendaftaran</td>
                     <td class="colon">:</td>
                     <td>{{ $expires_at }}</td>
                 </tr>
            </table>
            
            <div class="description">
                 Selamat, Anda telah berhasil registrasi tahap awal pada portal Sistem Pelaporan Pelanggaran Siswa.
             </div>
             
             <div class="description">
                 Silahkan lanjutkan masuk ke {{ $magic_link }} pada menu "Login" dengan menggunakan link atau scan QR Code yang telah disediakan.
             </div>
            
            <div class="qr-section">
                <img src="{{ $qr_code }}" alt="QR Code" class="qr-code">
            </div>
            
            <div class="footer-note">
                "Demikian data pribadi ini saya buat dengan sebenarnya dan bila ternyata isian yang dibuat tidak benar, saya bersedia menanggung akibat hukum yang ditimbulkannya"
            </div>
        </div>
    </div>
</body>
</html>