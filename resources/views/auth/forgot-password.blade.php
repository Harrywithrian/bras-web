<x-auth-layout>
    <!--begin::Forgot Password Form-->
    <form method="POST" action="{{ route('account.send-forgot-password') }}">
    @csrf

        <!--begin::Heading-->
        <div class="text-center mb-10">
            <!--begin::Title-->
            <h1 class="text-dark mb-3">
                Reset Password
            </h1>
            <!--end::Title-->

            <!--begin::Link-->
            <div class="text-gray-400 fw-bold fs-4">
                Silahkan isi email anda untuk melakukan reset password.
            </div>
            <!--end::Link-->
        </div>
        <!--begin::Heading-->

        <!--begin::Input group-->
        <div class="form-group">
            <label class="form-label fw-bolder text-gray-900 fs-6">Email</label>
            <input id="email" class="form-control" name="email" value="{{ old('email') }}" type="email">
            @if($errors->has('email'))
                <span id="err_email" class="text-danger">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <br>
        <!--end::Input group-->

        <!--begin::Actions-->
        <div class="d-flex flex-wrap justify-content-center pb-lg-0">
            <button type="submit" class="btn btn-primary me-4"> Submit </button>
            <a href="{{ route('login') }}" class="btn btn-secondary"> Kembali </a>
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Forgot Password Form-->

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
