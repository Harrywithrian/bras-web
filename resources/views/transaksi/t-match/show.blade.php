<x-base-layout>
    <style>
        tr td {
            border: 1px solid #ddd !important;
        }
    </style>

    <?php $title = 'Pertandingan ' . $model->nama ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"> <i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home </a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.index-event') }}" class="pe-3"> List Event </a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.index', $event->id) }}" class="pe-3"> Pertandingan Event {{ $event->nama }} </a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>


    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">

            <a href="{{ route('t-match.index', $event->id) }}" class="btn btn-secondary"> Kembali </a>

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
                <tr>
                    <td width="34%"><center><a href="#" class="btn btn-primary" style="width:60%"> Game Management </a></center></td>
                    <td width="33%"><center><a href="#" class="btn btn-primary" style="width:60%"> Game Management </a></center></td>
                    <td width="33%"><center><a href="#" class="btn btn-primary" style="width:60%"> Game Management </a></center></td>
                </tr>
                <tr>
                    <td width="34%"><center><a href="#" class="btn btn-success" style="width:60%"> Mechanical Court </a></center></td>
                    <td width="33%"><center><a href="#" class="btn btn-success" style="width:60%"> Mechanical Court </a></center></td>
                    <td width="33%"><center><a href="#" class="btn btn-success" style="width:60%"> Mechanical Court </a></center></td>
                </tr>
                <tr>
                    <td width="34%"><center><a href="#" class="btn btn-info" style="width:60%"> Appearance </a></center></td>
                    <td width="33%"><center><a href="#" class="btn btn-info" style="width:60%"> Appearance </a></center></td>
                    <td width="33%"><center><a href="#" class="btn btn-info" style="width:60%"> Appearance </a></center></td>
                </tr>
            </table>
        </div>
    </div>

    @section('scripts')
        <script>
            $(document).ready( function() {
                @if(\Illuminate\Support\Facades\Session::has('success'))
                    var msg = JSON.parse('<?php echo json_encode(\Illuminate\Support\Facades\Session::get('success')); ?>');
                    toastr['success'](msg, 'Success', {
                        closeButton: true,
                        tapToDismiss: false,
                        rtl: false
                    });
                @endif

                @if(\Illuminate\Support\Facades\Session::has('error'))
                    var msg = JSON.parse('<?php echo json_encode(\Illuminate\Support\Facades\Session::get('error')); ?>');
                    toastr['error'](msg, 'Error', {
                        closeButton: true,
                        tapToDismiss: false,
                        rtl: false
                    });
                @endif
            });
        </script>
    @endsection

</x-base-layout>