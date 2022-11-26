<x-base-layout>
    <?php
    $title = 'Tambah Lokasi Pertandingan';
    $provinsi = \App\Models\Master\Region::select(['id', 'region'])->whereNull('deletedon')->get()->toArray();
    ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('location.index') }}" class="pe-3">Lokasi Pertandingan</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#181C32;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('location.store') }}">
                @csrf

                <div class="row mb-5">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Lokasi <span style="color: red">*</span></label>
                            <input id="nama" class="form-control" name="nama" value="{{ old('nama') }}">
                            @if($errors->has('nama'))
                                <span id="err_nama" class="text-danger">{{ $errors->first('nama') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Provinsi <span style="color: red">*</span></label>
                            <select class="form-select" data-placeholder="Pilih Provinsi ..." data-allow-clear="true" id="provinsi" name="provinsi">
                                <option value=""></option>
                                @foreach($provinsi as $item)
                                    <option value="{{ $item['id'] }}" {{(old('provinsi') == $item['id']) ? 'selected' : '';}}>{{ $item['region'] }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('provinsi'))
                                <span id="err_provinsi" class="text-danger">{{ $errors->first('provinsi') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Telepon</label>
                            <input id="telepon" class="form-control" name="telepon" value="{{ old('telepon') }}">
                            @if($errors->has('telepon'))
                                <span id="err_telepon" class="text-danger">{{ $errors->first('telepon') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">
                            @if($errors->has('email'))
                                <span id="err_email" class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label>Alamat</label>
                        <textarea id="alamat" class="form-control" name="alamat">{{ old('alamat') }}</textarea>
                        @if($errors->has('alamat'))
                            <span id="err_alamat" class="text-danger">{{ $errors->first('alamat') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group mt-5 float-end">
                    <button type="submit" class="btn btn-primary"> Simpan </button>
                    <a href="{{ route('location.index') }}" class="btn btn-secondary"> Kembali </a>
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

            $("#provinsi").select2({
                // the following code is used to disable x-scrollbar when click in select input and
                // take 100% width in responsive also
                placeholder: "Pilih ...",
                dropdownAutoWidth: true,
                width: '100%'
            });
            
            $("#nama").change(function() {
                $("#err_nama").html("");
            });

            $('#provinsi').select2().on('change', function(){
                $("#err_provinsi").html("");
            });
        </script>
    @endsection

</x-base-layout>