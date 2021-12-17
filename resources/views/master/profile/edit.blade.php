<x-base-layout>
    <style>
        .responsive {
            width: 100%;
            height: 200px;
            width: auto;
            border-radius: 10px;
        }
    </style>

    <?php
    $title = 'Profile Settings';
    ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#profile">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#password">Password</a>
                </li>
            </ul>

            <div class="tab-content" id="tab">
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="page_1"> 
                    <form method="post" action="{{ route('profile.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        <h5>Account</h5>
                        <hr>
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input id="user" class="form-control form-control-solid" name="user" value="{{ (empty(old('user'))) ? $user->username : old('user') }}" readOnly>
                                    @if($errors->has('user'))
                                        <span id="err_user" class="text-danger">{{ $errors->first('user') }}</span>
                                    @endif
                                </div>
                            </div>
        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input id="email" class="form-control form-control-solid" name="email" value="{{ (empty(old('email'))) ? $user->email : old('email') }}" readOnly>
                                    @if($errors->has('email'))
                                        <span id="err_email" class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
        
                        <h5>General</h5>
                        <hr>
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input id="nama" class="form-control form-control-solid" name="nama" value="{{ (empty(old('nama'))) ? $user->name : old('nama') }}" readOnly>
                                    @if($errors->has('nama'))
                                        <span id="err_nama" class="text-danger">{{ $errors->first('nama') }}</span>
                                    @endif
                                </div>
                            </div>
        
                            <?php $oldRegion = (empty(old('provinsi'))) ? $detail->id_m_region : old('provinsi') ; ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pengurus Provinsi</label>
                                    <select class="form-select form-control" data-control="select2" data-placeholder="Pilih Provinsi ..." id="provinsi" name="provinsi">
                                        <option value=""></option>
                                        @foreach($region as $item)
                                            <option value="{{ $item['id'] }}" {{($oldRegion == $item['id']) ? 'selected' : '';}}>{{ $item['region'] }}</option>
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
                                    <label>Nomor Lisensi</label>
                                    <input id="no_lisensi" class="form-control form-control-solid" name="no_lisensi" value="{{ (empty(old('no_lisensi'))) ? $detail->no_lisensi : old('no_lisensi') }}" readOnly>
                                    @if($errors->has('no_lisensi'))
                                        <span id="err_no_lisensi" class="text-danger">{{ $errors->first('no_lisensi') }}</span>
                                    @endif
                                </div>
                            </div>
        
                            <?php $oldLicense = (empty(old('jenis_lisensi'))) ? $detail->id_m_lisensi : old('jenis_lisensi') ; ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Lisensi</label>
                                    <select class="form-select form-control" data-control="select2" data-placeholder="Pilih Lisensi ..." id="jenis_lisensi" name="jenis_lisensi">
                                        <option value=""></option>
                                        @foreach($license as $item)
                                            <option value="{{ $item['id'] }}" {{($oldLicense == $item['id']) ? 'selected' : '';}}>{{ $item['license'] }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('jenis_lisensi'))
                                        <span id="err_jenis_lisensi" class="text-danger">{{ $errors->first('jenis_lisensi') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
        
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tempat Lahir</label>
                                    <input id="tempat_lahir" class="form-control form-control-solid" name="tempat_lahir" value="{{ (empty(old('tempat_lahir'))) ? $detail->tempat_lahir : old('tempat_lahir') }}" readOnly>
                                    @if($errors->has('tempat_lahir'))
                                        <span id="err_tempat_lahir" class="text-danger">{{ $errors->first('tempat_lahir') }}</span>
                                    @endif
                                </div>
                            </div>
        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Lahir</label>
                                    <input id="tanggal_lahir" class="form-control form-control-solid" name="tanggal_lahir" value="{{ (empty(old('tanggal_lahir'))) ? $detail->tanggal_lahir : old('tanggal_lahir') }}" readOnly>
                                    @if($errors->has('tanggal_lahir'))
                                        <span id="err_tanggal_lahir" class="text-danger">{{ $errors->first('tanggal_lahir') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
        
                        <div class="row mb-5">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea id="alamat" class="form-control" name="alamat">{{ (empty(old('alamat'))) ? $detail->alamat : old('alamat') }}</textarea>
                                    @if($errors->has('alamat'))
                                        <span id="err_alamat" class="text-danger">{{ $errors->first('alamat') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
        
                        <div class="row mb-5">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Foto Lama</label>
                                    <br>
                                    <img class="responsive" src="{{ url('storage/'.$foto->path) }}">
                                    <br>
        
                                    <label style="margin-top:10px;">Ubah Foto</label>
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
                        </div>
        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Ubah File</label>
                                    <div style="border: solid #EFF2F5 1px; padding:5px; background-color: #EFF2F5; border-radius:5px;">
                                        <label style="margin-bottom:5px;">File Sebelumnya : <a href="{{ route('profile.download-lisensi', $detail->id_t_file_lisensi) }}"> {{ $file->name }} </a></label>
                                        <br>
                                        <input type="file" name="upload_lisensi" class="custom-file-input" id="upload_lisensi" value="{{ old('upload_lisensi') }}">
                                    </div>
                                    @if($errors->has('upload_lisensi'))
                                        <span id="err_upload_lisensi" class="text-danger">{{ $errors->first('upload_lisensi') }}</span>
                                    @endif
                                    <div class="text-muted">
                                        Maksimal 10MB dan gunakan format PDF.
                                    </div>
                                </div>
                            </div>
                        </div>
        
                        <div class="form-group float-end">
                            <button type="submit" class="btn btn-primary"> Simpan </button>
                            <a href="{{ route('index') }}" class="btn btn-secondary"> Kembali </a>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="page_2"> 
                    <form method="post" action="{{ route('profile.update-password', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6" data-kt-password-meter="true">
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

                            <div class="col-md-6">
                                <label class="form-label fw-bolder text-dark fs-6">Re-type Password</label>
                                <input class="form-control form-control-lg form-control-solid" type="password" name="password_confirmation" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group float-end">
                            <button type="submit" class="btn btn-primary"> Ubah Password </button>
                            <a href="{{ route('index') }}" class="btn btn-secondary"> Kembali </a>
                        </div>
                    </form>
                </div>
            </div>
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