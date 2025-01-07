<x-base-layout>
    <?php
    $title = 'Tambah Lisensi ' . $model->name;
    $jenisLisensi = (old('jenis_lisensi')) ? old('jenis_lisensi') : $modelLisensi->id_m_license;
    ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('m-user.index') }}" class="pe-3">Manajemen User</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('m-user.show', $model->id) }}" class="pe-3">{{ $model->name }}</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#181C32;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('m-user.update-lisensi', ['userid' => $model->id, 'id' => $modelLisensi->id]) }}" enctype="multipart/form-data">
                @csrf

                <div class="row mb-5">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nomor Lisensi</label>
                            <input id="nomor_lisensi" class="form-control" name="nomor_lisensi" value="{{ (old('nomor_lisensi')) ? old('nomor_lisensi') : $modelLisensi->nomor_lisensi }}">
                            @if($errors->has('nomor_lisensi'))
                                <span id="err_nomor_lisensi" class="text-danger">{{ $errors->first('nomor_lisensi') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Jenis Lisensi</label>
                            <select class="form-select form-control" data-placeholder="Pilih Lisensi ..." id="jenis_lisensi" name="jenis_lisensi">
                                <option value=""></option>
                                @foreach($lisensi as $item)
                                    <option value="{{ $item['id'] }}" {{($jenisLisensi == $item['id']) ? 'selected' : '';}}>@if($item['type'] == 1) Wasit - @else Pengawas - @endif {{ $item['license'] }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('jenis_lisensi'))
                                <span id="err_jenis_lisensi" class="text-danger">{{ $errors->first('jenis_lisensi') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Tanggal Aktif</label>
                            <input class="form-control" name="tanggal_aktif" id="tanggal_aktif" value="{{ (old('tanggal_aktif')) ? old('tanggal_aktif') : $modelLisensi->start_date }}">
                            @if($errors->has('tanggal_aktif'))
                                <span id="err_tanggal_aktif" class="text-danger">{{ $errors->first('tanggal_aktif') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                    
                <div class="row mb-5">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Tanggal Expired</label>
                            <input class="form-control" name="tanggal_expired" id="tanggal_expired" value="{{ (old('tanggal_expired')) ? old('tanggal_expired') : $modelLisensi->end_date }}">
                            @if($errors->has('tanggal_expired'))
                                <span id="err_tanggal_expired" class="text-danger">{{ $errors->first('tanggal_expired') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-12">
                        <label>Upload Lisensi</label>
                        <div style="border: solid #EFF2F5 1px; padding:5px; background-color: #EFF2F5; border-radius:5px;">
                            <input type="file" name="upload_lisensi" class="custom-file-input" id="upload_lisensi" value="{{ old('upload_lisensi') }}">
                        </div>
                        @if($errors->has('upload_lisensi'))
                            <span id="err_upload_lisensi" class="text-danger">{{ $errors->first('upload_lisensi') }}</span>
                        @endif
                        <div class="text-muted">
                            Dokumen lisensi harus berupa file pdf dengan maksimal ukuran file adalah 2MB.
                        </div>
                    </div>
                </div>

                <div class="fv-row mb-7">

                </div>

                <div class="form-group mt-5 float-end">
                    <button type="submit" class="btn btn-primary"> Simpan </button>
                    <a href="{{ route('m-user.show', $model->id) }}" class="btn btn-secondary"> Kembali </a>
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
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
            });

            $("#jenis_lisensi").select2({
                // the following code is used to disable x-scrollbar when click in select input and
                // take 100% width in responsive also
                placeholder: "Pilih ...",
                dropdownAutoWidth: true,
                width: '100%'
            });

            $("#tanggal_aktif").daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                locale: {
                    cancelLabel: 'Clear',
                    format: "YYYY-MM-DD",
                },
            });

            $("#tanggal_aktif").on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });

            $('#tanggal_aktif').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('')
                picker.setStartDate({})
                picker.setEndDate({})
            });

            $("#tanggal_expired").daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                locale: {
                    cancelLabel: 'Clear',
                    format: "YYYY-MM-DD",
                },
            });

            $("#tanggal_expired").on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });

            $('#tanggal_expired').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('')
                picker.setStartDate({})
                picker.setEndDate({})
            });
        </script>
    @endsection

</x-base-layout>