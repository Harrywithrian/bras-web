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

        <div class="alert alert-dismissible bg-primary p-3" style="margin-bottom:0px; border-radius: 0px;">
            <div class="d-flex flex-column text-light">
                <span style="font-weight:bold;">Lisensi</span>
            </div>
        </div>
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
                    <td colspan="6" class="text-center"><h4>Lisensi Tidak Ditemukan</h4></td>
                </tr>
            @endif
        </table>
    </div>
</div>