<x-base-layout>
    <?php $title = 'Approval User' ?>

    <link href="{{asset('demo1/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"> <i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home </a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm" id="main-layout">
        <div class="card-header" style="background-color:#181C32;">
            <h3 class="card-title text-light">{{ $title }}</h3>
        </div>

        <div class="card-body">

            @include('transaksi.t-approval.search')

            <div class="row">
                <div class="col-12">
                    <div id="main-table">
                        <table id="content-table" class="table table-hover table-rounded table-row-bordered border gy-5 gs-5" style="width:100%;">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Status</th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Nomor Lisensi</th>
                                <th>Jenis Lisensi</th>
                                <th>Jenis Daftar</th>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.js"></script>
        @include('transaksi.t-approval.index-script')
        <script src="{{asset('demo1/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    @endsection

</x-base-layout>