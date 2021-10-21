<x-base-layout>

    <?php $title = $model->name ?>

    <style>
        .responsive {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
    </style>
    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('t-approval.index') }}" class="pe-3">User Approval</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="row">
        <div class="col-md-2">
            <div class="card shadow-sm">
                <div class="card-body">
                    <center>
                        <img class="responsive" src="{{ url('storage/'.$foto->path) }}">

                        @if($model->status == 0)
                            <div class="alert alert-dismissible bg-primary p-3 mt-3">
                                <div class="d-flex flex-column text-light">
                                    <center><span style="font-weight:bold;">Waiting Approval</span></center>
                                </div>
                            </div>
                        @elseif($model->status == 1)
                            <div class="alert alert-dismissible bg-success p-3 mt-3">
                                <div class="d-flex flex-column text-light">
                                    <center><span style="font-weight:bold;">Approved</span></center>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-dismissible bg-danger p-3 mt-3">
                                <div class="d-flex flex-column text-light">
                                    <center><span style="font-weight:bold;">Rejected</span></center>
                                </div>
                            </div>
                        @endif


                    </center>
                </div>
            </div>

            @if ($model->status == 0)
            <center>
                <button id="approve" data-id="{{ $model->id }}" class="btn btn-success mt-2 p-3" style="width:100%" onClick="approve(event)">Approve</button>
                <button id="reject" data-id="{{ $model->id }}" class="btn btn-danger mt-2 p-3" style="width:100%" onClick="reject(event)">Reject</button>
            </center>
            @endif
        </div>

        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="alert alert-dismissible bg-primary p-3" style="margin-bottom:0px; border-radius: 0px;">
                        <div class="d-flex flex-column text-light">
                            <span style="font-weight:bold;">Akun</span>
                        </div>
                    </div>
                    <table class="table table bordered table-striped">
                        <tr>
                            <td class="p-5" width="15%">Username</td>
                            <td class="p-5" width="1%">:</td>
                            <td class="p-5" >{{ $model->username }}</td>
                        </tr>
                        <tr>
                            <td class="p-5" >Email</td>
                            <td class="p-5" >:</td>
                            <td class="p-5" >{{$model->email}}</td>
                        </tr>
                    </table>

                    <div class="alert alert-dismissible bg-primary p-3" style="margin-bottom:0px; border-radius: 0px;">
                        <div class="d-flex flex-column text-light">
                            <span style="font-weight:bold;">Data Diri</span>
                        </div>
                    </div>
                    <table class="table table bordered table-striped">
                        <tr>
                            <td class="p-5" width="15%">Nama</td>
                            <td class="p-5" width="1%">:</td>
                            <td class="p-5" >{{ $model->name }}</td>
                        </tr>
                        <tr>
                            <td class="p-5" width="15%">Jenis Daftar</td>
                            <td class="p-5" width="1%">:</td>
                            <td class="p-5" >{{ $role->name }}</td>
                        </tr>
                        <tr>
                            <td class="p-5" >Nomor Lisensi</td>
                            <td class="p-5" >:</td>
                            <td class="p-5" >{{$model->no_lisensi}}</td>
                        </tr>
                        <tr>
                            <td class="p-5" >Jenis Lisensi</td>
                            <td class="p-5" >:</td>
                            <td class="p-5" >{{$lisensi->license}}</td>
                        </tr>
                        <tr>
                            <td class="p-5" >Tempat Lahir</td>
                            <td class="p-5" >:</td>
                            <td class="p-5" >{{$model->tempat_lahir}}</td>
                        </tr>
                        <tr>
                            <td class="p-5" >Tempat Lahir</td>
                            <td class="p-5" >:</td>
                            <td class="p-5" >{{date('d-m-Y', strtotime($model->tanggal_lahir)) }}</td>
                        </tr>
                        <tr>
                            <td class="p-5" >Alamat</td>
                            <td class="p-5" >:</td>
                            <td class="p-5" >{{ $model->alamat }}</td>
                        </tr>
                        <tr>
                            <td class="p-5" >Provinsi</td>
                            <td class="p-5" >:</td>
                            <td class="p-5" >{{ $provinsi->region }}</td>
                        </tr>
                        <tr>
                            <td class="p-5" >Tanggal Pendaftaran</td>
                            <td class="p-5" >:</td>
                            <td class="p-5" >{{ date('H:i:s / d-m-Y', strtotime($model->createdon)) }}</td>
                        </tr>
                    </table>

                    @if($model->status == 1)
                        <div class="alert alert-dismissible bg-primary p-3" style="margin-bottom:0px; border-radius: 0px;">
                            <div class="d-flex flex-column text-light">
                                <span style="font-weight:bold;">Status Approval</span>
                            </div>
                        </div>
                        <?php $approve = \App\Models\User::find($model->tindakan) ?>
                        <table class="table table bordered table-striped">
                            <tr>
                                <td class="p-5" width="15%">Di Approve Oleh</td>
                                <td class="p-5" width="1%">:</td>
                                <td class="p-5" >{{ $approve->name }}</td>
                            </tr>
                            <tr>
                                <td class="p-5" width="15%">Tanggal Approve</td>
                                <td class="p-5" width="1%">:</td>
                                <td class="p-5" >{{ date('H:i:s / d-m-Y', strtotime($model->tanggal_tindakan)) }}</td>
                            </tr>
                        </table>
                    @elseif($model->status == -1)
                        <div class="alert alert-dismissible bg-primary p-3" style="margin-bottom:0px; border-radius: 0px;">
                            <div class="d-flex flex-column text-light">
                                <span style="font-weight:bold;">Status Approval</span>
                            </div>
                        </div>
                        <?php $approve = \App\Models\User::find($model->tindakan) ?>
                        <table class="table table bordered table-striped">
                            <tr>
                                <td class="p-5" width="15%">Di Reject Oleh</td>
                                <td class="p-5" width="1%">:</td>
                                <td class="p-5" >{{ $approve->name }}</td>
                            </tr>
                            <tr>
                                <td class="p-5" width="15%">Tanggal Reject</td>
                                <td class="p-5" width="1%">:</td>
                                <td class="p-5" >{{ $model->tanggal_tindakan }}</td>
                            </tr>
                        </table>
                    @endif

                    <div class="alert alert-dismissible bg-primary p-3" style="margin-bottom:0px; border-radius: 0px;">
                        <div class="d-flex flex-column text-light">
                            <span style="font-weight:bold;">Berkas</span>
                        </div>
                    </div>
                    <table class="table table bordered table-striped">
                        <tr>
                            <td class="p-5" width="15%">Lisensi</td>
                            <td class="p-5" width="1%">:</td>
                            <td class="p-5" ><a href="{{ route('t-approval.download-lisensi', $model->id_t_file_lisensi) }}"> Download </a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            function approve(event) {
                event.preventDefault();
                var id = $('#approve').data("id");
                var token  = $("meta[name='csrf-token']").attr("content");

                warningMessage = 'Apakah anda akan approve user ini?';
                buttonName = "Approve";

                Swal.fire({
                    title: "Approval",
                    text: warningMessage,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: buttonName
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: '/t-approval/approve',
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
                                            window.location.replace("/t-approval/show/" + id);
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
                                            window.location.replace("/t-approval/show/" + id);
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            }

            function reject(event) {
                event.preventDefault();
                var id = $('#reject').data("id");
                var token  = $("meta[name='csrf-token']").attr("content");

                warningMessage = 'Apakah anda akan reject user ini?';
                buttonName = "Reject";

                Swal.fire({
                    title: "Reject",
                    text: warningMessage,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: buttonName
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: '/t-approval/reject',
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
                                            window.location.replace("/t-approval/show/" + id);
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
                                            window.location.replace("/t-approval/show/" + id);
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