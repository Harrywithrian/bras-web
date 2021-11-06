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
    $title = 'Evaluation : ' . $modelWasit->name;
    $i     = 1;
    $arr   = array_merge(range('a', 'z'));
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

            <a href="{{ route('t-match.show', $model->id) }}" class="btn btn-secondary mb-5"> Kembali </a>

            <table id="template-preview" class="table">
                <tr>
                    <td width="3%"><b> No </b></td>
                    <td><b> Nama </b></td>
                    <td width="10%"><b> Persentase </b></td>
                    <td width="10%"><b> Nilai </b></td>
                    <td width="10%"><b><center> Nilai Akhir </center></b></td>
                </tr>
                <tr>
                    <td width="3%">1</td>
                    <td> Play Calling </td>
                    <td width="10%"> 55 % </td>
                    <td width="10%">Belum Di nilai</td>
                    <td width="10%"><center> {{ $evaluation->play_calling }} </center></td>
                </tr>
                <tr>
                    <td width="3%">2</td>
                    <td> Game Management </td>
                    <td width="10%">15 %</td>
                    <td width="10%">{{ ($gameManagement) ? $gameManagement->nilai : 'Belum Di nilai' ; }}</td>
                    <td width="10%"><center> {{ $evaluation->game_management }} </center></td>
                </tr>
                <tr>
                    <td width="3%">3</td>
                    <td> Mechanical Court </td>
                    <td width="10%">25 %</td>
                    <td width="10%">{{ ($mechanicalCourt) ? $mechanicalCourt->nilai : 'Belum Di nilai' ; }}</td>
                    <td width="10%"><center> {{ $evaluation->mechanical_court }} </center></td>
                </tr>
                <tr>
                    <td width="3%">4</td>
                    <td> Appearance </td>
                    <td width="10%">5 %</td>
                    <td width="10%">{{ ($appearance) ? $appearance->nilai : 'Belum Di nilai' ; }}</td>
                    <td width="10%"><center> {{ $evaluation->appearance }} </center></td>
                </tr>
                <tr>
                    <td colspan="4"><b> Score Akhir </b></td>
                    <td width="10%"><b><center> {{ $evaluation->total_score }} </center></b></td>
                </tr>
            </table>
        </div>
    </div>

    @section('scripts')

    @endsection

</x-base-layout>