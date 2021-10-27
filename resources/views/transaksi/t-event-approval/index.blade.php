<x-base-layout>
    <?php $title = 'Event Approval' ?>

    <link href="{{asset('demo1/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"> <i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home </a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm" id="main-layout">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light">{{ $title }}</h3>
        </div>

        <div class="card-body">

            @include('transaksi.t-event-approval.search')

            <div class="row">
                <div class="col-12">
                    <div id="main-table">
                        <table id="content-table" class="table table-hover table-rounded table-row-bordered border gy-5 gs-5" style="width:100%;">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Status</th>
                                <th>Nama</th>
                                <th>Nomor Lisensi</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Penyelenggara</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @section('scripts')
        <script src="{{asset('demo1/js/transaksi/t-event-approval/index.js')}}"></script>
        <script src="{{asset('demo1/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    @endsection

</x-base-layout>