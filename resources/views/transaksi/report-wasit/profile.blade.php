<style>
    .responsive {
        width: 100%;
        height: auto;
        border-radius: 10px;
    }

    #t-profile {
        border: 1px solid #ddd !important;
    }

    #t-profile tr td {
        border: 1px solid #ddd !important;
    }
</style>

<div class="row">
    <div class="col-md-2">
        <img class="responsive" src="{{ url('storage/'.$foto->path) }}">
    </div>

    <div class="col-md-10">

        <div class="alert alert-dismissible bg-primary p-3" style="margin-bottom:0px; border-radius: 0px;">
            <div class="d-flex flex-column text-light">
                <span style="font-weight:bold;">Data Diri</span>
            </div>
        </div>
        <table id="t-profile" class="table table bordered table-striped">
            <tr>
                <td class="p-5" width="15%">Nama</td>
                <td class="p-5" >{{ $user->name }}</td>
            </tr>
            <tr>
                <td class="p-5" >Nomor Lisensi</td>
                <td class="p-5" >{{$detail->no_lisensi}}</td>
            </tr>
            <tr>
                <td class="p-5" >Jenis Lisensi</td>
                <td class="p-5" >{{$detail->license->license}}</td>
            </tr>
            <tr>
                <td class="p-5" >Tempat Lahir</td>
                <td class="p-5" >{{$detail->tempat_lahir}}</td>
            </tr>
            <tr>
                <td class="p-5" >Tanggal Lahir</td>
                <td class="p-5" >{{date('d-m-Y', strtotime($detail->tanggal_lahir)) }}</td>
            </tr>
            <tr>
                <td class="p-5" >Alamat</td>
                <td class="p-5" >{{ $detail->alamat }}</td>
            </tr>
            <tr>
                <td class="p-5" >Pengurus Provinsi</td>
                <td class="p-5" >{{ $detail->region->region }}</td>
            </tr>

            <tr>
                <td class="p-5" >Total Pertandingan</td>
                <td class="p-5" >{{ $totalMatch }}</td>
            </tr>
        </table>

    </div>
</div>