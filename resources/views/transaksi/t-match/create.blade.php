<x-base-layout>
    <?php
    $title = 'Tambah Pertandingan';

    $qLocation = \App\Models\Transaksi\TEventLocation::select(['m_location.id', 'm_location.nama'])
        ->leftJoin('m_location', 't_event_location.id_m_location', '=', 'm_location.id')
        ->where('t_event_location.id_t_event', '=', $event->id)
        ->whereNull('m_location.deletedby')
        ->get()->toArray();

    $qParticipant = \App\Models\Transaksi\TEventParticipant::select(['users.id', 'users.name'])
        ->leftJoin('users', 't_event_participant.user', '=', 'users.id')
        ->where('t_event_participant.role', '=', '8')
        ->where('t_event_participant.id_t_event', '=', $event->id)
        ->get()->toArray();

    ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.index-event') }}" class="pe-3"> List Event </a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.index', $event->id) }}" class="pe-3"> Pertandingan Event {{ $event->nama }} </a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('t-match.store', $event->id) }}">
                @csrf

                <div class="row mb-5">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Event</label>
                            <input id="event" class="form-control" name="event" value="{{ (old('event')) ? old('event') : $event->nama }}" readonly>
                            @if($errors->has('event'))
                                <span id="err_event" class="text-danger">{{ $errors->first('event') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Lokasi Pertandingan</label>
                            <select id="lokasi" class="form-select form-select-lg" data-control="select2" data-placeholder="Pilih Lokasi ..." name="lokasi">
                                <option></option>
                                @if($qLocation)
                                    @foreach($qLocation as $itemLocation)
                                        <option value="{{ $itemLocation['id'] }}" {{ ($itemLocation['id'] == old('lokasi')) ? 'selected' : null ; }}>{{ $itemLocation['nama'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if($errors->has('lokasi'))
                                <span id="err_lokasi" class="text-danger">{{ $errors->first('lokasi') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Pertandingan</label>
                            <input id="nama" class="form-control" name="nama" value="{{ (old('nama')) ? old('nama') : null }}">
                            @if($errors->has('nama'))
                                <span id="err_nama" class="text-danger">{{ $errors->first('nama') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Waktu Pertandingan</label>
                            <input id="waktu" class="form-control" name="waktu" value="{{ (old('waktu')) ? old('waktu') : null }}">
                            @if($errors->has('waktu'))
                                <span id="err_waktu" class="text-danger">{{ $errors->first('waktu') }}</span>
                            @endif
                        </div>
                    </div>
                </div>


                <div class="row mb-5">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Crew Chief</label>
                            <select id="wasit1" class="form-select form-select-lg" data-control="select2" data-placeholder="Pilih Crew Chief ..." name="wasit1">
                                <option></option>
                                @if($qParticipant)
                                    @foreach($qParticipant as $itemP)
                                        <option value="{{ $itemP['id'] }}" {{ ($itemP['id'] == old('wasit1')) ? 'selected' : null ; }}>{{ $itemP['name'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if($errors->has('wasit1'))
                                <span id="err_wasit1" class="text-danger">{{ $errors->first('wasit1') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Official 1</label>
                            <select id="wasit2" class="form-select form-select-lg" data-control="select2" data-placeholder="Pilih Official 1 ..." name="wasit2">
                                <option></option>
                                @if($qParticipant)
                                    @foreach($qParticipant as $itemP)
                                        <option value="{{ $itemP['id'] }}" {{ ($itemP['id'] == old('wasit2')) ? 'selected' : null ; }}>{{ $itemP['name'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if($errors->has('wasit2'))
                                <span id="err_wasit2" class="text-danger">{{ $errors->first('wasit2') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Official 1</label>
                            <select id="wasit3" class="form-select form-select-lg" data-control="select2" data-placeholder="Pilih Official 2 ..." name="wasit3">
                                <option></option>
                                @if($qParticipant)
                                    @foreach($qParticipant as $itemP)
                                        <option value="{{ $itemP['id'] }}" {{ ($itemP['id'] == old('wasit3')) ? 'selected' : null ; }}>{{ $itemP['name'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if($errors->has('wasit3'))
                                <span id="err_wasit3" class="text-danger">{{ $errors->first('wasit3') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group mt-5 float-end">
                    <button type="submit" class="btn btn-primary"> Simpan </button>
                    <a href="{{ route('t-match.index', $event->id) }}" class="btn btn-secondary"> Kembali </a>
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

                $("#waktu").daterangepicker({
                    timePicker: true,
                    singleDatePicker: true,
                    showDropdowns: true,
                    autoUpdateInput: false,
                    autoApply: true,
                    drops: "down",
                    minDate: '{{ (date("d-m-Y") > $event->tanggal_mulai) ? date('m/d/Y') : date('m/d/Y', strtotime($event->tanggal_mulai)) }}',
                    maxDate: '{{ date('m/d/Y', strtotime($event->tanggal_selesai)) }}',
                    locale: {
                        cancelLabel: 'Clear'
                    }
                });

                $("#waktu").on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD H:mm'));
                });
            });
        </script>
    @endsection

</x-base-layout>