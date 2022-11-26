<?php
$license = \App\Models\Master\License::select(['id', 'license'])->where('status', '=', 1)->whereNull('deletedon')->get()->toArray();
?>

<div class="card card-bordered mb-5">
    <div class="card-body">

        <form id="search">
            <div class="row mb-5">
                <div class="col-md-4">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="">
                </div>

                <div class="col-md-4">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="">
                </div>

                <div class="col-md-4">
                    <label for="jenis">Jenis Daftar</label>
                    <select class="form-select" id="jenis" name="jenis">
                        <option value=""></option>
                        <option value="6">Pengawas Pertandingan</option>
                        <option value="7">Koordinator Wasit</option>
                        <option value="8">Wasit</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <label for="no_lisensi">Nomor Lisensi</label>
                    <input type="text" class="form-control" id="no_lisensi" name="no_lisensi" value="">
                </div>

                <div class="col-md-4">
                    <label for="lisensi">Jenis Lisensi</label>
                    <select class="form-select" id="lisensi" name="lisensi">
                        <option value=""></option>
                        @foreach($license as $item)
                            <option value="{{ $item['id'] }}"> {{ $item['license'] }} </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="status">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value=""></option>
                        <option value="1">Approved</option>
                        <option value="0">Waiting</option>
                        <option value="-1">Rejected</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end" style="margin-top: 15px;">
                <button type="button" id="cari" class="btn btn-primary" style="border-top-right-radius: 0; border-bottom-right-radius: 0;" onclick="search(event)"> <i class="bx bx-search"></i> Cari </button>
                <button type="button" class="btn btn-warning" style="border-top-left-radius: 0; border-bottom-left-radius: 0;" onclick="resets(event)">  <i class="bx bx-reset"></i> Reset </button>
            </div>
        </form>

    </div>
</div>