<x-base-layout>
    <style>
        table tr td {
            border: 1px solid #ddd !important;
        }
    </style>

    <?php $title = $model->nama ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('t-event-approval.index') }}" class="pe-3">Event Approval</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">

            @if($model->status == 0)
                <button class="btn btn-success" id="approve" data-id="{{ $model->id }}" onClick="approve(event)"> Approve </button>
                <button class="btn btn-danger" id="reject" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="{{ $model->id }}"> Reject </button>
            @endif

            <a href="{{ route('t-event-approval.index') }}" class="btn btn-secondary"> Kembali </a>

            <section class="card bg-primary mb-0 mt-5" style="border-radius: 0">
                <div class="card-header">
                    <h4 class="card-title" style="color: white;">General</h4>
                </div>
            </section>
            <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                <tr>
                    <td width="25%">Nama Event</td>
                    <td>{{ $model->nama }}</td>
                </tr>
                <tr>
                    <td width="25%">Nomor Surat</td>
                    <td>{{ $model->no_lisensi }}</td>
                </tr>
                <tr>
                    <td width="25%">Deskripsi</td>
                    <td>{{ $model->deskripsi }}</td>
                </tr>
                <tr>
                    <td width="25%">Tanggal Event</td>
                    <td>{{ date('d/m/Y', strtotime($model->tanggal_mulai)) . " - " . date('d/m/Y', strtotime($model->tanggal_selesai)) }}</td>
                </tr>
                <tr>
                    <td width="25%">Status</td>
                    <td>
                        @if($model->status == 0)
                            <span class='w-130px badge badge-info me-4'> Waiting Approval </span>
                        @elseif($model->status == 1)
                            <span class='w-130px badge badge-success me-4'> Approved </span>
                        @elseif($model->status == -1)
                            <span class='w-130px badge badge-danger me-4'> Rejected </span>
                        @elseif ($model->status == 2)
                            <span class='w-130px badge badge-primary me-4'> Selesai </span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td width="25%">Prioritas Event</td>
                    <td>
                        @if($model->tipe == 0)
                            <span class='w-130px badge badge-success me-4'> Normal </span>
                        @elseif($model->tipe == 1)
                            <span class='w-130px badge badge-danger me-4'> Urgent </span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td width="25%">Komisi Teknik</td>
                    <td>{{ $model->getPenyelenggara->name }}</td>
                </tr>
                <tr>
                    <td width="25%">Tanggal Pengajuan</td>
                    <td>{{ date('d/m/Y', strtotime($model->createdon)) }}</td>
                </tr>
                @if ($model->status == 1)
                    <tr>
                        <td width="25%">Disetujui Oleh</td>
                        <td>{{ $model->getApproval->name }}</td>
                    </tr>
                    <tr>
                        <td width="25%">Tanggal Disetujui</td>
                        <td>{{ date('d/m/Y', strtotime($model->tanggal_tindakan)) }}</td>
                    </tr>
                @elseif($model->status == -1)
                    <tr>
                        <td width="25%">Ditolak Oleh</td>
                        <td>{{ $model->getApproval->name }}</td>
                    </tr>
                    <tr>
                        <td width="25%">Tanggal Ditolak</td>
                        <td>{{ date('d/m/Y', strtotime($model->tanggal_tindakan)) }}</td>
                    </tr>
                    <tr>
                        <td width="25%">Keterangan Ditolak</td>
                        <td>{{ $model->keterangan_tolak }}</td>
                    </tr>
                @endif
            </table>

            <?php $i = 1; ?>
            <section class="card bg-primary mt-0 mb-0" style="border-radius: 0">
                <div class="card-header">
                    <h4 class="card-title" style="color: white;">List Lokasi Pertandingan</h4>
                </div>
            </section>
            <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                <tr>
                    <td width="5%" style="padding-left:5px; padding-right:5px;"><center><b>No</b></center></td>
                    <td width="20%"><b>Nama Lokasi</b></td>
                    <td><b>Provinsi</b></td>
                    <td><b>Alamat</b></td>
                </tr>
                @foreach($location as $item)
                    <tr>
                        <td style="padding-left:5px; padding-right:5px;"><center>{{ $i }}</center></td>
                        <td>{{ $item['nama'] }}</td>
                        <td>{{ $item['region'] }}</td>
                        <td width="50%">{{ $item['alamat'] }}</td>
                    </tr>
                    <?php $i++; ?>
                @endforeach
            </table>

            <?php $i = 1; ?>
            <section class="card bg-primary mt-0 mb-0" style="border-radius: 0">
                <div class="card-header">
                    <h4 class="card-title" style="color: white;">List Provinsi Pertandingan</h4>
                </div>
            </section>
            <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                <tr>
                    <td width="5%" style="padding-left:5px; padding-right:5px;"><center><b>No</b></center></td>
                    <td width="20%"><b>Kode Provinsi</b></td>
                    <td><b>Provinsi</b></td>
                </tr>
                @foreach($region as $item)
                    <tr>
                        <td style="padding-left:5px; padding-right:5px;"><center>{{ $i }}</center></td>
                        <td>{{ $item['kode'] }}</td>
                        <td>{{ $item['region'] }}</td>
                    </tr>
                    <?php $i++; ?>
                @endforeach
            </table>

            <?php $i = 1; ?>
            <section class="card bg-primary mt-0 mb-0" style="border-radius: 0">
                <div class="card-header">
                    <h4 class="card-title" style="color: white;">List Participant</h4>
                </div>
            </section>
            <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                <tr>
                    <td width="5%" style="padding-left:5px; padding-right:5px;"><center><b>No</b></center></td>
                    <td width="20%"><b>Nama</b></td>
                    <td><b>Email</b></td>
                    <td><b>Lisensi</b></td>
                    <td><b>Nomor Lisensi</b></td>
                    <td><b>Provinsi</b></td>
                    <td><b>Status</b></td>
                </tr>
                @foreach($participant as $item)
                    <tr>
                        <td style="padding-left:5px; padding-right:5px;"><center>{{ $i }}</center></td>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['email'] }}</td>
                        <td>{{ $item['license'] }}</td>
                        <td>{{ $item['no_lisensi'] }}</td>
                        <td>{{ $item['region'] }}</td>
                        <td>@if($item['role'] == 6) Pengawas Pertandingan @elseif($item['role'] == 7) Koordinator Wasit @elseif($item['role'] == 8) Wasit @else - @endif</td>
                    </tr>
                    <?php $i++; ?>
                @endforeach
            </table>

            <?php $i = 1; ?>
            <section class="card bg-primary mt-0 mb-0" style="border-radius: 0">
                <div class="card-header">
                    <h4 class="card-title" style="color: white;">Tembusan</h4>
                </div>
            </section>
            <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                <tr>
                    <td width="5%" style="padding-left:5px; padding-right:5px;"><center><b>No</b></center></td>
                    <td width="50%"><b>Nama</b></td>
                    <td><b>Email</b></td>
                </tr>
                @foreach($tembusan as $item)
                    <tr>
                        <td style="padding-left:5px; padding-right:5px;"><center>{{ $i }}</center></td>
                        <td>{{ $item['nama'] }}</td>
                        <td>{{ $item['email'] }}</td>
                    </tr>
                    <?php $i++; ?>
                @endforeach
            </table>

            <?php $i = 1; ?>
            <section class="card bg-primary mt-0 mb-0" style="border-radius: 0">
                <div class="card-header">
                    <h4 class="card-title" style="color: white;">Contact Person</h4>
                </div>
            </section>
            <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                <tr>
                    <td width="5%" style="padding-left:5px; padding-right:5px;"><center><b>No</b></center></td>
                    <td width="50%"><b>Nama</b></td>
                    <td><b>Telepon</b></td>
                </tr>
                @foreach($cp as $item)
                    <tr>
                        <td style="padding-left:5px; padding-right:5px;"><center>{{ $i }}</center></td>
                        <td>{{ $item['nama'] }}</td>
                        <td>{{ $item['telepon'] }}</td>
                    </tr>
                    <?php $i++; ?>
                @endforeach
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tolak Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <label>Alasan Tolak</label>
                                <textarea id="tolak" class="form-control" name="tolak"></textarea>
                                @if($errors->has('tolak'))
                                    <span id="err_tolak" class="text-danger">{{ $errors->first('tolak') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button id="btnTolak" type="button" class="btn btn-danger" data-id="{{ $model->id }}" onClick="reject(event)">Tolak Event</button>
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

            warningMessage = 'Apakah anda akan setujui event ini?';
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
                        url: '/t-event-approval/approve',
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
                                        window.location.replace("/t-event-approval/show/" + id);
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
                                        window.location.replace("/t-event-approval/show/" + id);
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
            var id = $('#btnTolak').data("id");
            var keterangan = $('#tolak').val();
            var token  = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                url: '/t-event-approval/reject',
                type: 'POST',
                data: {
                    _token: token,
                    id: id,
                    ket: keterangan
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
                                window.location.replace("/t-event-approval/show/" + id);
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
                                window.location.replace("/t-event-approval/show/" + id);
                            }
                        });
                    }
                }
            });
        }
    </script>
    @endsection

</x-base-layout>