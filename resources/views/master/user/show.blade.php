<x-base-layout>
    <style>
        tr td {
            border: 1px solid #ddd !important;
        }

        .responsive {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
    </style>

    <?php $title = $model->username ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('m-user.index') }}" class="pe-3">User</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">

            @if ($model->id != 1)
                <a href="{{ route('m-user.edit', $model->id) }}" class="btn btn-warning"> Edit </a>
                @if($model->status == 1)
                    <button class="btn btn-danger" id="switchStatus" data-toogle="inactive" data-id="{{ $model->id }}" onClick="aktif(event)"> Inactive </button>
                    <button class="btn btn-danger" id="switchLock" data-toogle="lock" data-id="{{ $model->id }}" onClick="lock(event)"> Lock </button>
                @else
                    <button class="btn btn-success" id="switchStatus" data-toogle="active" data-id="{{ $model->id }}" onClick="aktif(event)"> Active </button>
                @endif
            @endif
            <a href="{{ route('m-user.index') }}" class="btn btn-secondary"> Kembali </a>

            <br><br>

            <div class="row">
                <div class="col-md-2">
                    <center>
                        <img class="responsive" src="{{ url('storage/'.$foto->path) }}">
                    </center>
                </div>

                <div class="col-md-10">
                    <section class="card bg-primary mb-0" style="border-radius: 0">
                        <div class="card-header">
                            <h4 class="card-title" style="color: white;">Account</h4>
                        </div>
                    </section>
                    <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                        <tr>
                            <td width="25%">Username</td>
                            <td>{{ $model->username }}</td>
                        </tr>
                        <tr>
                            <td width="25%">Email</td>
                            <td>{{ $model->email }}</td>
                        </tr>
                        <tr>
                            <td width="25%">Status</td>
                            <td>
                                @if ($model->status == 1)
                                    <span class='w-130px badge badge-success me-4'> Active </span>
                                @elseif ($model->status == 2)
                                    <span class='w-130px badge badge-warning me-4'> Locked </span>
                                @else
                                    <span class='w-130px badge badge-danger me-4'> Inactive </span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <section class="card bg-primary mt-0 mb-0" style="border-radius: 0">
                        <div class="card-header">
                            <h4 class="card-title" style="color: white;">General</h4>
                        </div>
                    </section>
                    <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                        <tr>
                            <td width="25%">Nama</td>
                            <td>{{ $model->name }}</td>
                        </tr>
                        <tr>
                            <td width="25%">Role</td>
                            <td>{{ $role->name }}</td>
                        </tr>
                        <tr>
                            <td width="25%">Tempat, Tanggal Lahir</td>
                            <td>{{ $detail->tempat_lahir }}, {{ date('d-m-Y', strtotime($detail->tanggal_lahir)) }}</td>
                        </tr>
                        <tr>
                            <td width="25%">Provinsi</td>
                            <td>{{ $provinsi->region }}</td>
                        </tr>
                        <tr>
                            <td width="25%">Alamat</td>
                            <td>{{ $detail->alamat }}</td>
                        </tr>
                    </table>

                    <section class="card bg-primary mt-0 mb-0" style="border-radius: 0">
                        <div class="card-header">
                            <h4 class="card-title" style="color: white;">System</h4>
                        </div>
                    </section>
                    <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                        <tr>
                            <td width="25%">Waktu Dibuat</td>
                            <td>{{ date("H:i:s d-m-Y", strtotime($model->created_at)) }}</td>
                        </tr>
                        <tr>
                            <td width="25%">Waktu Diubah</td>
                            <td>{{ date("H:i:s d-m-Y", strtotime($model->updated_at)) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

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
                        $.ajax({
                            url: '/m-user/status',
                            type: 'POST',
                            data: {
                                _token: token,
                                id: id
                            },
                            success: function (response) {
                                if (response.status == 200) {
                                    Swal.fire({
                                        icon: "success",
                                        title: response.header,
                                        text: response.message,
                                        confirmButtonClass: 'btn btn-success'
                                    }).then(function (result) {
                                        if (result.value) {
                                            window.location.replace("/m-user/show/" + id);
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
                                            window.location.replace("/m-user/show/" + id);
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            }

            function lock(event) {
                event.preventDefault();
                var id = $('#switchLock').data("id");
                var token  = $("meta[name='csrf-token']").attr("content");

                Swal.fire({
                    title: "Kunci Data",
                    text: 'Apakah anda akan mengunci data ini?',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Kunci"
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: '/m-user/lock',
                            type: 'POST',
                            data: {
                                _token: token,
                                id: id
                            },
                            success: function (response) {
                                if (response.status == 200) {
                                    Swal.fire({
                                        icon: "success",
                                        title: response.header,
                                        text: response.message,
                                        confirmButtonClass: 'btn btn-success'
                                    }).then(function (result) {
                                        if (result.value) {
                                            window.location.replace("/m-user/show/" + id);
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
        </script>
    @endsection

</x-base-layout>