<x-base-layout>
    <style>
        table tr td {
            border: 1px solid #ddd !important;
        }
    </style>

    <?php $title = $model->nama ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('t-event-letter.index') }}" class="pe-3">Surat Tugas</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">

            <button class="btn btn-warning" id="reject" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="{{ $model->id }}"> Ubah Nomor Surat </button>
            <a href="{{ route('t-event-letter.send', $letter->id) }}" class="btn btn-primary"> Kirim Surat </a>
            <a href="{{ route('t-event-letter.dokumen', $letter->id) }}" class="btn btn-primary"> Preview Dokumen </a>
            <a href="{{ route('t-event-letter.index') }}" class="btn btn-secondary"> Kembali </a>

            <section class="card bg-primary mb-0 mt-5" style="border-radius: 0">
                <div class="card-header">
                    <h4 class="card-title" style="color: white;">Surat Tugas</h4>
                </div>
            </section>
            <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                <tr>
                    <td width="25%">Nomor Surat</td>
                    <td>{{ $letter->no_surat }}</td>
                </tr>
                <tr>
                    <td width="25%">Perihal</td>
                    <td>{{ $letter->perihal }}</td>
                </tr>
                <tr>
                    <td width="25%">Status</td>
                    <td>{!! ($letter->sent == 0) ? "<span class='w-130px badge badge-info me-4'> Belum Terkirim </span>" : "<span class='w-130px badge badge-success me-4'> Terkirim </span>" !!}</td>
                </tr>
                @if($letter->sent > 0)
                    <tr>
                        <td width="25%">Count Terkirim</td>
                        <td>{{ $letter->sent }}</td>
                    </tr>
                @endif
            </table>

            <section class="card bg-primary mb-0 mt-0" style="border-radius: 0">
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
                    <h5 class="modal-title" id="exampleModalLabel">Ubah Nomor Surat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <label>Nomor Surat</label>
                                <input id="no_surat" class="form-control" name="no_surat" value="{{ $letter->no_surat }}">
                                @if($errors->has('no_surat'))
                                    <span id="no_surat" class="text-danger">{{ $errors->first('no_surat') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button id="gantiSurat" type="button" class="btn btn-success" data-id="{{ $letter->id }}" onClick="gantiSurat(event)">Submit</button>
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

        function gantiSurat(event) {
            event.preventDefault();
            var id = $('#gantiSurat').data("id");
            var no_surat = $('#no_surat').val();
            var token  = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                url: '/t-event-letter/update',
                type: 'POST',
                data: {
                    _token: token,
                    id: id,
                    no_surat: no_surat
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
                                window.location.replace("/t-event-letter/show/" + id);
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
                                window.location.replace("/t-event-letter/show/" + id);
                            }
                        });
                    }
                }
            });
        }
    </script>
    @endsection

</x-base-layout>