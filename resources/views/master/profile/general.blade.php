<style>
    #profile tr td {
        border: 1px solid #ddd !important;
    }
</style>

<a href="{{ route('profile.tambah-lisensi', $user->id) }}" class="btn btn-success"> Tambah Lisensi </a>

<br><br>

<section class="card bg-primary mb-0" style="border-radius: 0">
    <div class="card-header">
        <h4 class="card-title" style="color: white;">Account</h4>
    </div>
</section>
<table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
    <tr>
        <td width="25%">Username</td>
        <td>{{ $user->username }}</td>
    </tr>
    <tr>
        <td width="25%">email</td>
        <td>{{ $user->email }}</td>
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
        <td>{{ $user->name }}</td>
    </tr>
    <tr>
        <td width="25%">Pengurus Provinsi</td>
        <td>{{ $provinsi->region }}</td>
    </tr>
    <tr>
        <td width="25%">Tempat, Tanggal Lahir</td>
        <td>{{ $userDetail->tempat_lahir }}, {{ date('d-m-Y', strtotime($userDetail->tanggal_lahir)) }}</td>
    </tr>
    <tr>
        <td width="25%">Alamat</td>
        <td>{{ $userDetail->alamat }}</td>
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
        <td>Action</td>
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
                <td>
                    <a class="btn btn-success btn-sm" title="Download" style="padding:5px; margin-left:5px;" href="{{ route('profile.download-lisensi', $item->id) }}"> &nbsp<i class="bi bi-download"></i> </a>
                    <a class="btn btn-warning btn-sm" title="Edit" style="padding:5px; margin-left:5px;" href="{{ route('profile.edit-lisensi', ['userid' => $user->id, 'id' => $item->id]) }}"> &nbsp<i class="bi bi-pencil-square"></i> </a>
                    <btn class="btn btn-danger deleteLisensi" title="Delete" style="padding:5px; margin-left:5px;" data-id="{{ $item->id }}"> &nbsp<i class="bi bi-trash"></i> </btn>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="7" class="text-center"><h4>Lisensi Tidak Ditemukan</h4></td>
        </tr>
    @endif
</table>