<div class="card card-bordered mb-5">
    <div class="card-body">

        <form id="search">
            <div class="row">
                <div class="col-md-6">
                    <label for="lisensi">Pelanggaran</label>
                    <input type="text" class="form-control" id="pelanggaran" name="pelanggaran" value="">
                </div>

                <div class="col-md-6">
                    <label for="status">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value=""></option>
                        <option value="1">Aktif</option>
                        <option value="0">Inactive</option>
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