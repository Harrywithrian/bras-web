<x-base-layout>
    <?php
        $title = 'Pertandingan Event ' . $event->nama;
        $user = \App\Models\UserInfo::where('user_id', '=', \Illuminate\Support\Facades\Auth::id())->first();
        $dateNow = date('Y-m-d');
    ?>

    <link href="{{asset('demo1/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"> <i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home </a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.index-event') }}" class="pe-3"> List Event </a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm" id="main-layout">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light">{{ $title }}</h3>
        </div>

        <div class="card-body">

            @include('transaksi.t-match.search')

            <div class="row">
                <div class="col-12">
                    <div class="float-end mb-5">
                        <a class="btn btn-xs btn-secondary" href="{{ route('t-match.index-event') }}"> Kembali </a>
                        @if($user->role == 7 && $event->status != 2)
                            <a class="btn btn-xs btn-success" href="{{ route('t-match.done-event', $event->id) }}"> Event Selesai </a>
                        @endif
                        @if($user->role == 7 && $dateNow < $event->tanggal_selesai && $event->status != 2)
                            <a class="btn btn-xs btn-primary" href="{{ route('t-match.create', $event->id) }}"> Tambah Pertandingan </a>
                        @endif
                    </div>
                </div>
            </div>

            <input id="id_event" type="hidden" value="{{$event->id}}">

            <div class="row">
                <div class="col-12">
                    <div id="main-table">
                        <table id="content-table" class="table table-hover table-rounded table-row-bordered border gy-5 gs-5" style="width:100%;">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Status</th>
                                <th>Nama</th>
                                <th>Lokasi</th>
                                <th>Waktu Pertandingan</th>
                                <th>Tanggal Pertandingan</th>
                                <th>Event</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @section('scripts')
        <script src="{{asset('demo1/js/transaksi/t-match/index.js')}}"></script>
        <script src="{{asset('demo1/plugins/custom/datatables/datatables.bundle.js')}}"></script>
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