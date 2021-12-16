<x-base-layout>
    <?php
        $title = 'Catatan Evaluasi';
    ?>

    <link href="{{asset('demo1/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"> <i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home </a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.index-event') }}" class="pe-3"> List Event </a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.index', $event->id) }}" class="pe-3"> Pertandingan Event {{ $event->nama }} </a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.show', $model->id) }}" class="pe-3"> Pertandingan : {{ $model->nama }} </a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm" id="main-layout">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light">{{ $title }}</h3>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('t-match.submit-notes-evaluation', $id) }}">
                @csrf
                
                <?php $oldEval1 = empty(old('evaluasi_1')) ? old('evaluasi_1') : $eval1->notes ; ?>
                <div class="row mb-5">
                    <div class="col-md-12">
                        <label>Evaluasi : {{ $wst1->name }}</label>
                        <textarea id="evaluasi_1" class="form-control" name="evaluasi_1">{{ $oldEval1 }}</textarea>
                        @if($errors->has('evaluasi_1'))
                            <span id="err_evaluasi_1" class="text-danger">{{ $errors->first('evaluasi_1') }}</span>
                        @endif
                    </div>
                </div>

                <?php $oldEval2 = empty(old('evaluasi_2')) ? old('evaluasi_2') : $eval2->notes ; ?>
                <div class="row mb-5">
                    <div class="col-md-12">
                        <label>Evaluasi : {{ $wst2->name }}</label>
                        <textarea id="evaluasi_2" class="form-control" name="evaluasi_2">{{ $oldEval2 }}</textarea>
                        @if($errors->has('evaluasi_2'))
                            <span id="err_evaluasi_2" class="text-danger">{{ $errors->first('evaluasi_2') }}</span>
                        @endif
                    </div>
                </div>

                <?php $oldEval3 = empty(old('evaluasi_3')) ? old('evaluasi_3') : $eval3->notes ; ?>
                <div class="row">
                    <div class="col-md-12">
                        <label>Evaluasi : {{ $wst3->name }}</label>
                        <textarea id="evaluasi_3" class="form-control" name="evaluasi_3">{{ $oldEval3 }}</textarea>
                        @if($errors->has('evaluasi_3'))
                            <span id="err_evaluasi_3" class="text-danger">{{ $errors->first('evaluasi_3') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group mt-5 float-end">
                    <button type="submit" class="btn btn-primary"> Simpan </button>
                    <a href="{{ route('t-match.show', $id) }}" class="btn btn-secondary"> Kembali </a>
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
        <script>
            $(document).ready( function() {
                @if(\Illuminate\Support\Facades\Session::has('success'))
                    var msg = JSON.parse('<?php echo json_encode(\Illuminate\Support\Facades\Session::get('success')); ?>');
                    toastr['success'](msg, 'Success', {
                        closeButton: true,
                        tapToDismiss: false,
                        rtl: false
                    });
                @endif

                @if(\Illuminate\Support\Facades\Session::has('error'))
                    var msg = JSON.parse('<?php echo json_encode(\Illuminate\Support\Facades\Session::get('error')); ?>');
                    toastr['error'](msg, 'Error', {
                        closeButton: true,
                        tapToDismiss: false,
                        rtl: false
                    });
                @endif
            });
        </script>
    @endsection

</x-base-layout>