<x-base-layout>
    <?php $title = 'Edit Provinsi : ' . $model->region ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('region.index') }}" class="pe-3">Provinsi</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('region.show', $model->id) }}" class="pe-3">{{ $model->region }}</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('region.update', $model->id) }}">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kode</label>
                            <input id="kode" class="form-control" name="kode" value="{{ (empty(old('kode'))) ? $model->kode : old('kode') }}">
                            @if($errors->has('kode'))
                                <span id="err_kode" class="text-danger">{{ $errors->first('kode') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Provinsi</label>
                            <input id="provinsi" class="form-control" name="provinsi" value="{{ (empty(old('provinsi'))) ? $model->region : old('provinsi') }}">
                            @if($errors->has('provinsi'))
                                <span id="err_provinsi" class="text-danger">{{ $errors->first('provinsi') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ (empty(old('email'))) ? $model->email : old('email') }}">
                            @if($errors->has('kode'))
                                <span id="err_email" class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group mt-5 float-end">
                    <button type="submit" class="btn btn-primary"> Simpan </button>
                    <a href="{{ route('region.show', $model->id) }}" class="btn btn-secondary"> Kembali </a>
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