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
$evaluation1 = \App\Models\Transaksi\TMatchEvaluation::where('id_t_match', '=', $model->id)->where('referee', '=', $wst1->wasit)->first();
$evaluation2 = \App\Models\Transaksi\TMatchEvaluation::where('id_t_match', '=', $model->id)->where('referee', '=', $wst2->wasit)->first();
$evaluation3 = \App\Models\Transaksi\TMatchEvaluation::where('id_t_match', '=', $model->id)->where('referee', '=', $wst3->wasit)->first();

$pcTotalWasit1 = \App\Models\Transaksi\TPlayCalling::where('id_t_match', '=', $model->id)->where('referee', '=', $wst1->wasit)->sum('score');
$pcTotalWasit2 = \App\Models\Transaksi\TPlayCalling::where('id_t_match', '=', $model->id)->where('referee', '=', $wst2->wasit)->sum('score');
$pcTotalWasit3 = \App\Models\Transaksi\TPlayCalling::where('id_t_match', '=', $model->id)->where('referee', '=', $wst3->wasit)->sum('score');

$gmTotalWasit1 = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=', $model->id)->where('referee', '=', $wst1->wasit)->where('level', '=', 3)->first();
$gmTotalWasit2 = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=', $model->id)->where('referee', '=', $wst2->wasit)->where('level', '=', 3)->first();
$gmTotalWasit3 = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=', $model->id)->where('referee', '=', $wst3->wasit)->where('level', '=', 3)->first();

$mcTotalWasit1 = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $model->id)->where('referee', '=', $wst1->wasit)->where('level', '=', 3)->first();
$mcTotalWasit2 = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $model->id)->where('referee', '=', $wst2->wasit)->where('level', '=', 3)->first();
$mcTotalWasit3 = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $model->id)->where('referee', '=', $wst3->wasit)->where('level', '=', 3)->first();

$aTotalWasit1 = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $model->id)->where('referee', '=', $wst1->wasit)->where('level', '=', 3)->first();
$aTotalWasit2 = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $model->id)->where('referee', '=', $wst2->wasit)->where('level', '=', 3)->first();
$aTotalWasit3 = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $model->id)->where('referee', '=', $wst3->wasit)->where('level', '=', 3)->first();
?>

<div class="d-flex flex-column flex-md-row rounded border p-5">
    <ul class="nav nav-tabs nav-pills border-0 flex-row flex-md-column me-5 mb-3 mb-md-0 fs-6">
        <li class="nav-item w-md-150px me-0">
            <a class="nav-link active" data-bs-toggle="tab" href="#e-wst1"> {{ $wst1->name }} </a>
        </li>
        <li class="nav-item w-md-150px me-0">
            <a class="nav-link" data-bs-toggle="tab" href="#e-wst2"> {{ $wst2->name }} </a>
        </li>
        <li class="nav-item w-md-150px">
            <a class="nav-link" data-bs-toggle="tab" href="#e-wst3"> {{ $wst3->name }} </a>
        </li>
    </ul>

    <div class="card" style="width:100% !important;">
        <div class="card-body p-0">
            <div class="tab-content" id="tab">

                <div class="tab-pane fade show active" id="e-wst1" role="tabpanel" aria-labelledby="e-wst1">
                    <table id="template-preview" class="table">
                        <tr>
                            <td width="3%"><b> No </b></td>
                            <td><b> Nama </b></td>
                            <td width="10%"><b> Nilai </b></td>
                            <td width="10%"><b><center> Nilai Akhir </center></b></td>
                        </tr>
                        <tr>
                            <td width="3%">1</td>
                            <td> Play Calling </td>
                            <td width="10%">{{ ($pcTotalWasit1) ? $pcTotalWasit1 : 'Belum Di nilai' }}</td>
                            <td width="10%"><center> {{ $evaluation1->play_calling }} </center></td>
                        </tr>
                        <tr>
                            <td width="3%">2</td>
                            <td> Game Management </td>
                            <td width="10%">{{ ($gmTotalWasit1) ? $gmTotalWasit1->nilai : 'Belum Di nilai' ; }}</td>
                            <td width="10%"><center> {{ $evaluation1->game_management }} </center></td>
                        </tr>
                        <tr>
                            <td width="3%">3</td>
                            <td> Mechanical Court </td>
                            <td width="10%">{{ ($mcTotalWasit1) ? $mcTotalWasit1->nilai : 'Belum Di nilai' ; }}</td>
                            <td width="10%"><center> {{ $evaluation1->mechanical_court }} </center></td>
                        </tr>
                        <tr>
                            <td width="3%">4</td>
                            <td> Appearance </td>
                            <td width="10%">{{ ($aTotalWasit1) ? $aTotalWasit1->nilai : 'Belum Di nilai' ; }}</td>
                            <td width="10%"><center> {{ $evaluation1->appearance }} </center></td>
                        </tr>
                        <tr>
                            <td colspan="3"><b> Score Akhir </b></td>
                            <td width="10%"><b><center> {{ $evaluation1->total_score }} </center></b></td>
                        </tr>
                    </table>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Catatan Evaluasi</label>
                            <textarea id="evaluasi_1" class="form-control" name="evaluasi_1" readOnly>{{ $evaluation1->notes }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade show" id="e-wst2" role="tabpanel" aria-labelledby="e-wst2">
                    <table id="template-preview" class="table">
                        <tr>
                            <td width="3%"><b> No </b></td>
                            <td><b> Nama </b></td>
                            <td width="10%"><b> Persentase </b></td>
                            <td width="10%"><b><center> Nilai Akhir </center></b></td>
                        </tr>
                        <tr>
                            <td width="3%">1</td>
                            <td> Play Calling </td>
                            <td width="10%">{{ ($pcTotalWasit2) ? $pcTotalWasit2 : 'Belum Di nilai' }}</td>
                            <td width="10%"><center> {{ $evaluation2->play_calling }} </center></td>
                        </tr>
                        <tr>
                            <td width="3%">2</td>
                            <td> Game Management </td>
                            <td width="10%">{{ ($gmTotalWasit2) ? $gmTotalWasit2->nilai : 'Belum Di nilai' ; }}</td>
                            <td width="10%"><center> {{ $evaluation2->game_management }} </center></td>
                        </tr>
                        <tr>
                            <td width="3%">3</td>
                            <td> Mechanical Court </td>
                            <td width="10%">{{ ($mcTotalWasit2) ? $mcTotalWasit2->nilai : 'Belum Di nilai' ; }}</td>
                            <td width="10%"><center> {{ $evaluation2->mechanical_court }} </center></td>
                        </tr>
                        <tr>
                            <td width="3%">4</td>
                            <td> Appearance </td>
                            <td width="10%">{{ ($aTotalWasit2) ? $aTotalWasit2->nilai : 'Belum Di nilai' ; }}</td>
                            <td width="10%"><center> {{ $evaluation2->appearance }} </center></td>
                        </tr>
                        <tr>
                            <td colspan="3"><b> Score Akhir </b></td>
                            <td width="10%"><b><center> {{ $evaluation2->total_score }} </center></b></td>
                        </tr>
                    </table>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Catatan Evaluasi</label>
                            <textarea id="evaluasi_2" class="form-control" name="evaluasi_2" readOnly>{{ $evaluation2->notes }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade show" id="e-wst3" role="tabpanel" aria-labelledby="e-wst3">
                    <table id="template-preview" class="table">
                        <tr>
                            <td width="3%"><b> No </b></td>
                            <td><b> Nama </b></td>
                            <td width="10%"><b> Nilai </b></td>
                            <td width="10%"><b><center> Nilai Akhir </center></b></td>
                        </tr>
                        <tr>
                            <td width="3%">1</td>
                            <td> Play Calling </td>
                            <td width="10%">{{ ($pcTotalWasit3) ? $pcTotalWasit3 : 'Belum Di nilai' }}</td>
                            <td width="10%"><center> {{ $evaluation3->play_calling }} </center></td>
                        </tr>
                        <tr>
                            <td width="3%">2</td>
                            <td> Game Management </td>
                            <td width="10%">{{ ($gmTotalWasit3) ? $gmTotalWasit3->nilai : 'Belum Di nilai' ; }}</td>
                            <td width="10%"><center> {{ $evaluation3->game_management }} </center></td>
                        </tr>
                        <tr>
                            <td width="3%">3</td>
                            <td> Mechanical Court </td>
                            <td width="10%">{{ ($mcTotalWasit3) ? $mcTotalWasit3->nilai : 'Belum Di nilai' ; }}</td>
                            <td width="10%"><center> {{ $evaluation3->mechanical_court }} </center></td>
                        </tr>
                        <tr>
                            <td width="3%">4</td>
                            <td> Appearance </td>
                            <td width="10%">{{ ($aTotalWasit3) ? $aTotalWasit3->nilai : 'Belum Di nilai' ; }}</td>
                            <td width="10%"><center> {{ $evaluation3->appearance }} </center></td>
                        </tr>
                        <tr>
                            <td colspan="3"><b> Score Akhir </b></td>
                            <td width="10%"><b><center> {{ $evaluation3->total_score }} </center></b></td>
                        </tr>
                    </table>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Catatan Evaluasi</label>
                            <textarea id="evaluasi_3" class="form-control" name="evaluasi_3" readOnly>{{ $evaluation3->notes }}</textarea>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>