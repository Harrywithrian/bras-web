<x-base-layout>
    <style>
        table tr td {
            border: 1px solid #ddd !important;
        }
    </style>

    <?php $title = 'Notifikasi Event : ' . $event->nama ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">

            <section class="card bg-primary mb-0 mt-5" style="border-radius: 0">
                <div class="card-header">
                    <h4 class="card-title" style="color: white;">General</h4>
                </div>
            </section>
            <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                <tr>
                    <td width="25%">Nama Event</td>
                    <td>{{ $event->nama }}</td>
                </tr>
                <tr>
                    <td width="25%">Nomor Lisensi Event</td>
                    <td>{{ $event->no_lisensi }}</td>
                </tr>
                <tr>
                    <td width="25%">Deskripsi</td>
                    <td>{{ $event->deskripsi }}</td>
                </tr>
                <tr>
                    <td width="25%">Tanggal Event</td>
                    <td>{{ date('d/m/Y', strtotime($event->tanggal_mulai)) . " - " . date('d/m/Y', strtotime($event->tanggal_selesai)) }}</td>
                </tr>
                <tr>
                    <td width="25%">Prioritas Event</td>
                    <td>
                        @if($event->tipe == 0)
                            <span class='rounded-pill bg-success' style="padding:5px; color: white"> Normal </span>
                        @elseif($event->tipe == 1)
                            <span class='rounded-pill bg-danger' style="padding:5px; color: white"> Urgent </span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td width="25%">Penyelenggara</td>
                    <td>{{ $event->getPenyelenggara->name }}</td>
                </tr>
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

            @if ($notifikasi->status != 2)
                <br>

                <div class="card shadow-sm">
                    <div class="card-header" style="background-color:#1e1e2d; color:white;">
                        <h3 class="card-title text-light"> Reply </h3>
                    </div>

                    <div class="card-body">
                        <form method="post" action="{{ route('notifikasi.reply-event', $notifikasi->id) }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <textarea id="reply" class="form-control" name="reply">{{ old('reply') }}</textarea>
                                    @if($errors->has('reply'))
                                        <span id="err_reply" class="text-danger">{{ $errors->first('reply') }}</span>
                                    @endif
                                </div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary"> Submit </button>
                        </form>
                    </div>
                </div>

            @endif
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