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

$playCalling = \App\Models\Transaksi\TPlayCalling::where('id_t_match', '=', $model->id)->orderBy('quarter', 'ASC')->orderBy('time', 'DESC')->get()->toArray();

$wasit = [];
$wasit[$wst1->wasit] = \App\Models\Transaksi\TMatchReferee::where('id_t_match', '=', $model->id)->leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('wasit', '=', $wst1->wasit)->first();
$wasit[$wst2->wasit] = \App\Models\Transaksi\TMatchReferee::where('id_t_match', '=', $model->id)->leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('wasit', '=', $wst2->wasit)->first();
$wasit[$wst3->wasit] = \App\Models\Transaksi\TMatchReferee::where('id_t_match', '=', $model->id)->leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('wasit', '=', $wst3->wasit)->first();

?>
<table id="template-preview" class="table">
    <tr>
        <td width="3%"><b> No </b></td>
        <td><b> Quarter </b></td>
        <td><b> Time </b></td>
        <td><b> AR </b></td>
        <td><b> Referee </b></td>
        <td><b> Call </b></td>
        <td><b> Type </b></td>
        <td><b> Position </b></td>
        <td><b> Box </b></td>
        <td><b> IOT </b></td>
    </tr>
    <?php $i = 1 ?>
    @foreach($playCalling as $pcItem)
        <?php
            $query = \App\Models\Transaksi\TPlayCallingIot::where('id_t_play_calling', '=', $pcItem['id'])->get();
            $count = $query->count();
            $iot = $query->toArray();
        ?>
        <tr>
            <td @if ($count > 0) rowspan="{{ $count + 1 }}" @endif  width="3%"> {{ $i }} </td>
            <td @if ($count > 0) rowspan="{{ $count + 1 }}" @endif> {{ $pcItem['quarter'] }} </td>
            <td @if ($count > 0) rowspan="{{ $count + 1 }}" @endif> {{ $pcItem['time'] }} </td>
            <td @if ($count > 0) rowspan="{{ $count + 1 }}" @endif> {{ $wasit[$pcItem['referee']]->posisi }} </td>
            <td @if ($count > 0) rowspan="{{ $count + 1 }}" @endif> {{ $wasit[$pcItem['referee']]->name }} </td>
            <td @if ($count > 0) rowspan="{{ $count + 1 }}" @endif> {{ $pcItem['call_analysis'] }} </td>
            <td @if ($count > 0) rowspan="{{ $count + 1 }}" @endif> {{ $pcItem['call_type'] }} </td>
            <td @if ($count > 0) rowspan="{{ $count + 1 }}" @endif> {{ $pcItem['position'] }} </td>
            <td @if ($count > 0) rowspan="{{ $count + 1 }}" @endif> {{ $pcItem['zone_box'] }} </td>
            @if ($iot)
                @foreach($iot as $iotItem)
                    <tr>
                        <td>{{ $iotItem['iot_alias'] }}</td>
                    </tr>
                @endforeach
            @else
                <td> - </td>
            @endif
        </tr>
        <?php $i++; ?>
    @endforeach
</table>
