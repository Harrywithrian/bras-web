<x-base-layout>

    <?php
    $title = 'Wasit : ' . $user->name;
    ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('report-wasit.index') }}" class="pe-3">Report Wasit</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">

                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#profile">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#match-list">Pertandingan</a>
                        </li>
                    </ul>

                    <div class="card">
                        <div class="card-body p-0">
                            <div class="tab-content" id="tab">
                                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile"> @include('transaksi.report-wasit.profile') </div>
                                <div class="tab-pane fade" id="match-list" role="tabpanel" aria-labelledby="match-list"> @include('transaksi.report-wasit.index-match') </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-base-layout>