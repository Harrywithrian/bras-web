@extends('base.base')

@section('content')
    <div class="d-flex flex-column flex-root">
        <!--begin::Authentication-->
        <div
            class="d-flex flex-column flex-column-fluid bgi-position-y-center position-x-center bgi-no-repeat bgi-size-cover bgi-attachment-fixed"
            style="background-image: url({{ asset(theme()->getIllustrationUrl('25.jpg')) }})">

            <!--begin::Content-->
            <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
                <!--begin::Wrapper-->
                <div class="{{ $wrapperClass ?? '' }} rounded shadow-sm p-10 p-lg-15 mx-auto" style="box-shadow: 1px 21px 15px 13px rgba(0,0,0,0.4) !important;
-webkit-box-shadow: 1px 21px 15px 13px rgba(0,0,0,0.4) !important;
-moz-box-shadow: 1px 21px 15px 13px rgba(0,0,0,0.4) !important; background-color:white !important">
                    {{ $slot }}
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Content-->

            <!--begin::Footer-->
            <div class="d-flex flex-center flex-column-auto p-10">
                <!--begin::Links-->
                <div class="d-flex align-items-center fw-bold fs-6">
                    <span class="text-hover-danger px-2">{{ date("Y") }}&copy; Indonesia Basketball Referee</span>
                </div>
                <!--end::Links-->
            </div>
            <!--end::Footer-->
        </div>
        <!--end::Authentication-->
    </div>
@endsection
