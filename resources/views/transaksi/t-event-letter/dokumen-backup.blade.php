<html>
<head>
    <style>
        p {
            margin-top: 5px !important;
            margin-bottom: 5px !important;
        }

        li {
            margin-top: 5px !important;
            margin-bottom: 5px !important;
        }

        ol {
            margin-top: 5px !important;
            margin-bottom: 5px !important;
        }
    </style>
</head>
<body>
<table width="100%">
    <tr>
        <td width="50%">Nomor : {{ $letter->no_surat }}</td>
        <td width="50%" style="text-align: right;">{{ $sent_date }}</td>
    </tr>
</table>

<br>

<p>Kepada Yth,</p>
<p>Pengurus Provinsi Perbasi</p>
<ol>
    @foreach($region as $item)
        <li>{{ $item['region'] }}</li>
    @endforeach
</ol>
<p>di Tempat</p>
<br>

<table width="100%">
    <tr>
        <td width="10%" style="vertical-align: top;"><b>Perihal</b></td>
        <td width="1%" style="vertical-align: top;"><b>:</b></td>
        <td><b>Surat Tugas Koordinator Wasit, Wasit, dan Pengawas Pertandingan Kegiatan {{ $letter->perihal }}</b></td>
    </tr>
</table>

<br>
<p>Salam Olahraga,</p>
<br>

<p>Sehubungan dengan akan dilaksanakan kegiatan {{ $letter->perihal }} pada tanggal @if($monthStart == $monthEnd) {{ $monthStart }} @else {{ $monthStart }} s/d {{ $monthEnd }} @endif
, bersama ini PP Perbasi memberikan tugas kepada :</p>

<br>

<p><b>Pengawas Pertandingan :</b></p>
<table width="100%" style="margin-left:5%">
    <?php $i = 1 ?>
    @foreach($pengawas as $item)
        <tr>
            <td width="3%">{{ $i }}.</td>
            <td width="30%">{{ $item['name'] }}</td>
            <td>- {{ $item['region'] }}</td>
        </tr>
        <?php $i++ ?>
    @endforeach
</table>

<br>

<p><b>Koordinator Wasit :</b></p>
<table width="100%" style="margin-left:5%">
    <?php $i = 1 ?>
    @foreach($koordinator as $item)
        <tr>
            <td width="3%">{{ $i }}.</td>
            <td width="30%">{{ $item['name'] }}</td>
            <td>- {{ $item['region'] }}</td>
        </tr>
        <?php $i++ ?>
    @endforeach
</table>
<br>

<p><b>Wasit :</b></p>
<table width="100%" style="margin-left:5%">
    <?php $i = 1 ?>
    @foreach($wasit as $item)
        <tr>
            <td width="3%">{{ $i }}.</td>
            <td width="30%">{{ $item['name'] }}</td>
            <td>- {{ $item['region'] }}</td>
        </tr>
        <?php $i++ ?>
    @endforeach
</table>

<br>
<p>Sebagai Koordinator Wasit, Wasit, dan Pengawas Pertandingan di kegiatan {{ $letter->perihal }}.</p>
<p>Demikian surat tugas ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>

<br>

<p>Hormat Kami,</p>
<p><b>PENGURUS PUSAT</b></p>
<p><b>PERSATUAN BOLA BASKET SELURUH INDONESIA</b></p>

<br><br><br><br>

<p><b><u>{{ $ketum->name }}</u></b></p>
<p>Ketua Umum</p>

<br>

<p>Contact Person :</p>
<ol>
    @foreach($cp as $item)
        <li>{{ $item['nama'] }} ({{ $item['telepon'] }})</li>
    @endforeach
</ol>

<br>

<p>Tembusan Yth :</p>
<ol>
    @foreach($tembusan as $item)
        <li>{{ $item['nama'] }}</li>
    @endforeach
</ol>

</body>
</html>