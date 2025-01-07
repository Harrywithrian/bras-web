<x-base-layout>
    <?php
        $title = 'Pertandingan Event ' . $event->nama;
        // $user = \App\Models\UserInfo::where('user_id', '=', \Illuminate\Support\Facades\Auth::id())->first();
        $user = \App\Models\ModelHasRole::where('model_id', \Illuminate\Support\Facades\Auth::id())->where('role_id', 7)->first();
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
                        @if(!empty($user) && $event->status != 2)
                            <a class="btn btn-xs btn-success" href="{{ route('t-match.done-event', $event->id) }}"> Event Selesai </a>
                        @endif
                        {{-- @if(!empty($user) && $dateNow < $event->tanggal_selesai && $event->status != 2) --}}
                        @if(!empty($user) && $event->status != 2)
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.js"></script>
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

            $("body").on("click", ".deleted", function () {
                var id     = $(this).data("id");
                var table  = $('#content-table').DataTable();
                var token  = $("meta[name='csrf-token']").attr("content");

                warningMessage = 'Apakah anda akan menghapus data ini?';
                buttonName = "Delete";

                Swal.fire({
                    title: "Delete Data",
                    text: warningMessage,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: buttonName
                }).then(function (result) {
                    if (result.value) {
                        loadingScreen('Mohon Tunggu ...');
                        $.ajax({
                            url: '/t-match/delete',
                            type: 'POST',
                            data: {
                                _token: token,
                                id: id
                            },
                            success: function (response) {
                                if (response.status == 200) {
                                    $.unblockUI();
                                    Swal.fire({
                                        icon: "success",
                                        title: response.header,
                                        text: response.message,
                                        confirmButtonClass: 'btn btn-success'
                                    }).then(function (result) {
                                        if (result.value) {
                                            table.draw();
                                        }
                                    });
                                } else {
                                    $.unblockUI();
                                    Swal.fire({
                                        icon: "warning",
                                        title: response.header,
                                        text: response.message,
                                        confirmButtonClass: 'btn btn-success'
                                    }).then(function (result) {
                                        if (result.value) {
                                            table.draw();
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            });

            function loadingScreen(msg) {
                var $white = '#fff';
                var src = $("#logo_ibr").attr('src');
                src = src.replace("logo_dark", "logo");
                $.blockUI({
                    message: '<img src="' + src + '" style="height: 80px; width: auto"> <br><br> <h3>' + msg + '</h2>',
                    timeout: 5000, //unblock after 5 seconds
                    overlayCSS: {
                        backgroundColor: $white,
                        opacity: 0.8,
                        cursor: 'wait'
                    },
                    css: {
                        border: 0,
                        padding: 0,
                        backgroundColor: 'transparent'
                    }
                });
            }
        </script>
    @endsection

</x-base-layout>