<x-base-layout>
    <style>
        tr td {
            border: 1px solid #ddd !important;
        }
    </style>

    <?php $title = $model->nama ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('t-event.index') }}" class="pe-3">Event</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">

        </div>
    </div>

    @section('scripts')

    @endsection

</x-base-layout>