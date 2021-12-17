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
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" value="">
                </div>

                <div class="col-md-4">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="">
                </div>
            </div>

            <div class="d-flex justify-content-end" style="margin-top: 15px;">
                <button type="button" class="btn btn-primary" style="border-top-right-radius: 0; border-bottom-right-radius: 0;" onclick="search(event)"> <i class="bx bx-search"></i> Cari </button>
                <button class="btn btn-warning" style="border-top-left-radius: 0; border-bottom-left-radius: 0;" onclick="reset(event)"> <i class="bx bx-reset"></i> Reset </button>
            </div>
        </form>

    </div>
</div>