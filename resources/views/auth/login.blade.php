<x-auth-layout>

    <!--begin::Logo-->
    <center>
        <a href="{{ $theme->getPageUrl('index') }}" class="mb-5">
            <img alt="Logo" src="{{ asset(theme()->getMediaUrlPath() . 'logos/logo.png') }}" class="h-90px"/>
        </a>
    </center>
    <!--end::Logo-->

    <div class="text-center mt-5 mb-10">
        {{--<h1 class="text-dark">--}}
            {{--Indonesia Basketball Referee--}}
        {{--</h1>--}}
        <h5>Silahkan Login Menggunakan Username Yang Terdaftar</h5>
    </div>

    <form method="POST" action="{{ theme()->getPageUrl('login') }}" class="form w-100" novalidate="novalidate" id="kt_sign_in_form">
    @csrf
        <div class="fv-row mb-10">
            <label class="form-label fs-6 fw-bolder text-dark">Username</label>
            <input class="form-control form-control-lg form-control-solid" type="text" name="username" autocomplete="off" value="{{ old('username') }}" required autofocus/>
            @if($errors->has('username'))
                <span id="err_alias" class="text-danger">{{ $errors->first('username') }}</span>
            @endif
        </div>

        <div class="fv-row mb-10">
            <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
            <input class="form-control form-control-lg form-control-solid" type="password" name="password" autocomplete="off" required/>
            @if($errors->has('password'))
                <span id="err_alias" class="text-danger">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <div class="fv-row mb-3">
            <div class="text-muted">
                <a href="{{ route('account.forgot-password') }}">Lupa Password?</a>
            </div>
            {{-- <label class="form-check form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" name="remember"/>
                <span class="form-check-label fw-bold text-gray-700 fs-6">{{ __('Remember me') }}
            </span>
            </label> --}}
        </div>

        <div class="text-center">
            <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">Login</button>
            <center>
                <div class="text-muted">
                    Belum memiliki akun? <a href="{{ route('register') }}">Daftar disini.</a>
                </div>
            </center>
        </div>
    </form>

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

</x-auth-layout>
