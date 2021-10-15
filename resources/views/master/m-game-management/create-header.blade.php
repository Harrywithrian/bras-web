<x-base-layout>
    <?php
    $title = 'Tambah Header';
    ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('m-game-management.index') }}" class="pe-3">Template Game Management</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('m-game-management.store-header') }}">
                @csrf

                <div class="row mb-5">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nama</label>
                            <input id="nama" class="form-control" name="nama" value="{{ old('nama') }}">
                            @if($errors->has('nama'))
                                <span id="err_nama" class="text-danger">{{ $errors->first('nama') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Persentase</label>
                            <div class="input-group">
                                <input id="persentase" name="persentase" type="text" class="form-control" aria-describedby="persentase" value="{{ old('persentase') }}">
                                <span class="input-group-text" id="persentase">%</span>
                            </div>
                            @if($errors->has('persentase'))
                                <span id="err_persentase" class="text-danger">{{ $errors->first('persentase') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Urutan</label>
                            <input id="urutan" class="form-control" name="urutan" value="{{ old('urutan') }}">
                            @if($errors->has('urutan'))
                                <span id="err_urutan" class="text-danger">{{ $errors->first('urutan') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group mt-5 float-end">
                    <button type="submit" class="btn btn-primary"> Simpan </button>
                    <a href="{{ route('m-game-management.index') }}" class="btn btn-secondary"> Kembali </a>
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
        </script>
    @endsection

</x-base-layout>