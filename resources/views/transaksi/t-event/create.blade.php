<x-base-layout>
    <?php
    $title       = 'Tambah Event';
    $location    = \App\Models\Master\Location::getLocationList();
    $provinsi    = \App\Models\Master\Region::select('id', 'region')->whereNull('deletedon')->get()->toArray();
    $pengawas    = \App\Models\User::getPengawas();
    $koordinator = \App\Models\User::getKoordinator();
    $wasit       = \App\Models\User::getWasit();
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
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Nama Event</label>
                            <input id="nama" class="form-control" name="nama" value="{{ (old('nama')) ? old('nama') : null }}">
                            @if($errors->has('nama'))
                                <span id="err_nama" class="text-danger">{{ $errors->first('nama') }}</span>
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

                <div class="row mb-5">
                    <div class="col-md-12">
                        <label for="provinsi">Provinsi Event</label>
                        <select class="form-select" data-control="select2" multiple="" id="provinsi" name="provinsi[]">
                            <option value=""></option>
                            @foreach($provinsi as $item_provinsi)
                                <option value="{{ $item_provinsi['id'] }}" @if(old('provinsi')) {{in_array($item_provinsi['id'], old('provinsi')) ? 'selected' : null ;}} @endif>{{ $item_provinsi['region'] }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('provinsi'))
                            <span id="err_provinsi" class="text-danger">{{ $errors->first('provinsi') }}</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-12">
                        <label for="location">Lokasi Pertandingan</label>
                        <select class="form-select" data-control="select2" multiple="" id="location" name="location[]">
                            <option value=""></option>
                            @foreach($location as $item_location)
                                <option value="{{ $item_location['id'] }}" @if(old('location')) {{in_array($item_location['id'], old('location')) ? 'selected' : null ;}} @endif>{{ $item_location['text'] }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('location'))
                            <span id="err_locationt" class="text-danger">{{ $errors->first('location') }}</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-12">
                        <label for="tipe_event">Tipe Event</label>
                        <select class="form-select" id="tipe_event" name="tipe_event">
                            <option></option>
                            <option value="0" {{ (old('tipe_event') == "0") ? 'selected' : '' }}>Normal</option>
                            <option value="1"  {{ (old('tipe_event') == "1") ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @if($errors->has('tipe_event'))
                            <span id="err_tipe_event" class="text-danger">{{ $errors->first('tipe_event') }}</span>
                        @endif
                    </div>
                </div>

                <section class="card bg-primary" style="border-radius: 0; margin-bottom:-7px;">
                    <div class="card-header">
                        <h4 class="card-title" style="color: white;">Pengawas Pertandingan</h4>
                    </div>
                </section>
                <table id="table_pengawas" class="table table-row-bordered border gy-5 gs-5 pt-0" style="width:100%">
                    <tbody>
                    @if(old('nama_pengawas'))
                        @foreach(old('nama_pengawas') as $item_pengawas)
                            <tr>
                                <td width="80%">
                                    <select class="form-select form-select-lg select-pengawas" data-control="select2" data-placeholder="Pilih Pengawas ..." name="nama_pengawas[]">
                                        <option></option>
                                        @if($pengawas)
                                            @foreach($pengawas as $list_pengawas)
                                                <option value="{{ $list_pengawas['id'] }}" {{ ($list_pengawas['id'] == $item_pengawas) ? 'selected' : null ; }}>{{ $list_pengawas['text'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <a id="add-w" class="btn btn-icon btn-success"> <i class="bi bi-plus-lg"></i> </a>
                                    <a id="remove-w" class="btn btn-icon btn-danger"> <i class="bi bi-dash-lg"></i> </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td width="80%">
                                <select class="form-select form-select-lg select-pengawas" data-control="select2" data-placeholder="Pilih Pengawas ..." name="nama_pengawas[]">
                                    <option></option>
                                    @if($pengawas)
                                        @foreach($pengawas as $list_pengawas)
                                            <option value="{{ $list_pengawas['id'] }}">{{ $list_pengawas['text'] }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td>
                                <a id="add-w" class="btn btn-icon btn-success"> <i class="bi bi-plus-lg"></i> </a>
                                <a id="remove-w" class="btn btn-icon btn-danger"> <i class="bi bi-dash-lg"></i> </a>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>

                <section class="card bg-primary" style="border-radius: 0; margin-bottom:-7px;">
                    <div class="card-header">
                        <h4 class="card-title" style="color: white;">Koordinator Wasit</h4>
                    </div>
                </section>
                <table id="table_koordinator" class="table table-row-bordered border gy-5 gs-5 pt-0" style="width:100%">
                    <tbody>
                    @if(old('nama_koordinator'))
                        @foreach(old('nama_koordinator') as $item_koordinator)
                            <tr>
                                <td width="80%">
                                    <select class="form-select form-select-lg select-koordinator" data-control="select2" data-placeholder="Pilih Koordinator Wasit ..." name="nama_koordinator[]">
                                        <option></option>
                                        @if($koordinator)
                                            @foreach($koordinator as $list_koordinator)
                                                <option value="{{ $list_koordinator['id'] }}" {{ ($list_koordinator['id'] == $item_koordinator) ? 'selected' : null ; }}>{{ $list_koordinator['text'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <a id="add-w" class="btn btn-icon btn-success"> <i class="bi bi-plus-lg"></i> </a>
                                    <a id="remove-w" class="btn btn-icon btn-danger"> <i class="bi bi-dash-lg"></i> </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td width="80%">
                                <select class="form-select form-select-lg select-koordinator" data-control="select2" data-placeholder="Pilih Koordinator Wasit ..." name="nama_koordinator[]">
                                    <option></option>
                                    @if($koordinator)
                                        @foreach($koordinator as $list_koordinator)
                                            <option value="{{ $list_koordinator['id'] }}">{{ $list_koordinator['text'] }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td>
                                <a id="add-w" class="btn btn-icon btn-success"> <i class="bi bi-plus-lg"></i> </a>
                                <a id="remove-w" class="btn btn-icon btn-danger"> <i class="bi bi-dash-lg"></i> </a>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>

                <section class="card bg-primary" style="border-radius: 0; margin-bottom:-7px;">
                    <div class="card-header">
                        <h4 class="card-title" style="color: white;">Wasit Pertandingan</h4>
                    </div>
                </section>
                <table id="table_wasit" class="table table-row-bordered border gy-5 gs-5 pt-0" style="width:100%">
                    <tbody>
                        @if(old('nama_wasit'))
                            @foreach(old('nama_wasit') as $item_wasit)
                            <tr>
                                <td width="80%">
                                    <select class="form-select form-select-lg select-wasit" data-control="select2" data-placeholder="Pilih Wasit ..." name="nama_wasit[]">
                                        <option></option>
                                        @if($wasit)
                                            @foreach($wasit as $list_wasit)
                                                <option value="{{ $list_wasit['id'] }}" {{ ($list_wasit['id'] == $item_wasit) ? 'selected' : null ; }}>{{ $list_wasit['text'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <a id="add-w" class="btn btn-icon btn-success"> <i class="bi bi-plus-lg"></i> </a>
                                    <a id="remove-w" class="btn btn-icon btn-danger"> <i class="bi bi-dash-lg"></i> </a>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td width="80%">
                                    <select class="form-select form-select-lg select-wasit" data-control="select2" data-placeholder="Pilih Wasit ..." name="nama_wasit[]">
                                        <option></option>
                                        @if($wasit)
                                            @foreach($wasit as $list_wasit)
                                                <option value="{{ $list_wasit['id'] }}">{{ $list_wasit['text'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <a id="add-w" class="btn btn-icon btn-success"> <i class="bi bi-plus-lg"></i> </a>
                                    <a id="remove-w" class="btn btn-icon btn-danger"> <i class="bi bi-dash-lg"></i> </a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <section class="card bg-primary" style="border-radius: 0; margin-bottom:-7px;">
                    <div class="card-header">
                        <h4 class="card-title" style="color: white;">Surat Tembusan</h4>
                    </div>
                </section>
                <table id="table_tembusan" class="table table-row-bordered border gy-5 gs-5 pt-0" style="width:100%">
                    <tbody>
                        <tr>
                            <td style="padding-left:25px !important;">Tembusan</td>
                            <td style="padding-left:25px !important;">Email</td>
                        </tr>
                        <?php $k = 0; ?>
                        @if(old('nama_tembusan'))
                            @foreach(old('nama_tembusan') as $item_tembusan)
                                <tr>
                                    <td width="40%"><input class="form-control" name="nama_tembusan[]" value="{{ $item_tembusan }}"></td>
                                    <td width="40%"><input type="email" class="form-control" name="email_tembusan[]" value="{{ old('email_tembusan')[$k] }}"></td>
                                    <td>
                                        <a id="add-w" class="btn btn-icon btn-success"> <i class="bi bi-plus-lg"></i> </a>
                                        <a id="remove-w" class="btn btn-icon btn-danger"> <i class="bi bi-dash-lg"></i> </a>
                                    </td>
                                </tr>
                                <?php $k++ ?>
                            @endforeach
                        @else
                            <tr>
                                <td width="40%"><input class="form-control" name="nama_tembusan[]"></td>
                                <td width="40%"><input type="email" class="form-control" name="email_tembusan[]"></td>
                                <td>
                                    <a id="add-w" class="btn btn-icon btn-success"> <i class="bi bi-plus-lg"></i> </a>
                                    <a id="remove-w" class="btn btn-icon btn-danger"> <i class="bi bi-dash-lg"></i> </a>
                                </td>
                            </tr>
                        @endif

                    </tbody>
                </table>

                <section class="card bg-primary" style="border-radius: 0; margin-bottom:-7px;">
                    <div class="card-header">
                        <h4 class="card-title" style="color: white;">Contact Person</h4>
                    </div>
                </section>
                <table id="table_cp" class="table table-row-bordered border gy-5 gs-5 pt-0" style="width:100%">
                    <tbody>
                    <tr>
                        <td style="padding-left:25px !important;">Nama</td>
                        <td style="padding-left:25px !important;">Telepon</td>
                    </tr>
                    <?php $k = 0; ?>
                    @if(old('nama_cp'))
                        @foreach(old('nama_cp') as $item_cp)
                            <tr>
                                <td width="40%"><input class="form-control" name="nama_cp[]" value="{{ $item_cp }}"></td>
                                <td width="40%"><input class="form-control" name="telp_cp[]" value="{{ (!empty(old('telp_cp')[$k])) ? old('telp_cp')[$k] : '' }}"></td>
                                <td>
                                    <a id="add-w" class="btn btn-icon btn-success"> <i class="bi bi-plus-lg"></i> </a>
                                    <a id="remove-w" class="btn btn-icon btn-danger"> <i class="bi bi-dash-lg"></i> </a>
                                </td>
                            </tr>
                            <?php $k++ ?>
                        @endforeach
                    @else
                        <tr>
                            <td width="40%"><input class="form-control" name="nama_cp[]"></td>
                            <td width="40%"><input class="form-control" name="telp_cp[]"></td>
                            <td>
                                <a id="add-w" class="btn btn-icon btn-success"> <i class="bi bi-plus-lg"></i> </a>
                                <a id="remove-w" class="btn btn-icon btn-danger"> <i class="bi bi-dash-lg"></i> </a>
                            </td>
                        </tr>
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

                // PENGAWAS
                var tpengawas = $('#table_pengawas');
                tpengawas.on('click', '#add-w', function () {
                    initRowPengawas();
                });

                tpengawas.on('click', '#remove-w', function () {
                    var tpengawas_item = $('#table_pengawas tbody').children();
                    if (tpengawas_item.length > 1) {
                        $(this).parents('tr').remove();
                    } else {
                        alert('Sudah mencapai batas maksimum.');
                    }
                });

                function initRowPengawas() {
                    tpengawas.append(`<tr>
                                    <td width="80%">
                                        <select class="form-select form-select-lg select-pengawas next-pengawas" data-control="select2" data-placeholder="Pilih Pengawas ..." name="nama_pengawas[]">
                                            <option></option>
                                            @if($pengawas)
                                                @foreach($pengawas as $list_pengawas)
                                                    <option value="{{ $list_pengawas['id'] }}">{{ $list_pengawas['text'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                    <td>
                                        <a id="add-w" class="btn btn-icon btn-success"> <i class="bi bi-plus-lg"></i> </a>
                                        <a id="remove-w" class="btn btn-icon btn-danger"> <i class="bi bi-dash-lg"></i> </a>
                                    </td>
                                </tr>`);
                    $('select.form-select.select-pengawas.next-pengawas').select2({
                        tags: true,
                        placeholder: "Pilih Pengawas ...",
                        allowClear: false,
                        width: '100%'
                    });
                }
                // END PENGAWAS

                // KOORDINATOR
                var tkoordinator = $('#table_koordinator');
                tkoordinator.on('click', '#add-w', function () {
                    initRowKoordinator();
                });

                tkoordinator.on('click', '#remove-w', function () {
                    var tkoordinator_item = $('#table_koordinator tbody').children();
                    if (tkoordinator_item.length > 1) {
                        $(this).parents('tr').remove();
                    } else {
                        alert('Sudah mencapai batas maksimum.');
                    }
                });

                function initRowKoordinator() {
                    tkoordinator.append(`<tr>
                                    <td width="80%">
                                        <select class="form-select form-select-lg select-koordinator next-koordinator" data-control="select2" data-placeholder="Pilih Koordinator ..." name="nama_koordinator[]">
                                            <option></option>
                                            @if($koordinator)
                                                @foreach($koordinator as $list_koordinator)
                                                    <option value="{{ $list_koordinator['id'] }}">{{ $list_koordinator['text'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                    <td>
                                        <a id="add-w" class="btn btn-icon btn-success"> <i class="bi bi-plus-lg"></i> </a>
                                        <a id="remove-w" class="btn btn-icon btn-danger"> <i class="bi bi-dash-lg"></i> </a>
                                    </td>
                                </tr>`);
                    $('select.form-select.select-koordinator.next-koordinator').select2({
                        tags: true,
                        placeholder: "Pilih Koordinator ...",
                        allowClear: false,
                        width: '100%'
                    });
                }
                // END KOORDINATOR

                // WASIT
                var twasit = $('#table_wasit');
                twasit.on('click', '#add-w', function () {
                    initRowWasit();
                });

                twasit.on('click', '#remove-w', function () {
                    var twasit_item = $('#table_wasit tbody').children();
                    if (twasit_item.length > 1) {
                        $(this).parents('tr').remove();
                    } else {
                        alert('Sudah mencapai batas maksimum.');
                    }
                });

                function initRowWasit() {
                    twasit.append(`<tr>
                                    <td width="80%">
                                        <select class="form-select form-select-lg select-wasit next-wasit" data-control="select2" data-placeholder="Pilih Wasit ..." name="nama_wasit[]">
                                            <option></option>
                                            @if($wasit)
                                                @foreach($wasit as $list_wasit)
                                                    <option value="{{ $list_wasit['id'] }}">{{ $list_wasit['text'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                    <td>
                                        <a id="add-w" class="btn btn-icon btn-success"> <i class="bi bi-plus-lg"></i> </a>
                                        <a id="remove-w" class="btn btn-icon btn-danger"> <i class="bi bi-dash-lg"></i> </a>
                                    </td>
                                </tr>`);
                    $('select.form-select.select-wasit.next-wasit').select2({
                        tags: true,
                        placeholder: "Pilih Wasit ...",
                        allowClear: false,
                        width: '100%'
                    });
                }
                // END WASIT

                // TEMBUSAN
                var ttembusan = $('#table_tembusan');
                ttembusan.on('click', '#add-w', function () {
                    initRowTembusan();
                });

                ttembusan.on('click', '#remove-w', function () {
                    var ttembusan_item = $('#table_tembusan tbody').children();
                    if (ttembusan_item.length > 2) {
                        $(this).parents('tr').remove();
                    } else {
                        alert('Sudah mencapai batas maksimum.');
                    }
                });

                function initRowTembusan() {
                    ttembusan.append(`<tr>
                            <td width="40%"><input class="form-control" name="nama_tembusan[]"></td>
                            <td width="40%"><input type="email" class="form-control" name="email_tembusan[]"></td>
                            <td>
                                <a id="add-w" class="btn btn-icon btn-success"> <i class="bi bi-plus-lg"></i> </a>
                                <a id="remove-w" class="btn btn-icon btn-danger"> <i class="bi bi-dash-lg"></i> </a>
                            </td>
                        </tr>`);
                }
                // END TEMBUSAN

                // CP
                var tcp = $('#table_cp');
                tcp.on('click', '#add-w', function () {
                    initRowCp();
                });

                tcp.on('click', '#remove-w', function () {
                    var tcp_item = $('#table_cp tbody').children();
                    if (tcp_item.length > 2) {
                        $(this).parents('tr').remove();
                    } else {
                        alert('Sudah mencapai batas maksimum.');
                    }
                });

                function initRowCp() {
                    tcp.append(`<tr>
                            <td width="40%"><input class="form-control" name="nama_cp[]"></td>
                            <td width="40%"><input class="form-control" name="telp_cp[]"></td>
                            <td>
                                <a id="add-w" class="btn btn-icon btn-success"> <i class="bi bi-plus-lg"></i> </a>
                                <a id="remove-w" class="btn btn-icon btn-danger"> <i class="bi bi-dash-lg"></i> </a>
                            </td>
                        </tr>`);
                }
                // END CP
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