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
$gmWasit      = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=', $model->id)->where('referee', '=', $user->id)->where('level', '=', 1)->get()->toArray();
$gmTotalWasit = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=', $model->id)->where('referee', '=', $user->id)->where('level', '=', 3)->first();
$arr          = array_merge(range('a', 'z'));
$penilaian    = ['100' => 'Baik Sekali', '90' => 'Baik', '80' => 'Cukup', '70' => 'Kurang', '60' => 'Buruk'];
?>
<table id="template-preview" class="table">
    <tr>
        <td width="3%"><b> No </b></td>
        <td><b> Nama </b></td>
        <td width="10%"><b> Penilaian </b></td>
    </tr>
    <?php
    $i = 1;
    ?>
    @if($gmWasit)
        @foreach($gmWasit as $pgm)
            <tr>
                <td><b>{{ $i }}</b></td>
                <td><b>{{ $pgm['nama'] }}</b></td>
            </tr>
            <?php
            $gmChildWasit = \App\Models\Transaksi\TGameManagement::where('id_parent', '=', $pgm['id_m_game_management'])
                ->where('id_t_match', '=', $model->id)->where('referee', '=', $user->id)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
            $j = 0;
            ?>
            @if($gmChildWasit)
                @foreach($gmChildWasit as $sgm)
                    <tr>
                        <td></td>
                        <td>{{ $arr[$j] }}. {{ $sgm['nama'] }}</td>
                        <td>{{ $penilaian[number_format($sgm['nilai'],0,".","")] }}</td>
                    </tr>
                    <?php $j++; ?>
                @endforeach
            @endif
            <?php $i++; ?>
        @endforeach
        <tr>
            <td colspan="2"><b>{{ $gmTotalWasit['nama'] }}</b></td>
            <td><b>{{ number_format($gmTotalWasit['nilai'],2,".","") }}</b></td>
        </tr>
    @endif
</table>