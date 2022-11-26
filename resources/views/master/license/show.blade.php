<x-base-layout>
    <style>
        tr td {
            border: 1px solid #ddd !important;
        }
    </style>

    <?php $title = $model->license ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('license.index') }}" class="pe-3">Lisensi</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#181C32;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">

            @if((in_array('1', $role)) || (in_array('2', $role)))
                <a href="{{ route('license.edit', $model->id) }}" class="btn btn-warning"> Edit </a>
                @if($model->status == 1)
                    <button class="btn btn-danger" id="switchStatus" data-toogle="inactive" data-id="{{ $model->id }}" onClick="aktif(event)"> Inactive </button>
                @else
                    <button class="btn btn-success" id="switchStatus" data-toogle="active" data-id="{{ $model->id }}" onClick="aktif(event)"> Active </button>
                @endif
            @endif
            <button class="btn btn-danger" id="delete" data-id="{{ $model->id }}" onClick="hapus(event)"> Delete </button>
            <a href="{{ route('license.index') }}" class="btn btn-secondary"> Kembali </a>

            <br><br>

            <section class="card bg-primary mb-0" style="border-radius: 0">
                <div class="card-header">
                    <h4 class="card-title" style="color: white;">General</h4>
                </div>
            </section>
            <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                <tr>
                    <td width="25%">Lisensi</td>
                    <td>{{ $model->license }}</td>
                </tr>
                <tr>
                    <td width="25%">Jenis Lisensi</td>
                    <td>
                        @if ($model->type == 1)
                            Wasit
                        @elseif ($model->type == 2)
                            Pengawas Pertandingan
                        @else
                            -
                        @endif</td>
                </tr>
                <tr>
                    <td width="25%">Keterangan</td>
                    <td>{{ ($model->keterangan) ? $model->keterangan : "-" }}</td>
                </tr>
                <tr>
                    <td width="25%">Status</td>
                    <td>
                        @if($model->status == 1)
                            <span class='w-130px badge badge-success me-4'> Active </span>
                        @elseif($model->status == 0)
                            <span class='w-130px badge badge-warning me-4'> Inactive </span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </table>

            <section class="card bg-primary mt-0 mb-0" style="border-radius: 0">
                <div class="card-header">
                    <h4 class="card-title" style="color: white;">System</h4>
                </div>
            </section>
            <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                <tr>
                    <td width="25%">Dibuat Oleh</td>
                    <td>{{ \App\Models\User::find($model->createdby)->name }}</td>
                </tr>
                <tr>
                    <td width="25%">Waktu Dibuat</td>
                    <td>{{ date("H:i:s d-m-Y", strtotime($model->createdon)) }}</td>
                </tr>
                <tr>
                    <td width="25%">Diubah Oleh</td>
                    <td>{{ \App\Models\User::find($model->modifiedby)->name }}</td>
                </tr>
                <tr>
                    <td width="25%">Waktu Diubah</td>
                    <td>{{ date("H:i:s d-m-Y", strtotime($model->modifiedon)) }}</td>
                </tr>
            </table>

        </div>
    </div>

    @section('scripts')
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

            function aktif(event) {
                event.preventDefault();
                var id = $('#switchStatus').data("id");
                var toogle = $('#switchStatus').data("toogle");
                var token  = $("meta[name='csrf-token']").attr("content");

                if (toogle == 'inactive') {
                    warningMessage = 'Apakah anda akan menonaktifkan data ini?';
                    buttonName = "Non-Aktifkan";
                } else {
                    warningMessage = 'Apakah anda akan mengaktifkan data ini?';
                    buttonName = "Aktifkan";
                }

                Swal.fire({
                    title: "Change Data Status",
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
                            url: '/license/status',
                            type: 'POST',
                            data: {
                                _token: token,
                                id: id
                            },
                            success: function (response) {
                                $.unblockUI();
                                if (response.status == 200) {
                                    Swal.fire({
                                        icon: "success",
                                        title: response.header,
                                        text: response.message,
                                        confirmButtonClass: 'btn btn-success'
                                    }).then(function (result) {
                                        if (result.value) {
                                            window.location.replace("/license/show/" + id);
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: "warning",
                                        title: response.header,
                                        text: response.message,
                                        confirmButtonClass: 'btn btn-success'
                                    }).then(function (result) {
                                        if (result.value) {
                                            window.location.replace("/license/show/" + id);
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            }

            function hapus(event) {
                event.preventDefault();
                var id = $('#delete').data("id");
                var token  = $("meta[name='csrf-token']").attr("content");

                Swal.fire({
                    title: "Delete Data",
                    text: 'Apakah anda akan menghapus data ini?',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Hapus"
                }).then(function (result) {
                    if (result.value) {
                        loadingScreen('Mohon Tunggu ...');
                        $.ajax({
                            url: '/license/delete',
                            type: 'POST',
                            data: {
                                _token: token,
                                id: id
                            },
                            success: function (response) {
                                $.unblockUI();
                                if (response.status == 200) {
                                    Swal.fire({
                                        icon: "success",
                                        title: response.header,
                                        text: response.message,
                                        confirmButtonClass: 'btn btn-success'
                                    }).then(function (result) {
                                        if (result.value) {
                                            window.location.replace("/license/index");
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: "warning",
                                        title: response.header,
                                        text: response.message,
                                        confirmButtonClass: 'btn btn-success'
                                    }).then(function (result) {
                                        if (result.value) {
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            }

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