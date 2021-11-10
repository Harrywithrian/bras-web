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
$evaluation   = \App\Models\Transaksi\TMatchEvaluation::where('id_t_match', '=', $model->id)->where('referee', '=', $user->id)->first();
$pcTotalWasit = \App\Models\Transaksi\TPlayCalling::where('id_t_match', '=', $model->id)->where('referee', '=', $user->id)->sum('score');
$gmTotalWasit = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=', $model->id)->where('referee', '=', $user->id)->where('level', '=', 3)->first();
$mcTotalWasit = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $model->id)->where('referee', '=', $user->id)->where('level', '=', 3)->first();
$aTotalWasit  = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $model->id)->where('referee', '=', $user->id)->where('level', '=', 3)->first();
?>

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
        <td width="10%">{{ ($pcTotalWasit) ? $pcTotalWasit : 'Belum Di nilai' }}</td>
        <td width="10%"><center> {{ $evaluation->play_calling }} </center></td>
    </tr>
    <tr>
        <td width="3%">2</td>
        <td> Game Management </td>
        <td width="10%">15 %</td>
        <td width="10%">{{ ($gmTotalWasit) ? $gmTotalWasit->nilai : 'Belum Di nilai' ; }}</td>
        <td width="10%"><center> {{ $evaluation->game_management }} </center></td>
    </tr>
    <tr>
        <td width="3%">3</td>
        <td> Mechanical Court </td>
        <td width="10%">25 %</td>
        <td width="10%">{{ ($mcTotalWasit) ? $mcTotalWasit->nilai : 'Belum Di nilai' ; }}</td>
        <td width="10%"><center> {{ $evaluation->mechanical_court }} </center></td>
    </tr>
    <tr>
        <td width="3%">4</td>
        <td> Appearance </td>
        <td width="10%">5 %</td>
        <td width="10%">{{ ($aTotalWasit) ? $aTotalWasit->nilai : 'Belum Di nilai' ; }}</td>
        <td width="10%"><center> {{ $evaluation->appearance }} </center></td>
    </tr>
    <tr>
        <td colspan="4"><b> Score Akhir </b></td>
        <td width="10%"><b><center> {{ $evaluation->total_score }} </center></b></td>
    </tr>
</table>