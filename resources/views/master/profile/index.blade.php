<x-base-layout>
    <link href="{{asset('demo1/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>

    <style>
        .responsive {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
    </style>

    <?php $title = 'Profile' ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-2">
                    <img class="responsive" src="{{ url('storage/'.$foto->path) }}">
                </div>

                <div class="col-10">
                    <h2>{{ $user->name }}</h2>
                    <h5 class="text-gray-600" style="margin-top:-10px; margin-bottom:10px;">{{ $user->email }}</h5>
                    <span class='w-130px badge badge-primary'>{{ $provinsi->region }}</span>
                    @if(!empty($rank)) <span class='w-130px badge badge-primary'>Rank Referee : {{ $rank }}</span> @endif
                </div>
            </div>
        </div>
    </div>

    <br>

    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#profile">Profile</a>
        </li>
        @if (in_array(8, $listRole))
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#match">Pertandingan</a>
            </li>
        @endif
    </ul>
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="tab-content" id="tab">
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="page_1"> @include('master.profile.general') </div>
                @if (in_array(8, $listRole)) <div class="tab-pane fade" id="match" role="tabpanel" aria-labelledby="page_2"> @include('master.profile.match') </div> @endif
            </div>

        </div>
    </div>

    @section('scripts')
        <script src="{{asset('demo1/js/master/profile/index-match.js')}}"></script>
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

            $("body").on("click", ".deleteLisensi", function () {
                var id = $(this).data("id");
                var token  = $("meta[name='csrf-token']").attr("content");

                Swal.fire({
                    title: "Apakah anda menghapus lisensi ini?",
                    text: "Data yang sudah dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, hapus lisensi!"
                }).then(function (result) {
                    if (result.value) {
                        loadingScreen('Mohon Tunggu ...');
                        $.ajax({
                            url: '/profile/delete-lisensi',
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
                                            window.location.href = "{{ url('/profile/index/' . $user->id) }}";
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
                                            window.location.href = "{{ url('/profile/index/' . $user->id) }}";
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