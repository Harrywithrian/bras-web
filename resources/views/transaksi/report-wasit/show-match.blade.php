<x-base-layout>
    <style>
        tr td {
            border: 1px solid #ddd !important;
        }
    </style>

    <?php
    $title = 'Pertandingan ' . $model->nama;
    ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('report-wasit.index') }}" class="pe-3">Report Wasit</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('report-wasit.show', $user->id) }}" class="pe-3">Wasit : {{ $user->name }}</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">

            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#page_1">General</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#page_2">Play Calling</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#page_3">Game Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#page_4">Mechanical Court</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#page_5">Appearance</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#page_6">Evaluation</a>
                </li>
            </ul>

            <div class="card">
                <div class="card-body p-0">
                    <div class="tab-content" id="tab">
                        <div class="tab-pane fade show active" id="page_1" role="tabpanel" aria-labelledby="page_1"> @include('transaksi.report-wasit.page-1') </div>
                        <div class="tab-pane fade" id="page_2" role="tabpanel" aria-labelledby="page_2"> @include('transaksi.report-wasit.page-2') </div>
                        <div class="tab-pane fade" id="page_3" role="tabpanel" aria-labelledby="page_3"> @include('transaksi.report-wasit.page-3') </div>
                        <div class="tab-pane fade" id="page_4" role="tabpanel" aria-labelledby="page_4"> @include('transaksi.report-wasit.page-4') </div>
                        <div class="tab-pane fade" id="page_5" role="tabpanel" aria-labelledby="page_5"> @include('transaksi.report-wasit.page-5') </div>
                        <div class="tab-pane fade" id="page_6" role="tabpanel" aria-labelledby="page_6"> @include('transaksi.report-wasit.page-6') </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-base-layout>