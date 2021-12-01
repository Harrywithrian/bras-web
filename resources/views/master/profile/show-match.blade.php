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

            <a href="{{ route('profile.index', $id) }}" class="btn btn-secondary"> Kembali </a>

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
                    <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>Calls</center></td>
                    <td><center>{{ $playCalling['q1']['first'] }} ({{ number_format($playCalling['q1']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $playCalling['q1']['second'] }} ({{ number_format($playCalling['q1']['secondPercent'], 0) }}%)</center></td>
                    <td><center>{{ $playCalling['q2']['first'] }} ({{ number_format($playCalling['q2']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $playCalling['q2']['second'] }} ({{ number_format($playCalling['q2']['secondPercent'], 0) }}%)</center></td>
                    <td><center>{{ $playCalling['q3']['first'] }} ({{ number_format($playCalling['q3']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $playCalling['q3']['second'] }} ({{ number_format($playCalling['q3']['secondPercent'], 0) }}%)</center></td>
                    <td><center>{{ $playCalling['q4']['first'] }} ({{ number_format($playCalling['q4']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $playCalling['q4']['second'] }} ({{ number_format($playCalling['q4']['secondPercent'], 0) }}%)</center></td>
                    <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>{{ $playCalling['total'] }} (100%)</center></td>
                </tr>
                <tr>
                    <td colspan="2"><center>{{ $playCalling['q1']['total'] }} ({{ number_format($playCalling['q1Percent'], 0) }}%)</center></td>
                    <td colspan="2"><center>{{ $playCalling['q2']['total'] }} ({{ number_format($playCalling['q2Percent'], 0) }}%)</center></td>
                    <td colspan="2"><center>{{ $playCalling['q3']['total'] }} ({{ number_format($playCalling['q3Percent'], 0) }}%)</center></td>
                    <td colspan="2"><center>{{ $playCalling['q4']['total'] }} ({{ number_format($playCalling['q4Percent'], 0) }}%)</center></td>
                </tr>
            </table>

            <section class="card bg-primary mb-0" style="border-radius: 0">
                <div class="card-header">
                    <h4 class="card-title" style="color: white;">Calls per Referee</h4>
                </div>
            </section>
            <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
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
                    <td><center>{{ $callReferee['wst1']['q1']['first'] }} ({{ number_format($callReferee['wst1']['q1']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst1']['q1']['second'] }} ({{ number_format($callReferee['wst1']['q1']['secondPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst1']['q2']['first'] }} ({{ number_format($callReferee['wst1']['q2']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst1']['q2']['second'] }} ({{ number_format($callReferee['wst1']['q2']['secondPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst1']['q3']['first'] }} ({{ number_format($callReferee['wst1']['q3']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst1']['q3']['second'] }} ({{ number_format($callReferee['wst1']['q3']['secondPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst1']['q4']['first'] }} ({{ number_format($callReferee['wst1']['q4']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst1']['q4']['second'] }} ({{ number_format($callReferee['wst1']['q4']['secondPercent'], 0) }}%)</center></td>
                    <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>{{ $callReferee['wst1']['total'] }} ({{ number_format($callReferee['wst1']['totalPercent'], 0) }}%)</center></td>
                </tr>
                <tr>
                    <td colspan="2"><center>{{ $callReferee['wst1']['q1']['total'] }} ({{ number_format($callReferee['wst1']['q1Percent'], 0) }}%)</center></td>
                    <td colspan="2"><center>{{ $callReferee['wst1']['q2']['total'] }} ({{ number_format($callReferee['wst1']['q2Percent'], 0) }}%)</center></td>
                    <td colspan="2"><center>{{ $callReferee['wst1']['q3']['total'] }} ({{ number_format($callReferee['wst1']['q3Percent'], 0) }}%)</center></td>
                    <td colspan="2"><center>{{ $callReferee['wst1']['q4']['total'] }} ({{ number_format($callReferee['wst1']['q4Percent'], 0) }}%)</center></td>
                </tr>
                <tr>
                    <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>{{ $wst2->name }}</center></td>
                    <td><center>{{ $callReferee['wst2']['q1']['first'] }} ({{ number_format($callReferee['wst2']['q1']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst2']['q1']['second'] }} ({{ number_format($callReferee['wst2']['q1']['secondPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst2']['q2']['first'] }} ({{ number_format($callReferee['wst2']['q2']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst2']['q2']['second'] }} ({{ number_format($callReferee['wst2']['q2']['secondPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst2']['q3']['first'] }} ({{ number_format($callReferee['wst2']['q3']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst2']['q3']['second'] }} ({{ number_format($callReferee['wst2']['q3']['secondPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst2']['q4']['first'] }} ({{ number_format($callReferee['wst2']['q4']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst2']['q4']['second'] }} ({{ number_format($callReferee['wst2']['q4']['secondPercent'], 0) }}%)</center></td>
                    <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>{{ $callReferee['wst2']['total'] }} ({{ number_format($callReferee['wst2']['totalPercent'], 0) }}%)</center></td>
                </tr>
                <tr>
                    <td colspan="2"><center>{{ $callReferee['wst2']['q1']['total'] }} ({{ number_format($callReferee['wst2']['q1Percent'], 0) }}%)</center></td>
                    <td colspan="2"><center>{{ $callReferee['wst2']['q2']['total'] }} ({{ number_format($callReferee['wst2']['q2Percent'], 0) }}%)</center></td>
                    <td colspan="2"><center>{{ $callReferee['wst2']['q3']['total'] }} ({{ number_format($callReferee['wst2']['q3Percent'], 0) }}%)</center></td>
                    <td colspan="2"><center>{{ $callReferee['wst2']['q4']['total'] }} ({{ number_format($callReferee['wst2']['q4Percent'], 0) }}%)</center></td>
                </tr>
                <tr>
                    <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>{{ $wst3->name }}</center></td>
                    <td><center>{{ $callReferee['wst3']['q1']['first'] }} ({{ number_format($callReferee['wst3']['q1']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst3']['q1']['second'] }} ({{ number_format($callReferee['wst3']['q1']['secondPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst3']['q2']['first'] }} ({{ number_format($callReferee['wst3']['q2']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst3']['q2']['second'] }} ({{ number_format($callReferee['wst3']['q2']['secondPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst3']['q3']['first'] }} ({{ number_format($callReferee['wst3']['q3']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst3']['q3']['second'] }} ({{ number_format($callReferee['wst3']['q3']['secondPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst3']['q4']['first'] }} ({{ number_format($callReferee['wst3']['q4']['firstPercent'], 0) }}%)</center></td>
                    <td><center>{{ $callReferee['wst3']['q4']['second'] }} ({{ number_format($callReferee['wst3']['q4']['secondPercent'], 0) }}%)</center></td>
                    <td rowspan="2" style="vertical-align : middle;text-align:center;"><center>{{ $callReferee['wst3']['total'] }}  ({{ number_format($callReferee['wst3']['totalPercent'], 0) }}%)</center></td>
                </tr>
                <tr>
                    <td colspan="2"><center>{{ $callReferee['wst3']['q1']['total'] }} ({{ number_format($callReferee['wst3']['q1Percent'], 0) }}%)</center></td>
                    <td colspan="2"><center>{{ $callReferee['wst3']['q2']['total'] }} ({{ number_format($callReferee['wst3']['q2Percent'], 0) }}%)</center></td>
                    <td colspan="2"><center>{{ $callReferee['wst3']['q3']['total'] }} ({{ number_format($callReferee['wst3']['q3Percent'], 0) }}%)</center></td>
                    <td colspan="2"><center>{{ $callReferee['wst3']['q4']['total'] }} ({{ number_format($callReferee['wst3']['q4Percent'], 0) }}%)</center></td>
                </tr>
                <tr>
                    <td colspan="9"><center>Total</center></td>
                    <td><center>{{ $playCalling['total'] }} (100%)</center></td>
                </tr>
            </table>
        </div>
    </div>

</x-base-layout>