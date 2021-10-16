@extends('base.base')

@section('content')
    <div class="d-flex flex-column flex-root">
        <!--begin::Authentication-->
        <div
            class="d-flex flex-column flex-column-fluid bgi-position-y-center position-x-center bgi-no-repeat bgi-size-cover bgi-attachment-fixed"
            style="background-image: url({{ asset(theme()->getIllustrationUrl('22.jpg')) }})">

            <!--begin::Content-->
            <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
                <!--begin::Logo-->
                <a href="{{ $theme->getPageUrl('index') }}" class="mb-5">
                    <img alt="Logo" src="{{ asset(theme()->getMediaUrlPath() . 'logos/logo-white.svg') }}" class="h-90px"/>
                </a>
                <!--end::Logo-->

                <!--begin::Wrapper-->
                <div class="{{ $wrapperClass ?? '' }} bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                    {{ $slot }}
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Content-->

            <!--begin::Footer-->
            <div class="d-flex flex-center flex-column-auto p-10">
                <!--begin::Links-->
                <div class="d-flex align-items-center fw-bold fs-6">
                    <span class="text-muted text-hover-primary px-2">{{ date("Y") }}&copy; Indonesia Basketball Referee</span>
                </div>
                <!--end::Links-->
            </div>
            <!--end::Footer-->
        </div>
        <!--end::Authentication-->
    </div>
@endsection
