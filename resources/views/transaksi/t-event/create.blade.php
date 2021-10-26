<x-base-layout>
    <?php
    $title = 'Tambah Event';
    $wasit = \App\Models\User::getWasit();
    ?>

    <link href="{{asset('demo1/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>

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
            <form method="post" action="{{ route('t-event.store') }}">
                @csrf

                <div class="row mb-5">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Event</label>
                            <input id="nama" class="form-control" name="nama" value="{{ (old('nama')) ? old('nama') : null }}">
                            @if($errors->has('nama'))
                                <span id="err_nama" class="text-danger">{{ $errors->first('nama') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nomor Lisensi</label>
                            <input id="no_lisensi" class="form-control" name="no_lisensi" value="{{ (old('no_lisensi')) ? old('no_lisensi') : null }}">
                            @if($errors->has('no_lisensi'))
                                <span id="err_no_lisensi" class="text-danger">{{ $errors->first('no_lisensi') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <label>Deskripsi</label>
                            <textarea id="deskripsi" class="form-control" name="deskripsi" value="{{ old('deskripsi') }}">{{ (old('deskripsi')) ? old('deskripsi') : null }}</textarea>
                            @if($errors->has('deskripsi'))
                                <span id="err_deskripsi" class="text-danger">{{ $errors->first('deskripsi') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-6">
                        <label for="tanggal_mulai">Tanggal Mulai</label>
                        <input class="form-control" placeholder="Pilih Tanggal ..." name="tanggal_mulai" id="tanggal_mulai" value="{{ (old('tanggal_mulai')) ? old('tanggal_mulai') : null }}">
                        @if($errors->has('tanggal_mulai'))
                            <span id="err_tanggal_mulai" class="text-danger">{{ $errors->first('tanggal_mulai') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_selesai">Tanggal Selesai</label>
                        <input class="form-control" placeholder="Pilih Tanggal ..." name="tanggal_selesai" id="tanggal_selesai" value="{{ (old('tanggal_selesai')) ? old('tanggal_selesai') : null }}">
                        @if($errors->has('tanggal_selesai'))
                            <span id="err_tanggal_selesai" class="text-danger">{{ $errors->first('tanggal_selesai') }}</span>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label for="tipe_event">Tipe Event</label>
                        <select class="form-select" id="tipe_event" name="tipe_event">
                            <option value=""></option>
                            <option value="0" {{ (old('tipe_event') == "0") ? 'selected' : '' }}>Normal</option>
                            <option value="1"  {{ (old('tipe_event') == "1") ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @if($errors->has('tipe_event'))
                            <span id="err_tipe_event" class="text-danger">{{ $errors->first('tipe_event') }}</span>
                        @endif
                    </div>
                </div>

                <br><br>

                <h1>Wasit</h1>

                <table id="table_wasit" class="table table-rounded table-row-bordered border gy-5 gs-5" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:80%">Wasit</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(old('nama_wasit'))
                            @foreach(old('nama_wasit') as $item_wasit)
                            <tr>
                                <td>
                                    <div>
                                        <select class="form-select form-select-lg form-select-solid select-wasit" data-control="select2" value="{{ $item_wasit }}" data-placeholder="Pilih Wasit ..." name="nama_wasit[]">
                                            <option></option>
                                            @if($wasit)
                                                @foreach($wasit as $list_wasit)
                                                    <option value="{{ $list_wasit['id'] }}" {{ ($list_wasit['id'] == $item_wasit) ? 'selected' : '' }}>{{ $list_wasit['text'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <a id="add-w" class="btn btn-success"> Tambah </a>
                                    <a id="remove-w" class="btn btn-danger"> Kurang </a>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                <div class="form-group mt-5 float-end">
                    <button type="submit" class="btn btn-primary"> Simpan </button>
                    <a href="{{ route('t-event.index') }}" class="btn btn-secondary"> Kembali </a>
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
        <script src="{{asset('demo1/plugins/custom/datatables/datatables.bundle.js')}}"></script>
        <script>
            $(document).ready( function() {
                @if(\Illuminate\Support\Facades\Session::has('error'))
                    var msg = JSON.parse('<?php echo json_encode(\Illuminate\Support\Facades\Session::get('error')); ?>');
                    toastr['error'](msg, 'Error', {
                        closeButton: true,
                        tapToDismiss: false,
                        rtl: false
                    });
                @endif

                var nama_wasit = @json($wasit);
                nama_wasit.unshift({ id : '', text : ''});

                var twasit = $('#table_wasit').DataTable({
                    bFilter: false,
                    processing: false,
                    serverSide: false,
                    searching: false,
                    paging: false,
                    info: false,
                    ordering: false,
                    @if(!old('nama_wasit'))
                    drawCallback: function (dataTable) {
                        console.log();
                        setTimeout( function () {
                            $("select.form-select.select-wasit").select2({
                                data:nama_wasit
                            });
                        }, 0);
                    }
                    @endif
                });

                twasit.on('click', '#add-w', function () {
                    initRowWasit();
                });

                twasit.on('click', '#remove-w', function () {
                    var twasit_item = $('#table_wasit tbody').children();
                    if (twasit_item.length > 1) {
                        twasit.row($(this).parents('tr')).remove().draw();
                    } else {
                        alert('Sudah mencapai batas maksimum.');
                    }
                });

                function initRowWasit(dataid) {
                    if (dataid) {
                        twasit.row.add([
                            '<select class="form-select form-select-lg form-select-solid select-wasit" data-value="'+dataid+'" data-control="select2" data-placeholder="Pilih Wasit ..." name="nama_wasit[]">',
                            '<a id="add-w" class="btn btn-success"> Tambah </a> <a id="remove-w" class="btn btn-danger"> Kurang </a>'
                        ]).draw(true);
                    } else {
                        twasit.row.add([
                            '<select class="form-select form-select-lg form-select-solid select-wasit" data-control="select2" data-placeholder="Pilih Wasit ..." name="nama_wasit[]">',
                            '<a id="add-w" class="btn btn-success"> Tambah </a> <a id="remove-w" class="btn btn-danger"> Kurang </a>'
                        ]).draw(true);
                    }
                }

                @if(!old('nama_wasit'))
                    initRowWasit();
                @endif
            });

            $("#tanggal_mulai").daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $("#tanggal_mulai").on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });

            $("#tanggal_selesai").daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $("#tanggal_selesai").on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });
        </script>
    @endsection

</x-base-layout>