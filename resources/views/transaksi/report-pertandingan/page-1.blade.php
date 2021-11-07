<a href="#" class="btn btn-primary"> Cetak </a>
<a href="{{ route('report-pertandingan.index', $event->id) }}" class="btn btn-secondary"> Kembali </a>

<br><br>

<section class="card bg-primary mb-0" style="border-radius: 0">
    <div class="card-header">
        <h4 class="card-title" style="color: white;">General</h4>
    </div>
</section>
<table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
    <tr>
        <td width="25%">Nama</td>
        <td>{{ $model->nama }}</td>
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
        <td>{{ date('H:i', strtotime($model->waktu_pertandingan)) }}</td>
    </tr>
    <tr>
        <td width="25%">Tanggal Pertandingan</td>
        <td>{{ date('d-m-Y', strtotime($model->waktu_pertandingan)) }}</td>
    </tr>
    <tr>
        <td width="25%">Status</td>
        <td>
            @if($model->status == 0)
                <span class='rounded-pill bg-info' style="padding:5px; color: white"> Belum Mulai </span>
            @elseif($model->status == 1)
                <span class='rounded-pill bg-primary' style="padding:5px; color: white"> Berlangsung </span>
            @elseif($model->status == 2)
                <span class='rounded-pill bg-success' style="padding:5px; color: white"> Selesai </span>
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