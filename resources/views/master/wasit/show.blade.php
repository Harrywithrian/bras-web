<x-base-layout>

    <?php $title = $user->name ?>

    <style>
        .responsive {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
    </style>
    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('wasit.index') }}" class="pe-3">List Wasit</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="row">
        <div class="col-md-2">
            <div class="card shadow-sm">
                <div class="card-body">
                    <center>
                        <img class="responsive" src="{{ url('storage/'.$foto->path) }}">
                    </center>
                </div>
            </div>

            <center>
                <a id="approve" href="{{ route('wasit.index') }}" class="btn btn-secondary mt-2 p-3" style="width:100%">Kembali</a>
            </center>
        </div>

        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="alert alert-dismissible bg-primary p-3" style="margin-bottom:0px; border-radius: 0px;">
                        <div class="d-flex flex-column text-light">
                            <span style="font-weight:bold;">Data Diri</span>
                        </div>
                    </div>
                    <table class="table table bordered table-striped">
                        <tr>
                            <td class="p-5" width="15%">Nama</td>
                            <td class="p-5" width="1%">:</td>
                            <td class="p-5" >{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td class="p-5" >Tempat Lahir</td>
                            <td class="p-5" >:</td>
                            <td class="p-5" >{{$userDetail->tempat_lahir}}</td>
                        </tr>
                        <tr>
                            <td class="p-5" >Tempat Lahir</td>
                            <td class="p-5" >:</td>
                            <td class="p-5" >{{date('d-m-Y', strtotime($userDetail->tanggal_lahir)) }}</td>
                        </tr>
                        <tr>
                            <td class="p-5" >Alamat</td>
                            <td class="p-5" >:</td>
                            <td class="p-5" >{{ $userDetail->alamat }}</td>
                        </tr>
                        <tr>
                            <td class="p-5" >Pengurus Provinsi</td>
                            <td class="p-5" >:</td>
                            <td class="p-5" >{{ $provinsi->region }}</td>
                        </tr>
                        <tr>
                            <td class="p-5" >Tanggal Pendaftaran</td>
                            <td class="p-5" >:</td>
                            <td class="p-5" >{{ date('H:i:s / d-m-Y', strtotime($userDetail->created_at)) }}</td>
                        </tr>
                    </table>
                    <section class="card bg-primary mt-0 mb-0" style="border-radius: 0">
                        <div class="card-header">
                            <h4 class="card-title" style="color: white;">Lisensi</h4>
                        </div>
                    </section>
                    <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                        <tr>
                            <td width="5%">No</td>
                            <td width="20%">Nomor Lisensi</td>
                            <td>Jenis Lisensi</td>
                            <td>Tanggal Aktif</td>
                            <td>Tanggal Expired</td>
                            <td>Status</td>
                        </tr>
                        @if(count($lisensi) > 0)
                            @foreach ($lisensi as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->nomor_lisensi }}</td>
                                    <td>{{ $item->jenis_lisensi }}</td>
                                    <td>{{ date('d-m-Y', strtotime($item->start_date)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($item->end_date)) }}</td>
                                    <td>
                                        @if($item->end_date >= date('Y-m-d'))
                                            @if ($item->status == 1)
                                                <span class='w-130px badge badge-success me-4'> Active </span>
                                            @else
                                                <span class='w-130px badge badge-warning me-4'> Inactive </span>
                                            @endif
                                        @else
                                            <span class='w-130px badge badge-danger me-4'> Expired </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center"><h4>Lisensi Tidak Ditemukan</h4></td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-base-layout>