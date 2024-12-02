<x-base-layout>
    <?php
    $title = 'Data Ketua Umum';
    ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#181C32;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('m-ketua-umum.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="row mb-5">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input id="nama" class="form-control" name="nama" value="{{ $model->nama }}">
                            @if($errors->has('nama'))
                                <span id="err_nama" class="text-danger">{{ $errors->first('nama') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-12">
                        Tanda Tangan Eksisting <br>
                        <div style="border: solid #EFF2F5 1px; padding:5px; background-color: #EFF2F5; border-radius:5px;">
                            <img class="responsive" src="{{ url('storage/'.$model->img_tanda_tangan) }}">
                        </div>

                        <br>

                        <label>Upload Tanda Tangan</label>
                        <div style="border: solid #EFF2F5 1px; padding:5px; background-color: #EFF2F5; border-radius:5px;">
                            <input type="file" name="tanda_tangan" class="custom-file-input" id="tanda_tangan">
                        </div>
                        @if($errors->has('tanda_tangan'))
                            <span id="err_tanda_tangan" class="text-danger">{{ $errors->first('tanda_tangan') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group mt-5 float-end">
                    <button type="submit" class="btn btn-primary"> Update </button>
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
        <script>
            $(document).ready( function() {
                @if(\Illuminate\Support\Facades\Session::has('success'))
                    var msg = JSON.parse('<?php echo json_encode(\Illuminate\Support\Facades\Session::get('success')); ?>');
                    toastr['success'](msg, 'Success', {
                        closeButton: true,
                        tapToDismiss: false,
                        rtl: false
                    });
                @endif

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