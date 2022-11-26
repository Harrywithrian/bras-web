<x-base-layout>
    <?php $title = 'List Wasit' ?>

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

            {{-- @include('master.wasit.search') --}}
            <div class="row">
                <div class="col-6">
                    <form id="search">
                        <div class="row">
                            <div class="col-md-6">
                                <select class="form-select form-control form-control-lg" data-placeholder="Pilih Lisensi ..." data-allow-clear="true" id="license" name="license">
                                    <option value=""></option>
                                    @if($license)
                                        @foreach($license as $item)
                                            <option value="{{ $item['id'] }}">{{ $item['license'] }}</option>
                                        @endforeach
                                    @endif
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
                                <th>Jenis Lisensi</th>
                                <th>Pengurus Provinsi</th>
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
        {{-- <script src="{{asset('demo1/js/master/wasit/index.js')}}"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.js"></script>
        @include('master.wasit.index-script')
        <script src="{{asset('demo1/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    @endsection

</x-base-layout>