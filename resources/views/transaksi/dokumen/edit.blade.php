<x-base-layout>
    <?php
    $title = 'Edit Dokumen';
    ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('dokumen.index') }}" class="pe-3">Library</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#181C32;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('dokumen.update', $model->id) }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Nama Dokumen</label>
                            <input id="nama_dokumen" class="form-control" name="nama_dokumen" value="{{ (old('nama_dokumen')) ? old('nama_dokumen') : $model->nama_dokumen }}">
                            @if($errors->has('nama_dokumen'))
                                <span id="err_nama_dokumen" class="text-danger">{{ $errors->first('nama_dokumen') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col-md-12">
                        <label>Deskripsi</label>
                        <textarea id="deskripsi" class="form-control" name="deskripsi">{{ (old('deskripsi')) ? old('deskripsi') : $model->deskripsi }}</textarea>
                        @if($errors->has('deskripsi'))
                            <span id="err_deskripsi" class="text-danger">{{ $errors->first('deskripsi') }}</span>
                        @endif
                    </div>
                </div>

                <br>

                <div class="row mb-5">
                    <div class="col-md-12">
                        <label>Upload Dokumen</label>
                        <div style="border: solid #EFF2F5 1px; padding:5px; background-color: #EFF2F5; border-radius:5px;">
                            <input type="file" name="upload_file" class="custom-file-input" id="upload_file" value="{{ old('upload_file') }}">
                        </div>
                        @if($errors->has('upload_file'))
                            <span id="err_upload_file" class="text-danger">{{ $errors->first('upload_file') }}</span>
                        @endif
                        <div class="text-muted">
                            Dokumen yang diupload menggunakan format PDF untuk dokumen dan Mp4 untuk video dengan batas maksimal 50MB.
                        </div>
                    </div>
                </div>

                <div class="form-group mt-5 float-end">
                    <button type="submit" class="btn btn-primary"> Simpan </button>
                    <a href="{{ route('dokumen.index') }}" class="btn btn-secondary"> Kembali </a>
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
            
            $("#nama_dokumen").change(function() {
                $("#err_nama_dokumen").html("");
            });
        </script>
    @endsection

</x-base-layout>