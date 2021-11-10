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
$aWasit       = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $model->id)->where('referee', '=', $user->id)->where('level', '=', 1)->get()->toArray();
$aTotalWasit  = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $model->id)->where('referee', '=', $user->id)->where('level', '=', 3)->first();
$arr          = array_merge(range('a', 'z'));
?>

<table id="template-preview" class="table">
    <tr>
        <td width="3%"><b> No </b></td>
        <td><b> Nama </b></td>
        <td width="10%"><b> Nilai </b></td>
        <td width="10%"><b> Rata-rata </b></td>
        <td width="10%"><b> Persentase </b></td>
        <td width="10%"><b> Nilai Akhir </b></td>
    </tr>
    <?php
    $i = 1;
    ?>
    @if($aWasit)
        @foreach($aWasit as $pa)
            <tr>
                <td><b>{{ $i }}</b></td>
                <td><b>{{ $pa['nama'] }}</b></td>
                <td><b>{{ number_format($pa['sum'],2,".","") }}</b></td>
                <td><b>{{ number_format($pa['avg'],2,".","") }}</b></td>
                <td><b>{{ $pa['persentase'] . " %" }}</b></td>
                <td><b>{{ number_format($pa['nilai'],2,".","") }}</b></td>
            </tr>
            <?php
            $aChildWasit = \App\Models\Transaksi\TAppearance::where('id_parent', '=', $pa['id_m_appearance'])
                ->where('id_t_match', '=', $model->id)->where('referee', '=', $user->id)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
            $j = 0;
            ?>
            @if($aChildWasit)
                @foreach($aChildWasit as $sa)
                    <tr>
                        <td></td>
                        <td>{{ $arr[$j] }}. {{ $sa['nama'] }}</td>
                        <td>{{ number_format($sa['nilai'],0,".","") }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php $j++; ?>
                @endforeach
            @endif
            <?php $i++; ?>
        @endforeach
        <tr>
            <td colspan="2"><b>{{ $aTotalWasit['nama'] }}</b></td>
            <td><b>{{ number_format($aTotalWasit['sum'],2,".","") }}</b></td>
            <td><b>{{ number_format($aTotalWasit['avg'],2,".","") }}</b></td>
            <td><b>{{ number_format($aTotalWasit['persentase'],2,".","") . " %" }}</b></td>
            <td><b>{{ number_format($aTotalWasit['nilai'],2,".","") }}</b></td>
        </tr>
    @endif
</table>