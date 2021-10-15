<x-base-layout>
    <?php
    $title = 'Preview Mechanical Court';
    $i = 1;
    $arr = array_merge(range('a', 'z'));
    ?>

    <style>
        #template-preview {
            border: 1px solid #ddd;
        }
        #template-preview td, #template-preview th {
            border: 1px solid #ddd;
            padding: 8px;
        }
    </style>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"> <i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home </a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('m-mechanical-court.index') }}" class="pe-3"> Template Mechanical Court </a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm" id="main-layout">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light">{{ $title }}</h3>
        </div>

        <div class="card-body">

            <a class="btn btn-xs btn-secondary mb-5" href="{{ route('m-mechanical-court.index') }}"> Kembali </a>

            <table id="template-preview" class="table">
                <tr>
                    <td width="3%"><b> No </b></td>
                    <td><b> Nama </b></td>
                    <td><b> % </b></td>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td><b>{{ $i }}</b></td>
                        <td><b>{{ $item['nama'] }}</b></td>
                        <td><b>{{ $item['persentase'] }}</b></td>
                    </tr>
                    <?php
                        $child = \App\Models\Master\MMechanicalCourt::where('id_m_mechanical_court', '=', $item['id'])->whereNull('deletedon')->orderBy('order_by')->get()->toArray();
                        $j = 0;
                    ?>
                        @if($child)
                            @foreach($child as $subitem)
                                <tr>
                                    <td></td>
                                    <td>{{ $arr[$j] }}. {{ $subitem['nama'] }}</td>
                                    <td></td>
                                </tr>
                            <?php $j++; ?>
                            @endforeach
                        @endif
                    <?php $i++; ?>
                @endforeach
                <tr>
                    <td colspan="2"><b>Total</b></td>
                    <td><b>{{ $persentase }}%</b></td>
                </tr>
            </table>

        </div>
    </div>

</x-base-layout>