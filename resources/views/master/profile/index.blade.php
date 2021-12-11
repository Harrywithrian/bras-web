<x-base-layout>
    <link href="{{asset('demo1/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>

    <style>
        .responsive {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
    </style>

    <?php $title = 'Profile' ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-2">
                    <img class="responsive" src="{{ url('storage/'.$foto->path) }}">
                </div>

                <div class="col-10">
                    <h2>{{ $user->name }}</h2>
                    <h5 class="text-gray-600" style="margin-top:-10px; margin-bottom:10px;">{{ $user->email }}</h5>
                    <span class='w-130px badge badge-primary me-4'>{{ $provinsi->region }}</span>
                </div>
            </div>
        </div>
    </div>

    <br>

    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#profile">Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#match">Pertandingan</a>
        </li>
    </ul>
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="tab-content" id="tab">
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="page_1"> @include('master.profile.general') </div>
                <div class="tab-pane fade" id="match" role="tabpanel" aria-labelledby="page_2"> @include('master.profile.match') </div>
            </div>

        </div>
    </div>

    @section('scripts')
        <script src="{{asset('demo1/js/master/profile/index-match.js')}}"></script>
        <script src="{{asset('demo1/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    @endsection

</x-base-layout>