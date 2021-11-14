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
                            <td class="p-5" >Nomor Lisensi</td>
                            <td class="p-5" >:</td>
                            <td class="p-5" >{{$userDetail->no_lisensi}}</td>
                        </tr>
                        <tr>
                            <td class="p-5" >Jenis Lisensi</td>
                            <td class="p-5" >:</td>
                            <td class="p-5" >{{$lisensi->license}}</td>
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
                            <td class="p-5" >{{ date('H:i:s / d-m-Y', strtotime($userDetail->createdon)) }}</td>
                        </tr>
                    </table>
                    <div class="alert alert-dismissible bg-primary p-3" style="margin-bottom:0px; border-radius: 0px;">
                        <div class="d-flex flex-column text-light">
                            <span style="font-weight:bold;">Berkas</span>
                        </div>
                    </div>
                    <table class="table table bordered table-striped">
                        <tr>
                            <td class="p-5" width="15%">Lisensi</td>
                            <td class="p-5" width="1%">:</td>
                            <td class="p-5" ><a href="{{ route('t-approval.download-lisensi', $userDetail->id_t_file_lisensi) }}"> Download </a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-base-layout>