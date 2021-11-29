<x-base-layout>
    <?php
    $title = 'Tambah User';
    $region  = \App\Models\Master\Region::where('status', '=', 1)->whereNull('deletedon')->get()->toArray();
    ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('m-user.index') }}" class="pe-3">User</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('m-user.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row mb-5">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Username</label>
                            <input id="username" class="form-control" name="username" value="{{ old('username') }}">
                            @if($errors->has('username'))
                                <span id="err_username" class="text-danger">{{ $errors->first('username') }}</span>
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

                <div class="row mb-5">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-select form-control" data-control="select2" data-placeholder="Pilih Role ..." id="role" name="role">
                                <option value=""></option>
                                    <option value="2" {{(old('role') == 2) ? 'selected' : ''}}>Admin</option>
                                    <option value="3" {{(old('role') == 3) ? 'selected' : ''}}>Ketua Umum</option>
                                    <option value="4" {{(old('role') == 4) ? 'selected' : ''}}>Pengurus Provinsi</option>
                                    <option value="5" {{(old('role') == 5) ? 'selected' : ''}}>Komisi Teknik</option>
                                    <option value="9" {{(old('role') == 9) ? 'selected' : ''}}>Admin Sekertariat</option>
                            </select>
                            @if($errors->has('provinsi'))
                                <span id="err_provinsi" class="text-danger">{{ $errors->first('provinsi') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-6">
                        <div data-kt-password-meter="true">
                            <div class="mb-1">
                                <label>Password</label>
                                <div class="position-relative mb-3">
                                    <input class="form-control" type="password" name="password" autocomplete="off">

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
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Re-Type Password</label>
                            <input class="form-control" type="password" name="password_confirmation" autocomplete="off">
                            @if($errors->has('password_confirmation'))
                                <span id="err_password_confirmation" class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input id="nama" class="form-control" name="nama" value="{{ old('nama') }}">
                            @if($errors->has('nama'))
                                <span id="err_nama" class="text-danger">{{ $errors->first('nama') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Provinsi</label>
                            <select class="form-select form-control" data-control="select2" data-placeholder="Pilih Provinsi ..." id="provinsi" name="provinsi">
                                <option value=""></option>
                                @foreach($region as $item)
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
                            <label>Tempat Lahir</label>
                            <input id="tempat_lahir" class="form-control" name="tempat_lahir" value="{{ old('tempat_lahir') }}">
                            @if($errors->has('tempat_lahir'))
                                <span id="err_tempat_lahir" class="text-danger">{{ $errors->first('tempat_lahir') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input class="form-control" name="tanggal_lahir" id="tanggal_lahir" value="{{old('tanggal_lahir')}}">
                            @if($errors->has('tanggal_lahir'))
                                <span id="err_tanggal_lahir" class="text-danger">{{ $errors->first('tanggal_lahir') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-12">
                        <label>Upload Foto</label>
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
                </div>

                <div class="row mb-5">
                    <div class="col-md-12">
                        <label>Alamat</label>
                        <textarea id="alamat" class="form-control" name="alamat">{{ old('alamat') }}</textarea>
                        @if($errors->has('alamat'))
                            <span id="err_alamat" class="text-danger">{{ $errors->first('alamat') }}</span>
                        @endif
                    </div>
                </div>

                <div class="fv-row mb-7">

                </div>

                <div class="form-group mt-5 float-end">
                    <button type="submit" class="btn btn-primary"> Simpan </button>
                    <a href="{{ route('m-user.index') }}" class="btn btn-secondary"> Kembali </a>
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

</x-base-layout>