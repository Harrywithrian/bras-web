<x-auth-layout>
    <!--begin::Forgot Password Form-->
    <form method="POST" action="{{ route('account.reset-change-password', $token) }}">
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
                Konfirmasi password anda.
            </div>
            <!--end::Link-->
        </div>
        <!--begin::Heading-->

        <!--begin::Input group-->
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

        <br>
        <!--end::Input group-->

        <!--begin::Actions-->
        <div class="d-flex flex-wrap justify-content-center pb-lg-0">
            <button type="submit" class="btn btn-primary me-4"> Reset </button>
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
