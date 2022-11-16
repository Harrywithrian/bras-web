<x-base-layout>
    <?php
    $title = 'Edit Lisensi : ' . $model->license;
    ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('license.index') }}" class="pe-3">Lisensi</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('license.show', $model->id) }}" class="pe-3">{{ $model->license }}</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('license.update', $model->id) }}">
                @csrf

                <div class="row mb-5">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Lisensi</label>
                            <input id="lisensi" class="form-control" name="lisensi" value="{{ (empty(old('lisensi'))) ? $model->license : old('lisensi') }}">
                            @if($errors->has('lisensi'))
                                <span id="err_lisensi" class="text-danger">{{ $errors->first('lisensi') }}</span>
                            @endif
                        </div>
                    </div>

                    <?php $oldLisensi = (old('jenis_lisensi')) ? old('jenis_lisensi') : $model->type ; ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Jenis Lisensi</label>
                            <select class="form-select" data-placeholder="Pilih Jenis Lisensi ..." id="jenis_lisensi" name="jenis_lisensi">
                                <option value=""></option>
                                <option value="1" @if($oldLisensi) {{($oldLisensi == 1) ? 'selected' : null}} @endif>Wasit</option>
                                <option value="2" @if($oldLisensi) {{($oldLisensi == 2) ? 'selected' : null}} @endif>Pengawas Pertandingan</option>
                            </select>
                            @if($errors->has('jenis_lisensi'))
                                <span id="err_jenis_lisensi" class="text-danger">{{ $errors->first('jenis_lisensi') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label>Keterangan</label>
                        <textarea id="keterangan" class="form-control" name="keterangan">{{ (empty(old('keterangan'))) ? $model->keterangan : old('keterangan') }}</textarea>
                        @if($errors->has('keterangan'))
                            <span id="err_keterangan" class="text-danger">{{ $errors->first('keterangan') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group mt-5 float-end">
                    <button type="submit" class="btn btn-primary"> Simpan </button>
                    <a href="{{ route('license.show', $model->id) }}" class="btn btn-secondary"> Kembali </a>
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
        <script>
            $(document).ready( function() {
                @if(\Illuminate\Support\Facades\Session::has('error'))
                    var msg = JSON.parse('<?php echo json_encode(\Illuminate\Support\Facades\Session::get('error')); ?>');
                    toastr['error'](msg, 'Error', {
                        closeButton: true,
                        tapToDismiss: false,
                        rtl: false
                    });
                @endif
            });

            $("#jenis_lisensi").select2({
                // the following code is used to disable x-scrollbar when click in select input and
                // take 100% width in responsive also
                placeholder: "Pilih ...",
                dropdownAutoWidth: true,
                width: '100%',
            });
            
            $("#lisensi").change(function() {
                $("#err_lisensi").html("");
            });

            $('#jenis_lisensi').select2().on('change', function(){
                $("#err_jenis_lisensi").html("");
            });
        </script>
    @endsection

</x-base-layout>