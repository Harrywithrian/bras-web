<?php
$license = \App\Models\Master\License::where('type', '=', 1)->where('status', '=', 1)->whereNull('deletedon')->get()->toArray();
$region = \App\Models\Master\Region::where('status', '=', 1)->whereNull('deletedon')->get()->toArray();
?>
<div class="card card-bordered mb-5">
    <div class="card-body">

        <form id="search">
            <div class="row">
                <div class="col-md-4">
                    <label for="alias">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="">
                </div>

                <div class="col-md-4">
                    <label for="license">Jenis Lisensi</label>
                    <select class="form-select form-control form-control-lg" data-control="select2" data-placeholder="Pilih Lisensi ..." data-allow-clear="true" id="license" name="license">
                        <option value=""></option>
                        @if($license)
                            @foreach($license as $item)
                                <option value="{{ $item['id'] }}">{{ $item['license'] }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="license">Pengurus Provinsi</label>
                    <select class="form-select form-control form-control-lg" data-control="select2" data-placeholder="Pilih Pengurus Provinsi ..." data-allow-clear="true" id="region" name="region">
                        <option value=""></option>
                        @if($region)
                            @foreach($region as $item)
                                <option value="{{ $item['id'] }}">{{ $item['region'] }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end" style="margin-top: 15px;">
                <button type="button" class="btn btn-primary" style="border-top-right-radius: 0; border-bottom-right-radius: 0;" onclick="search(event)"> <i class="bx bx-search"></i> Cari </button>
                <a class="btn btn-warning" style="border-top-left-radius: 0; border-bottom-left-radius: 0;" href="{{route('wasit.index')}}"> <i class="bx bx-reset"></i> Reset </a>
            </div>
        </form>

    </div>
</div>