<x-base-layout>
    <?php $title = 'Template Appearance' ?>

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

            <div class="row">
                <div class="col-6">
                    <form id="search">
                        <div class="row">
                            <div class="col-md-6">
                                <select class="form-select form-control form-control-lg" data-placeholder="Pilih Level ..." data-allow-clear="true" id="level" name="level">
                                    <option value=""></option>
                                    <option value="1">Parent</option>
                                    <option value="2">Child</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-5">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="input-search" name="input-search" value="" placeholder="Cari ...">
                                    <button type="button" id="cari" class="btn btn-primary" onclick="search(event)"> <i class="bi bi-search fs-3"></i> </button>
                                    <button type="button" class="btn btn-warning" onclick="resets(event)"> <i class="bi bi-arrow-clockwise fs-3"></i> </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="col-6">
                    <div class="float-end mb-5">
                        <a class="btn btn-xs btn-primary" href="{{ route('m-appearance.create-header') }}"> Tambah Header </a>
                        <a class="btn btn-xs btn-success" href="{{ route('m-appearance.create-content') }}"> Tambah Content </a>
                    </div>
                </div>
            </div>

            @if($persentase == 100)
                <div class="alert alert-success">
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-dark">Total Persentase : {{ $persentase }}%</h4>
                        <span>{{ $message }}</span>
                    </div>
                </div>
            @elseif($persentase < 100)
                <div class="alert alert-warning">
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-dark">Total Persentase : {{ $persentase }}%</h4>
                        <span>{{ $message }}</span>
                    </div>
                </div>
            @else
                <div class="alert alert-danger">
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-dark">Total Persentase : {{ $persentase }}%</h4>
                        <span>{{ $message }}</span>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="float-start mb-5">
                        <a class="btn btn-xs btn-secondary" href="{{ route('m-appearance.preview') }}"> Preview </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div id="main-table">
                        <table id="content-table" class="table table-hover table-rounded table-row-bordered border gy-5 gs-5" style="width:100%;">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Nama</th>
                                <th>Level</th>
                                <th>Parent</th>
                                <th>Presentase</th>
                                <th>Order By</th>
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
    @include('master.m-appearance.index-script')
        <script src="{{asset('demo1/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    @endsection

</x-base-layout>