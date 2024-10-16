<x-base-layout>
    <?php
    $title = 'Edit Pelanggaran : ' . $model->violation;
    ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('violation.index') }}" class="pe-3">Pelanggaran</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('violation.show', $model->id) }}" class="pe-3">{{ $model->violation }}</a></li>
        <li class="breadcrumb-item px-3 text-muted">Edit</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#181C32;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('violation.update', $model->id) }}">
                @csrf

                <div class="row mb-5">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Pelanggaran</label>
                            <input id="pelanggaran" class="form-control" name="pelanggaran" value="{{ (empty(old('pelanggaran'))) ? $model->violation : old('pelanggaran') }}">
                            @if($errors->has('pelanggaran'))
                                <span id="err_pelanggaran" class="text-danger">{{ $errors->first('pelanggaran') }}</span>
                            @endif
                        </div>
                    </div>

                    <?php $oldJenis = (!empty(old('jenis'))) ? old('jenis') : $model->jenis ; ?>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Jenis Pelanggaran</label>
                            <select class="form-select" data-placeholder="Pilih Jenis Pelanggaran ..." id="jenis" name="jenis">
                                <option value=""></option>
                                <option value="1" @if($oldJenis) {{ ($oldJenis == 1) ? 'selected' : null ; }} @endif>Fouls</option>
                                <option value="2" @if($oldJenis) {{ ($oldJenis == 2) ? 'selected' : null ; }} @endif>IRS</option>
                                <option value="3" @if($oldJenis) {{ ($oldJenis == 3) ? 'selected' : null ; }} @endif>Travelling</option>
                                <option value="4" @if($oldJenis) {{ ($oldJenis == 4) ? 'selected' : null ; }} @endif>Other Violations</option>
                            </select>
                            @if($errors->has('jenis'))
                                <span id="err_jenis" class="text-danger">{{ $errors->first('jenis') }}</span>
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
                    <a href="{{ route('violation.show', $model->id) }}" class="btn btn-secondary"> Kembali </a>
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
            
            $("#jenis").select2({
                // the following code is used to disable x-scrollbar when click in select input and
                // take 100% width in responsive also
                placeholder: "Pilih ...",
                dropdownAutoWidth: true,
                width: '100%',
            });
            
            $("#pelanggaran").change(function() {
                $("#err_pelanggaran").html("");
            });

            $('#jenis').select2().on('change', function(){
                $("#err_jenis").html("");
            });
        </script>
    @endsection

</x-base-layout>