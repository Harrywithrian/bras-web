<style>
    #template-preview {
        border: 1px solid #ddd;
    }
    #template-preview td, #template-preview th {
        border: 1px solid #ddd;
        padding: 8px;
    }
</style>

<x-base-layout>
    <?php
        $title = 'Edit Debrief : Pertandingan' . $match->nama;
    ?>

    <link href="{{asset('demo1/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"> <i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home </a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.index-event') }}" class="pe-3"> List Event </a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.show', $match->id) }}" class="pe-3"> Pertandingan {{ $match->nama }} </a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('debrief.index', $match->id) }}" class="pe-3"> Debrief </a></li>
        <li class="breadcrumb-item px-3 text-muted">Edit Debrief</li>
    </ol>

    <div class="card shadow-sm" id="main-layout">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light">{{ $title }}</h3>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('debrief.update', $playCalling->id) }}">
                @csrf
                <div>
                    <div class="d-flex flex-direction-column justify-content-center align-item-center">
                        <h2> Quarter {{ $playCalling->quarter }} </h2>
                    </div>
                    <div class="d-flex flex-direction-column justify-content-center align-item-center">
                        <label class="btn btn-outline btn-outline-dashed btn-outline-default py-3 px-3 d-flex align-items-center mb-5">
                            <span class="fs-5tx"> {{ $playCalling->time }} </span>
                        </label>
                    </div>
                </div>

                <!-- referee -->
                <div class="d-flex flex-column flex-lg-row" style="gap: 20px;">
                    <?php 
                        $referee = \App\Models\Transaksi\TMatchReferee::select('t_match_referee.wasit', 't_match_referee.posisi', 'users.name', 't_file.path')
                        ->leftJoin('user_infos', 't_match_referee.wasit', '=', 'user_infos.user_id')
                        ->leftJoin('users', 'users.id', '=', 'user_infos.user_id')
                        ->leftJoin('t_file', 't_file.id', '=', 'user_infos.id_t_file_foto')
                        ->where('id_t_match', '=', $match->id)->where('wasit', '=', $playCalling->referee)->get()->toArray();
                    ?>

                    @foreach($referee as $item)
                    <div style="flex-basis: 0; flex-grow: 1;">
                        <!--begin::Option-->
                        <input type="radio" class="btn-check" name="referee" value="{{ $item['wasit'] }}" data-value='{{ $item['wasit'] }}' checked/>
                        <label class="btn btn-outline btn-outline-dashed btn-outline-default py-3 px-3 d-flex align-items-center mb-5">
                            <div class="image-input image-input-circle me-5" data-kt-image-input="true" style="background-image: url({{ url('storage/'.$item['path']) }})">
                            <div class="image-input-wrapper w-100px h-100px" style="background-image: url({{ url('storage/'.$item['path']) }})"></div>
                            </div>

                            <span class="d-block fw-bold text-start">
                            <span class="text-dark fw-bolder d-block fs-3 test">{{ $item['name'] }}</span>
                            <span class="text-muted fw-bold fs-6">
                                {{ $item['posisi'] }}
                            </span>
                            </span>
                        </label>
                        <!--end::Option-->
                    </div>
                    @endforeach

                </div>

                <div class="d-flex flex-column flex-lg-row" style="gap: 20px;">
                    <div class="flex-fill flex-column">
                        <h6>Call Analysis</h6>
                        @if($errors->has('call_analysis'))
                            <span id="err_wasit1" class="text-danger">{{ $errors->first('call_analysis') }}</span>
                        @endif
                        <?php $ca = \App\Models\Master\CallAnalysis::where('status', '=', 1)->where('id', '!=', 4)->whereNull('deletedon')->get()->toArray(); ?>
                        <div data-kt-buttons="true">
                
                            @foreach($ca as $item)
                            <label class="btn btn-outline btn-outline-dashed d-flex flex-stack text-start p-3 mb-3 active">
                            <div class="d-flex align-items-center">
                                <div class="form-check form-check-custom form-check-solid form-check-primary me-2 form-check-sm">
                                <input class="form-check-input" type="radio" name="call_analysis" value="{{ $item['id'] }}" data-value="{{ $item['id'] }}" @if($item['id'] == $playCalling->call_analysis_id) checked @endif/>
                                </div>
                
                                <div class="flex-grow-1">
                                <h6 class="d-flex align-items-center flex-wrap me-0 m-0">
                                    {{ $item['call_analysis'] }}
                                </h6>
                                </div>
                            </div>
                            </label>
                            @endforeach
                
                        </div>
                    </div>

                    <div class="flex-fill flex-column">
                        <h6>Position</h6>
                        <?php $pos = ['1' => 'Trail', '2' => 'Center', '3' => 'Lead']; ?>
                        <div data-kt-buttons="true">
                
                            @foreach($pos as $key => $item)
                            <label class="btn btn-outline btn-outline-dashed d-flex flex-stack text-start p-3 mb-3 active">
                            <div class="d-flex align-items-center">
                                <div class="form-check form-check-custom form-check-solid form-check-primary me-2 form-check-sm">
                                <input class="form-check-input" type="radio" name="position" value="{{ $key }}" data-value="{{ $key }}" @if($key == $playCalling->position_id) checked @endif/>
                                </div>
                
                                <div class="flex-grow-1">
                                <h6 class="d-flex align-items-center flex-wrap me-0 m-0">
                                    {{ $item }}
                                </h6>
                                </div>
                            </div>
                            </label>
                            @endforeach
                
                        </div>
                    </div>

                    <div class="flex-fill flex-column">
                        <h6>Zone Box</h6>
                        <?php $zone = ['1' => 'Zone 1', '2' => 'Zone 2', '3' => 'Zone 3', '4' => 'Zone 4', '5' => 'Zone 5', '6' => 'Zone 6', '7' => 'Backcourt', '8' => 'Transisi']; ?>
                        <div data-kt-buttons="true">
                
                            @foreach($zone as $key => $item)
                            <label class="btn btn-outline btn-outline-dashed d-flex flex-stack text-start p-3 mb-3 active">
                            <div class="d-flex align-items-center">
                                <div class="form-check form-check-custom form-check-solid form-check-primary me-2 form-check-sm">
                                <input class="form-check-input" type="radio" name="zone_box" value="{{ $key }}" data-value="{{ $key }}" @if($key == $playCalling->zone_box_id) checked @endif/>
                                </div>
                
                                <div class="flex-grow-1">
                                <h6 class="d-flex align-items-center flex-wrap me-0 m-0">
                                    {{ $item }}
                                </h6>
                                </div>
                            </div>
                            </label>
                            @endforeach
                
                        </div>
                    </div>

                    <div class="flex-fill flex-column">
                        <h6>Call Type</h6>
                        <?php $ct = \App\Models\Master\Violation::where('status', '=', 1)->whereNull('deletedon')->get()->toArray(); ?>
                        <div data-kt-buttons="true">
                
                            @foreach($ct as $item)
                            <label class="btn btn-outline btn-outline-dashed d-flex flex-stack text-start p-3 mb-3 active">
                            <div class="d-flex align-items-center">
                                <div class="form-check form-check-custom form-check-solid form-check-primary me-2 form-check-sm">
                                <input class="form-check-input" type="radio" name="violation" value="{{ $item['id'] }}" data-value="{{ $item['id'] }}" @if($item['id'] == $playCalling->call_type_id) checked @endif/>
                                </div>
                
                                <div class="flex-grow-1">
                                <h6 class="d-flex align-items-center flex-wrap me-0 m-0">
                                    {{ $item['violation'] }}
                                </h6>
                                </div>
                            </div>
                            </label>
                            @endforeach
                
                        </div>
                    </div>

                    <div class="flex-fill flex-column">
                        <h6>IOT</h6>
                        <?php $iot = \App\Models\Master\Iot::where('status', '=', 1)->whereNull('deletedon')->get()->toArray(); ?>
                        <div data-kt-buttons="true">
                
                            @foreach($iot as $item)
                            <?php $check = \App\Models\Transaksi\TPlayCallingIot::where('id_t_play_calling', '=', $playCalling['id'])->where('iot_id', '=', $item['id'])->first(); ?>
                            <label class="btn btn-outline btn-outline-dashed d-flex flex-stack text-start p-3 mb-3 active">
                            <div class="d-flex align-items-center">
                                <div class="form-check form-check-custom form-check-solid form-check-primary me-2 form-check-sm">
                                <input class="form-check-input" type="checkbox" name="iot[]" value="{{ $item['id'] }}" data-value="{{ $item['id'] }}" @if($check) checked @endif/>
                                </div>
                
                                <div class="flex-grow-1">
                                <h6 class="d-flex align-items-center flex-wrap me-0 m-0">
                                    {{ $item['nama'] }}
                                </h6>
                                </div>
                            </div>
                            </label>
                            @endforeach
                
                        </div>
                    </div>
                </div>

                <div class="form-group float-end">
                    <button type="submit" class="btn btn-primary"> Submit </button>
                    <a class="btn btn-xs btn-secondary" href="{{ route('debrief.index', $match->id) }}"> Kembali </a>
                </div>
            </form>

        </div>
    </div>

    @section('scripts')
        <script src="{{asset('demo1/plugins/custom/datatables/datatables.bundle.js')}}"></script>
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