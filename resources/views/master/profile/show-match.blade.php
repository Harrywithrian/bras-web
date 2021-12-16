<x-base-layout>
    <style>
        tr td {
            border: 1px solid #ddd !important;
        }
    </style>

    <?php $title = $match->nama ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('profile.index', $id) }}" class="pe-3">Profile</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">

            <a href="{{ route('profile.print-match', [$id, $wasit]) }}" class="btn btn-primary"> Cetak </a>
            <a href="{{ route('profile.index', $wasit) }}" class="btn btn-secondary"> Kembali </a>

            <br><br>

            <section class="card bg-primary mb-0" style="border-radius: 0">
                <div class="card-header">
                    <h4 class="card-title" style="color: white;">General</h4>
                </div>
            </section>
            <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                <tr>
                    <td width="25%">Nama</td>
                    <td>{{ $match->nama }}</td>
                </tr>
                <tr>
                    <td width="25%">Event</td>
                    <td>{{ $event->nama }}</td>
                </tr>
                <tr>
                    <td width="25%">Lokasi</td>
                    <td>{{ $lokasi->nama }}</td>
                </tr>
                <tr>
                    <td width="25%">Waktu Pertandingan</td>
                    <td>{{ date('H:i', strtotime($match->waktu_pertandingan)) }}</td>
                </tr>
                <tr>
                    <td width="25%">Tanggal Pertandingan</td>
                    <td>{{ date('d-m-Y', strtotime($match->waktu_pertandingan)) }}</td>
                </tr>
                <tr>
                    <td width="25%">Status</td>
                    <td>
                        @if($match->status == 0)
                            <span class="w-130px badge badge-info me-4"> Belum Mulai </span>
                        @elseif($match->status == 1)
                            <span class="w-130px badge badge-primary me-4"> Berlangsung </span>
                        @elseif($match->status == 2)
                            <span class="w-130px badge badge-success me-4"> Selesai </span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </table>

            <section class="card bg-primary mb-0" style="border-radius: 0">
                <div class="card-header">
                    <h4 class="card-title" style="color: white;">Referee</h4>
                </div>
            </section>
            <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                <tr>
                    <td width="34%"><center>Crew Chief<br><h2>{{ $wst1->name }}</h2></center></td>
                    <td width="33%"><center>Official 1<br><h2>{{ $wst2->name }}</h2></center></td>
                    <td width="33%"><center>Official 2<br><h2>{{ $wst3->name }}</h2></center></td>
                </tr>
                <tr>
                    <td width="34%"><center><img width="34%" class="responsive" src="{{ url('storage/'.$foto1->path) }}"></center></td>
                    <td width="33%"><center><img width="33%" class="responsive" src="{{ url('storage/'.$foto2->path) }}"></center></td>
                    <td width="33%"><center><img width="33%" class="responsive" src="{{ url('storage/'.$foto3->path) }}"></center></td>
                </tr>
            </table>

            <section class="card bg-primary mb-0" style="border-radius: 0">
                <div class="card-header">
                    <h4 class="card-title" style="color: white;">Calls Summary</h4>
                </div>
            </section>
            <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                <tr>
                    <td><center><b>Periodes</b></center></td>
                    <td colspan="2"><center><b>1st Period</b></center></td>
                    <td colspan="2"><center><b>2nd Period</b></center></td>
                    <td colspan="2"><center><b>3rd Period</b></center></td>
                    <td colspan="2"><center><b>4th Period</b></center></td>
                    <td rowspan="2" colspan="2" style="vertical-align : middle;text-align:center;"><center><b>Total</b></center></td>
                </tr>
                <tr>
                    <td><center><b>Time</b></center></td>
                    @for($i = 0;$i < 4;$i++)
                        <td><center><b>5'</b></center></td>
                        <td><center><b>10'</b></center></td>
                    @endfor
                </tr>

                {{-- Summary Calls --}}
                <tr>
                    <td rowspan="2" style="vertical-align : middle;text-align:center;"><center><b>Calls</b></center></td>
                    @foreach($summary['summary'] as $item)
                        <td><center>{{ $item['firstcall'] }} ({{ number_format($item['firstPercent'], 0) }}%)</center></td>
                        <td><center>{{ $item['secondcall'] }} ({{ number_format($item['secondPercent'], 0) }}%)</center></td>
                    @endforeach
                    <td><center>{{ $summary['tSummary']['first'] }} ({{ number_format($summary['tSummary']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $summary['tSummary']['second'] }} ({{ number_format($summary['tSummary']['secondPercent'], 0) }}%)</center></td>
                </tr>
                <tr>
                    @foreach($summary['summary'] as $item)
                        <td colspan="2"><center>{{ $item['total'] }}  ({{ number_format($item['totalPercent'], 0) }}%)</center></td>
                    @endforeach
                    <td colspan="2"><center>{{ $summary['tSummary']['total'] }} (100%)</center></td>
                </tr>

                {{-- Summary Fouls --}}
                <tr>
                    <td rowspan="2" style="vertical-align : middle;text-align:center;"><center><b>Fouls</b></center></td>
                    @foreach($summary['foulSummary']['fouls'] as $item)
                        <td><center>{{ $item['firstcall'] }} ({{ number_format($item['firstPercent'], 0) }}%)</center></td>
                        <td><center>{{ $item['secondcall'] }} ({{ number_format($item['secondPercent'], 0) }}%)</center></td>
                    @endforeach
                    <td><center>{{ $summary['foulTotalSummary']['fouls']['first'] }} ({{ number_format($summary['foulTotalSummary']['fouls']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $summary['foulTotalSummary']['fouls']['second'] }} ({{ number_format($summary['foulTotalSummary']['fouls']['secondPercent'], 0) }}%)</center></td>
                </tr>
                <tr>
                    @foreach($summary['foulSummary']['fouls'] as $item)
                        <td colspan="2"><center>{{ $item['total'] }}  ({{ number_format($item['totalPercent'], 0) }}%)</center></td>
                    @endforeach
                    <td colspan="2"><center>{{ $summary['foulTotalSummary']['fouls']['total'] }} ({{ number_format($summary['foulTotalSummary']['fouls']['summaryPercent'], 0) }}%)</center></td>
                </tr>

                {{-- Summary IRS --}}
                <tr>
                    <td rowspan="2" style="vertical-align : middle;text-align:center;"><center><b>IRS</b></center></td>
                    @foreach($summary['foulSummary']['IRS'] as $item)
                        <td><center>{{ $item['firstcall'] }} ({{ number_format($item['firstPercent'], 0) }}%)</center></td>
                        <td><center>{{ $item['secondcall'] }} ({{ number_format($item['secondPercent'], 0) }}%)</center></td>
                    @endforeach
                    <td><center>{{ $summary['foulTotalSummary']['IRS']['first'] }} ({{ number_format($summary['foulTotalSummary']['IRS']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $summary['foulTotalSummary']['IRS']['second'] }} ({{ number_format($summary['foulTotalSummary']['IRS']['secondPercent'], 0) }}%)</center></td>
                </tr>
                <tr>
                    @foreach($summary['foulSummary']['IRS'] as $item)
                        <td colspan="2"><center>{{ $item['total'] }}  ({{ number_format($item['totalPercent'], 0) }}%)</center></td>
                    @endforeach
                    <td colspan="2"><center>{{ $summary['foulTotalSummary']['IRS']['total'] }} ({{ number_format($summary['foulTotalSummary']['IRS']['summaryPercent'], 0) }}%)</center></td>
                </tr>

                {{-- Summary IRS --}}
                <tr>
                    <td rowspan="2" style="vertical-align : middle;text-align:center;"><center><b>Travelling</b></center></td>
                    @foreach($summary['foulSummary']['Travelling'] as $item)
                        <td><center>{{ $item['firstcall'] }} ({{ number_format($item['firstPercent'], 0) }}%)</center></td>
                        <td><center>{{ $item['secondcall'] }} ({{ number_format($item['secondPercent'], 0) }}%)</center></td>
                    @endforeach
                    <td><center>{{ $summary['foulTotalSummary']['Travelling']['first'] }} ({{ number_format($summary['foulTotalSummary']['Travelling']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $summary['foulTotalSummary']['Travelling']['second'] }} ({{ number_format($summary['foulTotalSummary']['Travelling']['secondPercent'], 0) }}%)</center></td>
                </tr>
                <tr>
                    @foreach($summary['foulSummary']['Travelling'] as $item)
                        <td colspan="2"><center>{{ $item['total'] }}  ({{ number_format($item['totalPercent'], 0) }}%)</center></td>
                    @endforeach
                    <td colspan="2"><center>{{ $summary['foulTotalSummary']['Travelling']['total'] }} ({{ number_format($summary['foulTotalSummary']['Travelling']['summaryPercent'], 0) }}%)</center></td>
                </tr>

                {{--Other Violation --}}
                <tr>
                    <td rowspan="2" style="vertical-align : middle;text-align:center;"><center><b>Other Violations</b></center></td>
                    @foreach($summary['foulSummary']['Other Violations'] as $item)
                        <td><center>{{ $item['firstcall'] }} ({{ number_format($item['firstPercent'], 0) }}%)</center></td>
                        <td><center>{{ $item['secondcall'] }} ({{ number_format($item['secondPercent'], 0) }}%)</center></td>
                    @endforeach
                    <td><center>{{ $summary['foulTotalSummary']['Other Violations']['first'] }} ({{ number_format($summary['foulTotalSummary']['Other Violations']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $summary['foulTotalSummary']['Other Violations']['second'] }} ({{ number_format($summary['foulTotalSummary']['Other Violations']['secondPercent'], 0) }}%)</center></td>
                </tr>
                <tr>
                    @foreach($summary['foulSummary']['Other Violations'] as $item)
                        <td colspan="2"><center>{{ $item['total'] }}  ({{ number_format($item['totalPercent'], 0) }}%)</center></td>
                    @endforeach
                    <td colspan="2"><center>{{ $summary['foulTotalSummary']['Other Violations']['total'] }} ({{ number_format($summary['foulTotalSummary']['Other Violations']['summaryPercent'], 0) }}%)</center></td>
                </tr>
            </table>

            <section class="card bg-primary mb-0" style="border-radius: 0">
                <div class="card-header">
                    <h4 class="card-title" style="color: white;">Calls per Referee</h4>
                </div>
            </section>

            <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                <tr>
                    <td><center><b>Periodes</b></center></td>
                    <td colspan="2"><center><b>1st Period</b></center></td>
                    <td colspan="2"><center><b>2nd Period</b></center></td>
                    <td colspan="2"><center><b>3rd Period</b></center></td>
                    <td colspan="2"><center><b>4th Period</b></center></td>
                    <td rowspan="2" colspan="2" style="vertical-align : middle;text-align:center;"><center><b>Total</b></center></td>
                </tr>
                <tr>
                    <td><center><b>Time</b></center></td>
                    @for($i = 0;$i < 4;$i++)
                        <td><center><b>5'</b></center></td>
                        <td><center><b>10'</b></center></td>
                    @endfor
                </tr>

                <?php $i = 0; ?>
                @foreach ($summary['referee'] as $item)
                    <tr>
                        <td rowspan="2" style="vertical-align : middle;text-align:center;"><center><b>{{ $item['name'] }}</b></center></td>
                        @for($j = 1;$j < 5;$j++)
                            <td><center>{{ $item[$j]['firstcall'] }} ({{ number_format($item[$j]['firstPercent'], 0) }}%)</center></td>
                            <td><center>{{ $item[$j]['secondcall'] }} ({{ number_format($item[$j]['secondPercent'], 0) }}%)</center></td>
                        @endfor
                        <td><center>{{ $summary['refereeTotal'][$i]['first'] }} ({{ number_format($summary['refereeTotal'][$i]['firstPercent'], 0) }}%)</center></td>
                        <td><center>{{ $summary['refereeTotal'][$i]['second'] }} ({{ number_format($summary['refereeTotal'][$i]['secondPercent'], 0) }}%)</center></td>
                    </tr>
                    <tr>
                        @for($j = 1;$j < 5;$j++)
                            <td colspan="2"><center>{{ $item[$j]['total'] }}  ({{ number_format($item[$j]['totalPercent'], 0) }}%)</center></td>
                            @endfor
                        <td colspan="2"><center>{{ $summary['refereeTotal'][$i]['total'] }} ({{ number_format($summary['refereeTotal'][$i]['summaryPercent'], 0) }}%)</center></td>
                    </tr>
                    <?php $i++ ?>
                @endforeach
                <tr>
                    <td><center><b>Total</b></center></td>
                    <td colspan="8"></td>
                    <td colspan="2"><center>{{ $summary['tSummary']['total'] }} (100%)</center></td>
                </tr>
            </table>
        </div>
    </div>

</x-base-layout>