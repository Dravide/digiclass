<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Peringatan {{ $jenisSP }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 10px;
        }
        .school-name {
            font-size: 16pt;
            font-weight: bold;
            margin: 5px 0;
        }
        .school-address {
            font-size: 10pt;
            margin: 2px 0;
        }
        .letter-info {
            margin: 20px 0;
        }
        .letter-info table {
            width: 100%;
        }
        .letter-info td {
            padding: 2px 0;
            vertical-align: top;
        }
        .content {
            text-align: justify;
            margin: 20px 0;
        }
        .student-data {
            margin: 15px 0;
        }
        .student-data table {
            width: 100%;
            border-collapse: collapse;
        }
        .student-data td {
            padding: 5px;
            border: 1px solid #000;
        }
        .violation-list {
            margin: 15px 0;
        }
        .violation-list ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        .signature-space {
            margin-top: 80px;
        }
        .underline {
            text-decoration: underline;
        }
        .bold {
            font-weight: bold;
        }
        .center {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header Sekolah -->
    <div class="header">
        <div class="school-name">SMP NEGERI 1 CIPANAS</div>
        <div class="school-address">Jl. Raya Cipanas No. 123, Cipanas, Cianjur, Jawa Barat 43253</div>
        <div class="school-address">Telp: (0263) 123456 | Email: info@smpn1cipanas.sch.id</div>
    </div>

    <!-- Informasi Surat -->
    <div class="letter-info">
        <table>
            <tr>
                <td width="20%">Nomor</td>
                <td width="5%">:</td>
                <td>{{ $nomorSurat }}</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td class="bold">
                    @if($jenisSP == '1')
                        Surat Peringatan Pertama (Teguran)
                    @elseif($jenisSP == '2')
                        Surat Peringatan Kedua (Tindakan)
                    @else
                        Surat Peringatan Ketiga (Sanksi Tegas)
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <br>

    <!-- Alamat Tujuan -->
    <div>
        <p>Kepada Yth<br>
        Bapak / Ibu Orang Tua Siswa dari<br>
        <span class="bold">{{ $siswa->nama_siswa }} ({{ $kelas->nama_kelas }})</span><br>
        di<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tempat</p>
    </div>

    <br>

    <!-- Isi Surat -->
    <div class="content">
        <p><span class="bold">Dengan Hormat,</span></p>
        
        <p>Sehubungan dengan pelanggaran yang dilakukan oleh anak Bapak/Ibu, <span class="bold">{{ $siswa->nama_siswa }}</span>, yang merupakan siswa kelas <span class="bold">{{ $kelas->nama_kelas }}</span> di <span class="bold">SMP Negeri 1 Cipanas</span>, kami informasikan bahwa siswa tersebut telah mencapai batas poin pelanggaran yang telah ditetapkan oleh pihak sekolah. Hal ini sesuai dengan sistem penilaian pelanggaran yang berlaku di <span class="bold">SMP Negeri 1 Cipanas</span>.</p>

        <p class="bold underline">Data Siswa</p>
        <div class="student-data">
            <table>
                <tr>
                    <td width="40%" class="bold">Nama Siswa</td>
                    <td>: {{ $siswa->nama_siswa }}</td>
                </tr>
                <tr>
                    <td class="bold">Kelas</td>
                    <td>: {{ $kelas->nama_kelas }}</td>
                </tr>
                <tr>
                    <td class="bold">Nomor Induk Siswa</td>
                    <td>: {{ $siswa->nis }}</td>
                </tr>
                <tr>
                    <td class="bold">Nomor Induk Siswa Nasional</td>
                    <td>: {{ $siswa->nisn }}</td>
                </tr>
            </table>
        </div>

        <p class="bold underline">Pelanggaran yang Dilakukan dan Poin</p>
        
        @if($isManual)
            <p>Berdasarkan catatan kami, siswa telah melanggar peraturan sekolah sebagai berikut:</p>
            
            @if(!empty($manualPelanggaran))
            <div class="violation-list">
                <ul>
                    @foreach($manualPelanggaran as $pelanggaran)
                    <li>{{ $pelanggaran }}</li>
                    @endforeach
                </ul>
            </div>
            @else
            <p>Pelanggaran akan diisi sesuai dengan kasus yang terjadi.</p>
            @endif
        @else
            <p>Berdasarkan catatan kami, siswa telah melanggar peraturan sekolah dengan mendapatkan total poin pelanggaran sebanyak:</p>
            
            <div class="violation-list">
                <ul>
                    @foreach($pelanggaranList as $pelanggaran)
                    <li>{{ $pelanggaran->jenisPelanggaran->nama_pelanggaran }} : {{ $pelanggaran->jenisPelanggaran->poin }} poin</li>
                    @endforeach
                    <li class="bold">Total Poin Pelanggaran : {{ $totalPoin }} poin</li>
                </ul>
            </div>

            <p>Sesuai dengan ketentuan yang berlaku, batas poin pelanggaran yang mengakibatkan tindakan tegas adalah <span class="bold">{{ $sanksi->batas_poin_min ?? 'sesuai ketentuan' }}</span>, dan saat ini anak Anda telah mencapai atau melampaui batas tersebut.</p>
        @endif

        <p class="bold underline">Sanksi yang Diberikan</p>
        <p>Berdasarkan peraturan yang berlaku, sanksi yang diberikan kepada siswa adalah:</p>
        
        <div class="violation-list">
            <ul>
                @if($isManual)
                    <li>{{ $sanksi->deskripsi_sanksi }}</li>
                @else
                    <li>{{ $sanksi->jenis_sanksi }}</li>
                    <li>{{ $sanksi->deskripsi_sanksi }}</li>
                @endif
                @if($jenisSP == '3')
                <li>Pemberian Pekerjaan Sosial selama 1 minggu</li>
                @endif
            </ul>
        </div>

        <p>Kami berharap agar tindakan ini menjadi perhatian dan langkah bersama untuk mencegah terulangnya pelanggaran serupa di masa mendatang. Diharapkan kerjasama dari Bapak/Ibu untuk memberikan pengertian kepada anak agar lebih berhati-hati dan mematuhi peraturan sekolah.</p>

        <p>Demikian surat peringatan ini kami sampaikan, semoga dapat menjadi perhatian dan langkah perbaikan ke depan.</p>
    </div>

    <!-- Tanda Tangan -->
    <div class="signature">
        <p>Cipanas, {{ $tanggalSurat }}<br>
        Kepala Sekolah,</p>
        
        <div class="signature-space"></div>
        
        <p class="bold underline">Jaimin, S.Pd., M.Pd.</p>
        <p>NIP 196604111989031004</p>
    </div>
</body>
</html>