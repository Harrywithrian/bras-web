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
    <center><img height="60px;" src="{{ public_path() . "/demo1/media/logos/logo.png" }}"></center>
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
            <td colspan="11" style="background-color:#42A5F5"><center><b>Calls Summary</b></center></td>
        </tr>
        <tr>
            <td style="background-color:#42A5F5"><center><b>Periodes</b></center></td>
            <td colspan="2" style="background-color:#42A5F5"><center><b>1st Period</b></center></td>
            <td colspan="2" style="background-color:#42A5F5"><center><b>2nd Period</b></center></td>
            <td colspan="2" style="background-color:#42A5F5"><center><b>3rd Period</b></center></td>
            <td colspan="2" style="background-color:#42A5F5"><center><b>4th Period</b></center></td>
            <td rowspan="2" colspan="2" style="vertical-align : middle;text-align:center;background-color:#42A5F5;"><center><b>Total</b></center></td>
        </tr>
        <tr>
            <td style="background-color:#42A5F5"><center><b>Time</b></center></td>
            @for($i = 0;$i < 4;$i++)
                <td style="background-color:#42A5F5"><center><b>5'</b></center></td>
                <td style="background-color:#42A5F5"><center><b>10'</b></center></td>
            @endfor
        </tr>

        {{-- Summary Calls --}}
        <tr>
            <td rowspan="2" style="vertical-align : middle;text-align:center;background-color:#42A5F5;"><center><b>Calls</b></center></td>
            @foreach($summary['summary'] as $item)
                <td style="background-color:#64B5F6"><center>{{ $item['firstcall'] }} ({{ number_format($item['firstPercent'], 0) }}%)</center></td>
                <td style="background-color:#64B5F6"><center>{{ $item['secondcall'] }} ({{ number_format($item['secondPercent'], 0) }}%)</center></td>
            @endforeach
            <td style="background-color:#64B5F6"><center>{{ $summary['tSummary']['first'] }} ({{ number_format($summary['tSummary']['firstPercent'], 0) }}%)</center></td>
            <td style="background-color:#64B5F6"><center>{{ $summary['tSummary']['second'] }} ({{ number_format($summary['tSummary']['secondPercent'], 0) }}%)</center></td>
        </tr>
        <tr>
            @foreach($summary['summary'] as $item)
                <td colspan="2" style="background-color:#BBDEFB"><center>{{ $item['total'] }}  ({{ number_format($item['totalPercent'], 0) }}%)</center></td>
            @endforeach
            <td colspan="2" style="background-color:#BBDEFB"><center>{{ $summary['tSummary']['total'] }} (100%)</center></td>
        </tr>

        {{-- Summary Fouls --}}
        <tr>
            <td rowspan="2" style="vertical-align : middle;text-align:center;background-color:#42A5F5;"><center><b>Fouls</b></center></td>
            @foreach($summary['foulSummary']['fouls'] as $item)
                <td style="background-color:#64B5F6"><center>{{ $item['firstcall'] }} ({{ number_format($item['firstPercent'], 0) }}%)</center></td>
                <td style="background-color:#64B5F6"><center>{{ $item['secondcall'] }} ({{ number_format($item['secondPercent'], 0) }}%)</center></td>
            @endforeach
            <td style="background-color:#64B5F6"><center>{{ $summary['foulTotalSummary']['fouls']['first'] }} ({{ number_format($summary['foulTotalSummary']['fouls']['firstPercent'], 0) }}%)</center></td>
            <td style="background-color:#64B5F6"><center>{{ $summary['foulTotalSummary']['fouls']['second'] }} ({{ number_format($summary['foulTotalSummary']['fouls']['secondPercent'], 0) }}%)</center></td>
        </tr>
        <tr>
            @foreach($summary['foulSummary']['fouls'] as $item)
                <td colspan="2" style="background-color:#BBDEFB"><center>{{ $item['total'] }}  ({{ number_format($item['totalPercent'], 0) }}%)</center></td>
            @endforeach
            <td colspan="2" style="background-color:#BBDEFB"><center>{{ $summary['foulTotalSummary']['fouls']['total'] }} ({{ number_format($summary['foulTotalSummary']['fouls']['summaryPercent'], 0) }}%)</center></td>
        </tr>

        {{-- Summary IRS --}}
        <tr>
            <td rowspan="2" style="vertical-align : middle;text-align:center;background-color:#42A5F5;"><center><b>IRS</b></center></td>
            @foreach($summary['foulSummary']['IRS'] as $item)
                <td style="background-color:#64B5F6"><center>{{ $item['firstcall'] }} ({{ number_format($item['firstPercent'], 0) }}%)</center></td>
                <td style="background-color:#64B5F6"><center>{{ $item['secondcall'] }} ({{ number_format($item['secondPercent'], 0) }}%)</center></td>
            @endforeach
            <td style="background-color:#64B5F6"><center>{{ $summary['foulTotalSummary']['IRS']['first'] }} ({{ number_format($summary['foulTotalSummary']['IRS']['firstPercent'], 0) }}%)</center></td>
            <td style="background-color:#64B5F6"><center>{{ $summary['foulTotalSummary']['IRS']['second'] }} ({{ number_format($summary['foulTotalSummary']['IRS']['secondPercent'], 0) }}%)</center></td>
        </tr>
        <tr>
            @foreach($summary['foulSummary']['IRS'] as $item)
                <td colspan="2" style="background-color:#BBDEFB"><center><center>{{ $item['total'] }}  ({{ number_format($item['totalPercent'], 0) }}%)</center></td>
            @endforeach
            <td colspan="2" style="background-color:#BBDEFB"><center><center>{{ $summary['foulTotalSummary']['IRS']['total'] }} ({{ number_format($summary['foulTotalSummary']['IRS']['summaryPercent'], 0) }}%)</center></td>
        </tr>

        {{-- Summary IRS --}}
        <tr>
            <td rowspan="2" style="vertical-align : middle;text-align:center;background-color:#42A5F5;"><center><b>Travelling</b></center></td>
            @foreach($summary['foulSummary']['Travelling'] as $item)
                <td style="background-color:#64B5F6"><center>{{ $item['firstcall'] }} ({{ number_format($item['firstPercent'], 0) }}%)</center></td>
                <td style="background-color:#64B5F6"><center>{{ $item['secondcall'] }} ({{ number_format($item['secondPercent'], 0) }}%)</center></td>
            @endforeach
            <td style="background-color:#64B5F6"><center>{{ $summary['foulTotalSummary']['Travelling']['first'] }} ({{ number_format($summary['foulTotalSummary']['Travelling']['firstPercent'], 0) }}%)</center></td>
            <td style="background-color:#64B5F6"><center>{{ $summary['foulTotalSummary']['Travelling']['second'] }} ({{ number_format($summary['foulTotalSummary']['Travelling']['secondPercent'], 0) }}%)</center></td>
        </tr>
        <tr>
            @foreach($summary['foulSummary']['Travelling'] as $item)
                <td colspan="2" style="background-color:#BBDEFB"><center><center>{{ $item['total'] }}  ({{ number_format($item['totalPercent'], 0) }}%)</center></td>
            @endforeach
            <td colspan="2" style="background-color:#BBDEFB"><center><center>{{ $summary['foulTotalSummary']['Travelling']['total'] }} ({{ number_format($summary['foulTotalSummary']['Travelling']['summaryPercent'], 0) }}%)</center></td>
        </tr>

        {{--Other Violation --}}
        <tr>
            <td rowspan="2" style="vertical-align : middle;text-align:center; background-color:#42A5F5;"><center><b>Other Violations</b></center></td>
            @foreach($summary['foulSummary']['Other Violations'] as $item)
                <td style="background-color:#64B5F6"><center>{{ $item['firstcall'] }} ({{ number_format($item['firstPercent'], 0) }}%)</center></td>
                <td style="background-color:#64B5F6"><center>{{ $item['secondcall'] }} ({{ number_format($item['secondPercent'], 0) }}%)</center></td>
            @endforeach
            <td style="background-color:#64B5F6"><center>{{ $summary['foulTotalSummary']['Other Violations']['first'] }} ({{ number_format($summary['foulTotalSummary']['Other Violations']['firstPercent'], 0) }}%)</center></td>
            <td style="background-color:#64B5F6"><center>{{ $summary['foulTotalSummary']['Other Violations']['second'] }} ({{ number_format($summary['foulTotalSummary']['Other Violations']['secondPercent'], 0) }}%)</center></td>
        </tr>
        <tr>
            @foreach($summary['foulSummary']['Other Violations'] as $item)
                <td colspan="2" style="background-color:#BBDEFB"><center>{{ $item['total'] }}  ({{ number_format($item['totalPercent'], 0) }}%)</center></td>
            @endforeach
            <td colspan="2" style="background-color:#BBDEFB"><center>{{ $summary['foulTotalSummary']['Other Violations']['total'] }} ({{ number_format($summary['foulTotalSummary']['Other Violations']['summaryPercent'], 0) }}%)</center></td>
        </tr>
    </table>

    <div class="page_break"></div>

    <table>
        <tr>
            <td colspan="11" style="background-color:#42A5F5"><center><b>Calls per Referee</b></center></td>
        </tr>
        <tr>
            <td style="background-color:#42A5F5"><center><b>Periodes</b></center></td>
            <td colspan="2" style="background-color:#42A5F5"><center><b>1st Period</b></center></td>
            <td colspan="2" style="background-color:#42A5F5"><center><b>2nd Period</b></center></td>
            <td colspan="2" style="background-color:#42A5F5"><center><b>3rd Period</b></center></td>
            <td colspan="2" style="background-color:#42A5F5"><center><b>4th Period</b></center></td>
            <td rowspan="2" colspan="2" style="vertical-align : middle;text-align:center;background-color:#42A5F5;"><center><b>Total</b></center></td>
        </tr>
        <tr>
            <td style="background-color:#42A5F5"><center><b>Time</b></center></td>
            @for($i = 0;$i < 4;$i++)
                <td style="background-color:#42A5F5"><center><b>5'</b></center></td>
                <td style="background-color:#42A5F5"><center><b>10'</b></center></td>
            @endfor
        </tr>

        <?php $i = 0; ?>
        @foreach ($summary['referee'] as $item)
            <tr>
                <td rowspan="2" style="vertical-align : middle;text-align:center;background-color:#42A5F5;"><center><b>{{ $item['name'] }}</b></center></td>
                @for($j = 1;$j < 5;$j++)
                    <td style="vertical-align : middle;text-align:center;background-color:#64B5F6;"><center>{{ $item[$j]['firstcall'] }} ({{ number_format($item[$j]['firstPercent'], 0) }}%)</center></td>
                    <td style="vertical-align : middle;text-align:center;background-color:#64B5F6;"><center>{{ $item[$j]['secondcall'] }} ({{ number_format($item[$j]['secondPercent'], 0) }}%)</center></td>
                @endfor
                <td style="vertical-align : middle;text-align:center;background-color:#64B5F6;"><center>{{ $summary['refereeTotal'][$i]['first'] }} ({{ number_format($summary['refereeTotal'][$i]['firstPercent'], 0) }}%)</center></td>
                <td style="vertical-align : middle;text-align:center;background-color:#64B5F6;"><center>{{ $summary['refereeTotal'][$i]['second'] }} ({{ number_format($summary['refereeTotal'][$i]['secondPercent'], 0) }}%)</center></td>
            </tr>
            <tr>
                @for($j = 1;$j < 5;$j++)
                    <td colspan="2" style="vertical-align : middle;text-align:center;background-color:#BBDEFB;"><center>{{ $item[$j]['total'] }}  ({{ number_format($item[$j]['totalPercent'], 0) }}%)</center></td>
                    @endfor
                <td colspan="2" style="vertical-align : middle;text-align:center;background-color:#BBDEFB;"><center>{{ $summary['refereeTotal'][$i]['total'] }} ({{ number_format($summary['refereeTotal'][$i]['summaryPercent'], 0) }}%)</center></td>
            </tr>
            <?php $i++ ?>
        @endforeach
        <tr>
            <td style="background-color:#42A5F5"><center><b>Total</b></center></td>
            <td colspan="8" style="background-color:#42A5F5"></td>
            <td colspan="2" style="background-color:#42A5F5"><center>{{ $summary['tSummary']['total'] }} (100%)</center></td>
        </tr>
    </table>

    <div class="page_break"></div>
    <table>
        <tr>
            <td>Catatan</td>
        </tr>
        <tr>
            <td>{{ $catatan->notes }}</td>
        </tr>
    </table>

</body>
</html>