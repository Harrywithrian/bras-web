<x-auth-layout>
    <?php
        $lisensi = \App\Models\Master\License::select('id', 'license')->where('type', '=', 1)->where('status', '=', 1)->whereNull('deletedon')->get()->toArray();
        $lisensiPengawas = \App\Models\Master\License::select('id', 'license')->where('type', '=', 2)->where('status', '=', 1)->whereNull('deletedon')->get()->toArray();

        $region  = \App\Models\Master\Region::where('status', '=', 1)->whereNull('deletedon')->get()->toArray();
    ?>

    <form method="POST" action="{{ theme()->getPageUrl('register') }}" class="form w-100" novalidate="novalidate" enctype="multipart/form-data">
    @csrf

        <div class="text-center mb-10">
            <h1 class="text-dark">Indonesia Basketball Referee</h1>
            <label>Form Pendaftaran User</label>
        </div>

        <div class="fv-row mb-7">
            <label class="form-label fw-bolder text-dark fs-6">Jenis Daftar</label>
            <select class="form-select form-control form-control-lg form-control-solid" data-placeholder="Pilih ..." data-allow-clear="true" id="jenis_daftar" name="jenis_daftar">
                <option value=""></option>
                <option value="6" {{(old('jenis_daftar') == 6) ? 'selected' : '';}}>Pengawas Pertandingan</option>
                <option value="7" {{(old('jenis_daftar') == 7) ? 'selected' : '';}}>Koordinator Wasit</option>
                <option value="8" {{(old('jenis_daftar') == 8) ? 'selected' : '';}}>Wasit</option>
            </select>
            @if($errors->has('jenis_daftar'))
                <span id="err_jenis_daftar" class="text-danger">{{ $errors->first('jenis_daftar') }}</span>
            @endif
        </div>

        <div class="fv-row mb-7">
            <label class="form-label fw-bolder text-dark fs-6">Nama Lengkap</label>
            <input class="form-control form-control-lg form-control-solid" type="text" name="nama" autocomplete="off" value="{{ old('nama') }}"/>
            @if($errors->has('nama'))
                <span id="err_nama" class="text-danger">{{ $errors->first('nama') }}</span>
            @endif
        </div>

        <div class="fv-row mb-7">
            <label class="form-label fw-bolder text-dark fs-6">Nomor Lisensi</label>
            <input class="form-control form-control-lg form-control-solid" type="text" name="lisensi" autocomplete="off" value="{{ old('lisensi') }}">
            @if($errors->has('lisensi'))
                <span id="err_lisensi" class="text-danger">{{ $errors->first('lisensi') }}</span>
            @endif
        </div>

        <div class="fv-row mb-7">
            <label class="form-label fw-bolder text-dark fs-6">Jenis Lisensi</label>
            <select class="form-select form-control form-control-lg form-control-solid" data-control="select2" data-placeholder="Pilih Lisensi ..." data-allow-clear="true" id="jenis_lisensi" name="jenis_lisensi">
                <option value=""></option>
                @if (old('jenis_lisensi'))
                    @if (old('jenis_daftar') == 6)
                        @foreach($lisensiPengawas as $item)
                            <option value="{{ $item['id'] }}" {{(old('jenis_lisensi') == $item['id']) ? 'selected' : '';}}>{{ $item['license'] }}</option>
                        @endforeach
                    @else
                        @foreach($lisensi as $item)
                            <option value="{{ $item['id'] }}" {{(old('jenis_lisensi') == $item['id']) ? 'selected' : '';}}>{{ $item['license'] }}</option>
                        @endforeach
                    @endif
                @endif
            </select>
            @if($errors->has('jenis_lisensi'))
                <span id="err_jenis_lisensi" class="text-danger">{{ $errors->first('jenis_lisensi') }}</span>
            @endif
        </div>

        <div class="fv-row mb-7">
            <label class="form-label fw-bolder text-dark fs-6">Tempat Lahir</label>
            <input class="form-control form-control-lg form-control-solid" type="text" name="tempat_lahir" autocomplete="off" value="{{ old('tempat_lahir') }}">
            @if($errors->has('tempat_lahir'))
                <span id="err_tempat_lahir" class="text-danger">{{ $errors->first('tempat_lahir') }}</span>
            @endif
        </div>

        <div class="fv-row mb-7">
            <label class="form-label fw-bolder text-dark fs-6">Tanggal Lahir</label>
            <input class="form-control form-control-solid" placeholder="Pilih Tanggal ..." name="tanggal_lahir" id="tanggal_lahir" value="{{old('tanggal_lahir')}}">
            @if($errors->has('tanggal_lahir'))
                <span id="err_tanggal_lahir" class="text-danger">{{ $errors->first('tanggal_lahir') }}</span>
            @endif
        </div>

        <div class="fv-row mb-7">
            <label class="form-label fw-bolder text-dark fs-6">Alamat</label>
            <textarea id="alamat" class="form-control form-control-solid" name="alamat">{{ old('alamat') }}</textarea>
            @if($errors->has('alamat'))
                <span id="err_alamat" class="text-danger">{{ $errors->first('alamat') }}</span>
            @endif
        </div>

        <div class="fv-row mb-7">
            <label class="form-label fw-bolder text-dark fs-6">Provinsi</label>
            <select class="form-select form-control form-control-lg form-control-solid" data-control="select2" data-placeholder="Pilih Provinsi ..." data-allow-clear="true" id="provinsi" name="provinsi">
                <option value=""></option>
                @foreach($region as $item)
                    <option value="{{ $item['id'] }}" {{(old('provinsi') == $item['id']) ? 'selected' : '';}}>{{ $item['region'] }}</option>
                @endforeach
            </select>
            @if($errors->has('provinsi'))
                <span id="err_provinsi" class="text-danger">{{ $errors->first('provinsi') }}</span>
            @endif
        </div>

        <div class="fv-row mb-7">
            <label class="form-label fw-bolder text-dark fs-6">Email</label>
            <input class="form-control form-control-lg form-control-solid" type="email" name="email" autocomplete="off" value="{{ old('email') }}">
            @if($errors->has('email'))
                <span id="err_email" class="text-danger">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <div class="fv-row mb-7">
            <label class="form-label fw-bolder text-dark fs-6">Username</label>
            <input class="form-control form-control-lg form-control-solid" type="text" name="username" autocomplete="off" value="{{ old('username') }}">
            @if($errors->has('username'))
                <span id="err_username" class="text-danger">{{ $errors->first('username') }}</span>
            @endif
        </div>

        <div class="mb-5 fv-row" data-kt-password-meter="true">
            <div class="mb-1">
                <label class="form-label fw-bolder text-dark fs-6">Password</label>
                <div class="position-relative mb-3">
                    <input class="form-control form-control-lg form-control-solid" type="password" name="password" autocomplete="off">

                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                        <i class="bi bi-eye-slash fs-2"></i>
                        <i class="bi bi-eye fs-2 d-none"></i>
                    </span>
                </div>

                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                </div>
            </div>
            @if($errors->has('password'))
                <span id="err_password" class="text-danger">{{ $errors->first('password') }}</span>
            @endif
            <div class="text-muted">
                Gunakan 8 karakter atau lebih. serta gunakan kombinasi huruf, angka, dan simbol.
            </div>
        </div>

        <div class="fv-row mb-5">
            <label class="form-label fw-bolder text-dark fs-6">Re-type Password</label>
            <input class="form-control form-control-lg form-control-solid" type="password" name="password_confirmation" autocomplete="off">
        </div>

        <div class="fv-row mb-5">
            <label class="form-label fw-bolder text-dark fs-6">Upload Lisensi</label>
            <div style="border: solid #EFF2F5 1px; padding:5px; background-color: #EFF2F5; border-radius:5px;">
                <input type="file" name="upload_lisensi" class="custom-file-input" id="upload_lisensi" value="{{ old('upload_lisensi') }}">
            </div>
            @if($errors->has('upload_lisensi'))
                <span id="err_upload_lisensi" class="text-danger">{{ $errors->first('upload_lisensi') }}</span>
            @endif
            <div class="text-muted">
                Maksimal 10MB dan gunakan format PDF.
            </div>
        </div>

        <div class="fv-row mb-5">
            <label class="form-label fw-bolder text-dark fs-6">Upload Foto</label>
            <div style="border: solid #EFF2F5 1px; padding:5px; background-color: #EFF2F5; border-radius:5px;">
                <input type="file" name="upload_foto" class="custom-file-input" id="upload_foto" value="{{ old('upload_foto') }}">
            </div>
            @if($errors->has('upload_foto'))
                <span id="err_upload_foto" class="text-danger">{{ $errors->first('upload_foto') }}</span>
            @endif
            <div class="text-muted">
                Foto harus ber jas rapi, background berwarna biru, maksimal 10MB dan menggunakan format JPG, PNG, atau JPEG.
            </div>
        </div>

        <div class="form-group mt-5">
            <button type="submit" class="btn btn-lg btn-primary w-100 mb-5"> Daftar </button>
        </div>

        <center>
            <div class="text-muted">
                Sudah punya akun? <a href="{{ route('login') }}">Login disini.</a>
            </div>
        </center>
    </form>

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

                    $('#jenis_daftar').select2().on('change', function() {
                        var data = $("#jenis_daftar option:selected").text();
                        $('#jenis_lisensi').empty().trigger("change");
                        $('#jenis_lisensi').append('<option></option>').trigger("change");

                        if (data == 'Pengawas Pertandingan') {
                            @foreach($lisensiPengawas as $item)
                            $('#jenis_lisensi').append('<option value="{{ $item['id'] }}">{{ $item['license'] }}</option>').trigger("change");
                            @endforeach
                        } else {
                            @foreach($lisensi as $item)
                            $('#jenis_lisensi').append('<option value="{{ $item['id'] }}">{{ $item['license'] }}</option>').trigger("change");
                            @endforeach
                        }
                    });
                });

                $("#tanggal_lahir").daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    autoUpdateInput: false,
                    locale: {
                        cancelLabel: 'Clear'
                    }
                });

                $("#tanggal_lahir").on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD'));
                });
            </script>
        @endsection
</x-auth-layout>
