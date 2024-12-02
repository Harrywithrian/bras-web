<html>
<head>
    <style>
        @page {
            margin: 150px 25px; /* Adjust top margin to ensure content does not overlap with header */
        }
        header {
            position: fixed;
            top: -120px;
            left: 0px;
            right: 0px;
            height: 50px;
            text-align: center;
        }

        hr {
            border: #0c1abc 1px solid;
        }
        .font-header-satu {
            font-size: 18px;
            color: #0c1abc;
        }

        .font-header-dua {
            font-size: 18px;
            color: #0c1abc;
            font-weight: bold;
        }

        .font-header-tiga {
            font-size: 12px;
            color: #0c1abc;
        }

        .content {
            font-size: 14px;
            margin-left: 65px;
            margin-right: 65px;
            margin-bottom: 50px;
        }

        .no-page-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <header>
        <span class="font-header-satu">PERSATUAN BOLA BASKET SELURUH INDONESIA</span><br>
        <span class="font-header-dua">(INDONESIAN BASKETBALL ASSOCIATION)</span><br>
        <span class="font-header-tiga">Gedung Basket, Jl. Asia Afrika, Senayan - Jakarta 10270 Telepon / Fax : (021) 574 2250</span><br>
        <span class="font-header-tiga">Email : perbasi_iba@yahoo.com / info@perbasi.or.id</span><br>
        <span class="font-header-tiga">Website : www.perbasi.or.id</span><br>

        <hr>
    </header>

    <div class="content">
        <table width="100%">
            <tr>
                <td width="50%">Nomor : {{ $letter->no_surat }}</td>
                <td width="50%" style="text-align: right;">{{ $sent_date }}</td>
            </tr>
        </table>

        <br>

        <table width="100%">
            <tr><td>Kepada Yth,</td></tr>
            <tr><td>Pengurus Provinsi Perbasi</td></tr>
            <?php $i = 1; ?>
            @foreach($region as $item)
            <tr>
                <td style="padding-left:50px;">{{ $i . ". " . $item['region'] }}</td>
            </tr>
            <?php $i++; ?>
            @endforeach
            <tr><td>di</td></tr>
            <tr><td style="padding-left:30px;">Tempat</td></tr>
        </table>

        <table width="100%">
            <tr>
                <td width="9%" style="vertical-align: top; font-weight: bold;">Perihal</td>
                <td width="1%" style="vertical-align: top; font-weight: bold;"><b>:</b></td>
                <td style="font-weight: bold;">Surat Tugas Koordinator Wasit, Wasit, dan Pengawas Pertandingan Kegiatan {{ $letter->perihal }}</b></td>
            </tr>
        </table>

        <p>Salam Olahraga,</p>
        <p>Sehubungan dengan akan dilaksanakan kegiatan {{ $letter->perihal }} pada tanggal @if($monthStart == $monthEnd) {{ $monthStart }} @else {{ $monthStart }} s/d {{ $monthEnd }} @endif
        , bersama ini PP Perbasi memberikan tugas kepada :</p>

        <br>

        <table width="100%">
            <tr><td style="font-weight: bold;" colspan="3">Pengawas Pertandingan :</td></tr>
            <?php $i = 1; ?>
            @foreach($pengawas as $item)
                <tr>
                    <td width="50%" style="padding-left:50px;">{{ $i }}. {{ $item['name'] }}</td>
                    <td>- {{ $item['region'] }}</td>
                </tr>
                <?php $i++ ?>
            @endforeach
        </table>

        <br>

        <table width="100%">
            <tr><td style="font-weight: bold;" colspan="3">Koordinator Wasit :</td></tr>
            <?php $i = 1; ?>
            @foreach($koordinator as $item)
                <tr>
                    <td width="50%" style="padding-left:50px;">{{ $i }}. {{ $item['name'] }}</td>
                    <td>- {{ $item['region'] }}</td>
                </tr>
                <?php $i++ ?>
            @endforeach
        </table>

        <br>

        <table width="100%">
            <tr><td style="font-weight: bold;" colspan="3">Wasit :</td></tr>
            <?php $i = 1; ?>
            @foreach($wasit as $item)
                <tr>
                    <td width="50%" style="padding-left:50px;">{{ $i }}. {{ $item['name'] }}</td>
                    <td>- {{ $item['region'] }}</td>
                </tr>
                <?php $i++ ?>
            @endforeach
        </table>

        <p>Sebagai Koordinator Wasit, Wasit, dan Pengawas Pertandingan di kegiatan {{ $letter->perihal }}. Demikian surat tugas ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>

        <table width="100%" class="no-page-break">
            <tr><td>Hormat Kami,</td></tr>
            <tr><td style="font-weight: bold;">PENGURUS PUSAT</td></tr>
            <tr><td style="font-weight: bold;">PERSATUAN BOLA BASKET SELURUH INDONESIA</td></tr>
            <tr><td><img height="100px;" src="{{ public_path() . "/storage/" . $letter->img_tanda_tangan }}"></td></tr>
            <tr><td style="font-weight: bold; text-decoration: underline;">{{ strtoupper($letter->nama_ketum) }}</td></tr>
            <tr><td>Ketua Umum</td></tr>
        </table>

        <br>

        <table width="100%">
            <tr><td colspan="3">Contact Person :</td></tr>
            <?php $i = 1; ?>
            @foreach($cp as $item)
                <tr>
                    <td width="50%" style="padding-left:50px;">{{ $i }}. {{ $item['nama'] }}</td>
                    <td>{{ $item['telepon'] }}</td>
                </tr>
                <?php $i++ ?>
            @endforeach
        </table>

        <br>

        <table width="100%">
            <tr><td colspan="2">Tembusan Yth:</td></tr>
            <?php $i = 1; ?>
            @foreach($tembusan as $item)
                <tr>
                    <td style="padding-left:50px;">{{ $i }}. {{ $item['nama'] }}</td>
                </tr>
                <?php $i++ ?>
            @endforeach
        </table>
    </div>
</body>
</html>