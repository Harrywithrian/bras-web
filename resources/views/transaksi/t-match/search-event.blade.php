<div class="card card-bordered mb-5">
    <div class="card-body">

        <form id="search">
            <div class="row mb-5">
                <div class="col-md-6">
                    <label for="nama">Nama Event</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="">
                </div>

                <div class="col-md-6">
                    <label for="tgl_event">Tanggal Event</label>
                    <input class="form-control form-control-solid" placeholder="Pilih Tanggal ..." id="tgl_event" value="">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label for="lisensi">Nomor Lisensi</label>
                    <input type="text" class="form-control" id="lisensi" name="lisensi" value="">
                </div>

                <div class="col-md-6">
                    <label for="penyelenggara">Penyelenggara</label>
                    <input type="text" class="form-control" id="penyelenggara" name="penyelenggara" value="">
                </div>
            </div>

            <div class="d-flex justify-content-end" style="margin-top: 15px;">
                <button type="button" class="btn btn-primary" style="border-top-right-radius: 0; border-bottom-right-radius: 0;" onclick="search(event)"> <i class="bx bx-search"></i> Cari </button>
                <button class="btn btn-warning" style="border-top-left-radius: 0; border-bottom-left-radius: 0;" onclick="reset(event)"> <i class="bx bx-reset"></i> Reset </button>
            </div>
        </form>

    </div>
</div>