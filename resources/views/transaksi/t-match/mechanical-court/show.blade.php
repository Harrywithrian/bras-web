<x-base-layout>
    <style>
        #template-preview {
            border: 1px solid #ddd;
        }
        #template-preview td, #template-preview th {
            border: 1px solid #ddd;
            padding: 8px;
        }
    </style>

    <?php
    $title = 'Mechanical Court : ' . $modelWasit->name;
    $i     = 1;
    $arr   = array_merge(range('a', 'z'));
    $penilaian = ['100' => 'Baik Sekali', '90' => 'Baik', '80' => 'Cukup', '70' => 'Kurang', '60' => 'Buruk'];
    ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"> <i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home </a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.index-event') }}" class="pe-3"> List Event </a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.index', $event->id) }}" class="pe-3"> Pertandingan Event {{ $event->nama }} </a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.show', $model->id) }}" class="pe-3"> Pertandingan {{ $model->nama }} </a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>


    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">
            
            @if($event->status != 2)
                <a href="{{ route('mechanical-court.edit', [$model->id, $modelWasit->id]) }}" class="btn btn-primary mb-5"> Update </a>
            @endif
            <a href="{{ route('t-match.show', $model->id) }}" class="btn btn-secondary mb-5"> Kembali </a>

            <table id="template-preview" class="table">
                <tr>
                    <td width="3%"><b> No </b></td>
                    <td><b> Nama </b></td>
                    <td width="10%"><b> Penilaian </b></td>
                </tr>
                @if($mechanicalCourt)
                    @foreach($mechanicalCourt as $item)
                        <tr>
                            <td><b>{{ $i }}</b></td>
                            <td><b>{{ $item['nama'] }}</b></td>
                        </tr>
                        <?php
                        $child = \App\Models\Transaksi\TMechanicalCourt::where('id_parent', '=', $item['id_m_mechanical_court'])
                            ->where('id_t_match', '=', $model->id)->where('referee', '=', $modelWasit->wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
                        $j = 0;
                        ?>
                        @if($child)
                            @foreach($child as $subitem)
                                <tr>
                                    <td></td>
                                    <td>{{ $arr[$j] }}. {{ $subitem['nama'] }}</td>
                                    <td>{{ $penilaian[number_format($subitem['nilai'],0,".","")] }}</td>
                                </tr>
                                <?php $j++ ?>
                            @endforeach
                        @endif
                        <?php $i++ ?>
                    @endforeach
                    <tr>
                        <td colspan="2"><b>{{ $total['nama'] }}</b></td>
                        <td><b>{{ number_format(($total['nilai'] / 100) * 25,3,".","") }}</b></td>
                    </tr>
                @endif
            </table>
        </div>
    </div>

    @section('scripts')

    @endsection

</x-base-layout>