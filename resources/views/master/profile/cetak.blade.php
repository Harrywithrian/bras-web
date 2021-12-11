<html>
<head>
    <style>
        h2, h4 {
            margin-top:3px;
            margin-bottom:3px;
        }
        table {
            /*border: solid 1px black;*/
            width:100%;
            border-collapse: collapse;
        }
        td {
            border: solid 1px black;
            padding: 5px;
        }
        .page_break {
            page-break-before: always;
        }

        hr {
            border-bottom: solid 1px black;
        }
    </style>
</head>
<body>
    <center><img height="40px;" src="{{ public_path() . "/demo1/media/logos/logo.svg" }}"></center>
    <center><h2>{{ $event->nama }}</h2></center>
    <center><h2>{{ $match->nama }}</h2></center>
    <center><h4>Waktu Pertandingan : {{ date('H:i d/m/Y', strtotime($match->waktu_pertandingan)) }}</h4></center>
    <center><h4>Lokasi Pertandingan : {{ $lokasi->nama }}</h4></center>

    <br><br>

    <table width="100%">
        <tr>
            <td><center><b>Crew Chief</b></center></td>
            <td><center><b>Official 1</b></center></td>
            <td><center><b>Official 2</b></center></td>
        </tr>
        <tr>
            <td><center><img width="20%" class="responsive" src="{{ public_path() . "/storage/".$foto1->path }}"></center></td>
            <td><center><img width="20%" class="responsive" src="{{ public_path() . "/storage/".$foto2->path }}"></center></td>
            <td><center><img width="20%" class="responsive" src="{{ public_path() . "/storage/".$foto3->path }}"></center></td>
        </tr>
        <tr>
            <td><center>{{ $wst1->name }}</center></td>
            <td><center>{{ $wst2->name }}</center></td>
            <td><center>{{ $wst3->name }}</center></td>
        </tr>
        <tr>
            <td><center>{{ $detail1->no_lisensi }} ({{$license1->license}})</center></td>
            <td><center>{{ $detail2->no_lisensi }} ({{$license2->license}})</center></td>
            <td><center>{{ $detail3->no_lisensi }} ({{$license3->license}})</center></td>
        </tr>
        <tr>
            <td><center>{{ $region1->region }}</center></td>
            <td><center>{{ $region2->region }}</center></td>
            <td><center>{{ $region3->region }}</center></td>
        </tr>
    </table>

    <div class="page_break"></div>

    <table>
        <tr>
            <td colspan="10"><center>Calls Summary</center></td>
        </tr>
        <tr>
            <td><center>Periodes</center></td>
            <td colspan="2"><center>1st Period</center></td>
            <td colspan="2"><center>2nd Period</center></td>
            <td colspan="2"><center>3rd Period</center></td>
            <td colspan="2"><center>4th Period</center></td>
            <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>Total</center></td>
        </tr>
        <tr>
            <td><center>Time</center></td>
            <td><center>5'</center></td>
            <td><center>10'</center></td>
            <td><center>5'</center></td>
            <td><center>10'</center></td>
            <td><center>5'</center></td>
            <td><center>10'</center></td>
            <td><center>5'</center></td>
            <td><center>10'</center></td>
        </tr>
        <tr>
            <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>{{ $wst1->name }}</center></td>
            @for($i = 1;$i < 5;$i++)
                <td><center>{{ $callReferee['wst1'][$i]['first'] }} ({{ number_format($callReferee['wst1'][$i]['firstPercent'], 0) }}%)</center></td>
                <td><center>{{ $callReferee['wst1'][$i]['second'] }} ({{ number_format($callReferee['wst1'][$i]['secondPercent'], 0) }}%)</center></td>
            @endfor
            <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>{{ $callReferee['wst1']['total'] }} ({{ number_format($callReferee['wst1']['totalPercent'], 0) }}%)</center></td>
        </tr>
        <tr>
            @for($i = 1;$i < 5;$i++)
                <td colspan="2"><center>{{ $callReferee['wst1'][$i]['total'] }} ({{ number_format($callReferee['wst1'][$i]['totalPercent'], 0) }}%)</center></td>
            @endfor
        </tr>
        <tr>
            <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>{{ $wst2->name }}</center></td>
            @for($i = 1;$i < 5;$i++)
                <td><center>{{ $callReferee['wst2'][$i]['first'] }} ({{ number_format($callReferee['wst2'][$i]['firstPercent'], 0) }}%)</center></td>
                <td><center>{{ $callReferee['wst2'][$i]['second'] }} ({{ number_format($callReferee['wst2'][$i]['secondPercent'], 0) }}%)</center></td>
            @endfor
            <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>{{ $callReferee['wst2']['total'] }} ({{ number_format($callReferee['wst2']['totalPercent'], 0) }}%)</center></td>
        </tr>
        <tr>
            @for($i = 1;$i < 5;$i++)
                <td colspan="2"><center>{{ $callReferee['wst2'][$i]['total'] }} ({{ number_format($callReferee['wst2'][$i]['totalPercent'], 0) }}%)</center></td>
            @endfor
        </tr>
        <tr>
            <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>{{ $wst3->name }}</center></td>
            @for($i = 1;$i < 5;$i++)
                <td><center>{{ $callReferee['wst3'][$i]['first'] }} ({{ number_format($callReferee['wst3'][$i]['firstPercent'], 0) }}%)</center></td>
                <td><center>{{ $callReferee['wst3'][$i]['second'] }} ({{ number_format($callReferee['wst3'][$i]['secondPercent'], 0) }}%)</center></td>
            @endfor
            <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>{{ $callReferee['wst3']['total'] }} ({{ number_format($callReferee['wst3']['totalPercent'], 0) }}%)</center></td>
        </tr>
        <tr>
            @for($i = 1;$i < 5;$i++)
                <td colspan="2"><center>{{ $callReferee['wst3'][$i]['total'] }} ({{ number_format($callReferee['wst3'][$i]['totalPercent'], 0) }}%)</center></td>
            @endfor
        </tr>
        <tr>
            <td colspan="9"><center>Total</center></td>
            <td><center>{{ $playCalling['total'] }} (100%)</center></td>
        </tr>
    </table>

    <div class="page_break"></div>

    <table>
        <tr>
            <td colspan="10"><center>Calls per Referee</center></td>
        </tr>
        <tr>
            <td><center>Periodes</center></td>
            <td colspan="2"><center>1st Period</center></td>
            <td colspan="2"><center>2nd Period</center></td>
            <td colspan="2"><center>3rd Period</center></td>
            <td colspan="2"><center>4th Period</center></td>
            <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>Total</center></td>
        </tr>
        <tr>
            <td><center>Time</center></td>
            <td><center>5'</center></td>
            <td><center>10'</center></td>
            <td><center>5'</center></td>
            <td><center>10'</center></td>
            <td><center>5'</center></td>
            <td><center>10'</center></td>
            <td><center>5'</center></td>
            <td><center>10'</center></td>
        </tr>
        <tr>
            <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>{{ $wst1->name }}</center></td>
            @for($i = 1;$i < 5;$i++)
                <td><center>{{ $callReferee['wst1'][$i]['first'] }} ({{ number_format($callReferee['wst1'][$i]['firstPercent'], 0) }}%)</center></td>
                <td><center>{{ $callReferee['wst1'][$i]['second'] }} ({{ number_format($callReferee['wst1'][$i]['secondPercent'], 0) }}%)</center></td>
            @endfor
            <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>{{ $callReferee['wst1']['total'] }} ({{ number_format($callReferee['wst1']['totalPercent'], 0) }}%)</center></td>
        </tr>
        <tr>
            @for($i = 1;$i < 5;$i++)
                <td colspan="2"><center>{{ $callReferee['wst1'][$i]['total'] }} ({{ number_format($callReferee['wst1'][$i]['totalPercent'], 0) }}%)</center></td>
            @endfor
        </tr>
        <tr>
            <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>{{ $wst2->name }}</center></td>
            @for($i = 1;$i < 5;$i++)
                <td><center>{{ $callReferee['wst2'][$i]['first'] }} ({{ number_format($callReferee['wst2'][$i]['firstPercent'], 0) }}%)</center></td>
                <td><center>{{ $callReferee['wst2'][$i]['second'] }} ({{ number_format($callReferee['wst2'][$i]['secondPercent'], 0) }}%)</center></td>
            @endfor
            <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>{{ $callReferee['wst2']['total'] }} ({{ number_format($callReferee['wst2']['totalPercent'], 0) }}%)</center></td>
        </tr>
        <tr>
            @for($i = 1;$i < 5;$i++)
                <td colspan="2"><center>{{ $callReferee['wst2'][$i]['total'] }} ({{ number_format($callReferee['wst2'][$i]['totalPercent'], 0) }}%)</center></td>
            @endfor
        </tr>
        <tr>
            <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>{{ $wst3->name }}</center></td>
            @for($i = 1;$i < 5;$i++)
                <td><center>{{ $callReferee['wst3'][$i]['first'] }} ({{ number_format($callReferee['wst3'][$i]['firstPercent'], 0) }}%)</center></td>
                <td><center>{{ $callReferee['wst3'][$i]['second'] }} ({{ number_format($callReferee['wst3'][$i]['secondPercent'], 0) }}%)</center></td>
            @endfor
            <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>{{ $callReferee['wst3']['total'] }} ({{ number_format($callReferee['wst3']['totalPercent'], 0) }}%)</center></td>
        </tr>
        <tr>
            @for($i = 1;$i < 5;$i++)
                <td colspan="2"><center>{{ $callReferee['wst3'][$i]['total'] }} ({{ number_format($callReferee['wst3'][$i]['totalPercent'], 0) }}%)</center></td>
            @endfor
        </tr>
        <tr>
            <td colspan="9"><center>Total</center></td>
            <td><center>{{ $playCalling['total'] }} (100%)</center></td>
        </tr>
    </table>
</body>
</html>