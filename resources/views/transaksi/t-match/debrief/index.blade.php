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
        $title = 'Debrief';
    ?>

    <link href="{{asset('demo1/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"> <i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home </a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.index-event') }}" class="pe-3"> List Event </a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.show', $match->id) }}" class="pe-3"> Pertandingan {{ $match->nama }} </a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm" id="main-layout">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light">{{ $title }}</h3>
        </div>

        <div class="card-body">

            <a class="btn btn-xs btn-secondary" href="{{ route('t-match.show', $match) }}"> Kembali </a>

            <br><br>

            <table id="template-preview" class="table">
                <tr>
                    <td width="3%"><b> No </b></td>
                    <td><b> Action </b></td>
                    <td><b> Quarter </b></td>
                    <td><b> Time </b></td>
                    <td><b> AR </b></td>
                    <td><b> Call </b></td>
                    <td><b> Type </b></td>
                    <td><b> Position </b></td>
                    <td><b> Box </b></td>
                    <td><b> IOT </b></td>
                </tr>
                <?php $i = 1; ?>
                @foreach($playCalling as $item)
                    <?php 
                        $ar = \App\Models\Transaksi\TMatchReferee::where('id_t_match', '=', $item['id_t_match'])->where('wasit', '=', $item['referee'])->first();
                        $iot = \App\Models\Transaksi\TPlayCallingIot::where('id_t_play_calling', '=', $item['id'])->get();
                    ?>
                    <tr>
                        <td style="vertical-align: middle;text-align: center;">{{ $i }}</td>
                        <td style="vertical-align: middle;text-align: center;">@if ($item['call_analysis_id'] == 4) <a class="btn btn-primary" title="Debrief" style="padding:5px; margin-top:-5px;" href="{{ route('debrief.edit', $item['id']) }}"> &nbsp<i class="bi bi-eye"></i> </a> @endif</td>
                        <td style="vertical-align: middle;text-align: center;">{{ $item['quarter'] }}</td>
                        <td style="vertical-align: middle;text-align: center;">{{ $item['time'] }}</td>
                        <td style="vertical-align: middle;text-align: center;">{{ $ar['posisi'] }}</td>
                        <td style="vertical-align: middle;text-align: center;">{{ $item['call_analysis'] }}</td>
                        <td style="vertical-align: middle;text-align: center;">{{ $item['call_type'] }}</td>
                        <td style="vertical-align: middle;text-align: center;">{{ $item['position'] }}</td>
                        <td style="vertical-align: middle;text-align: center;">{{ $item['zone_box'] }}</td>
                        <td style="vertical-align: middle;text-align: center;">
                            @if($iot)
                                @foreach ($iot as $subitem)
                                    {!! $subitem['iot_alias'] . "<br>" !!}
                                @endforeach
                            @endif
                        </td>
                    </tr>
                    <?php $i++; ?>
                @endforeach
            </table>

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