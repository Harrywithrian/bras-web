<div class="card card-bordered mb-5">
    <div class="card-body">

        <form id="search">
            <div class="row">
                <div class="col-md-3">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="">
                </div>

                <div class="col-md-3">
                    <label for="no_lisensi">Nomor Lisensi</label>
                    <input type="text" class="form-control" id="no_lisensi" name="no_lisensi" value="">
                </div>

                <div class="col-md-3">
                    <label for="jenis_lisensi">Jenis Lisensi</label>
                    <select class="form-select" id="jenis_lisensi" name="jenis_lisensi">
                        <option value=""></option>
                        @foreach($license as $item)
                            <option value="{{ $item['id'] }}">{{ $item['license'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="pengprov">Pengurus Provinsi</label>
                    <select class="form-select" id="pengprov" name="pengprov">
                        <option value=""></option>
                        @foreach($pengprov as $item)
                            <option value="{{ $item['id'] }}">{{ $item['region'] }}</option>
                        @endforeach
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