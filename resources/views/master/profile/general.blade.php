<style>
    #profile tr td {
        border: 1px solid #ddd !important;
    }
</style>

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
        <td width="25%">Lisensi</td>
        <td>{{ $lisensi->license }}</td>
    </tr>
    <tr>
        <td width="25%">Nomor Lisensi</td>
        <td>{{ $userDetail->no_lisensi }}</td>
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