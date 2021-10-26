<div class="card card-bordered mb-5">
    <div class="card-body">

        <form id="search">
            <div class="row mb-5">
                <div class="col-md-2">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="">
                </div>

                <div class="col-md-3">
                    <label for="no_lisensi">Nomor Lisensi</label>
                    <input type="text" class="form-control" id="no_lisensi" name="no_lisensi" value="">
                </div>

                <div class="col-md-2">
                    <label for="penyelenggara">Penyelenggara</label>
                    <input type="text" class="form-control" id="penyelenggara" name="penyelenggara" value="">
                </div>

                <div class="col-md-3">
                    <label for="tanggal_event">Tanggal</label>
                    <input class="form-control" placeholder="Pilih Tanggal ..." name="tanggal_event" id="tanggal_event">
                </div>

                <div class="col-md-2">
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
                <button type="button" class="btn btn-primary" style="border-top-right-radius: 0; border-bottom-right-radius: 0;" onclick="search(event)"> <i class="bx bx-search"></i> Cari </button>
                <button class="btn btn-warning" style="border-top-left-radius: 0; border-bottom-left-radius: 0;" onclick="reset(event)"> <i class="bx bx-reset"></i> Reset </button>
            </div>
        </form>

    </div>
</div>