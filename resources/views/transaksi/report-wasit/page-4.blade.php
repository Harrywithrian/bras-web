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
$mcWasit      = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $model->id)->where('referee', '=', $user->id)->where('level', '=', 1)->get()->toArray();
$mcTotalWasit = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $model->id)->where('referee', '=', $user->id)->where('level', '=', 3)->first();
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
    @if($mcWasit)
        @foreach($mcWasit as $pmc)
            <tr>
                <td><b>{{ $i }}</b></td>
                <td><b>{{ $pmc['nama'] }}</b></td>
                <td><b>{{ number_format($pmc['sum'],2,".","") }}</b></td>
                <td><b>{{ number_format($pmc['avg'],2,".","") }}</b></td>
                <td><b>{{ $pmc['persentase'] . " %" }}</b></td>
                <td><b>{{ number_format($pmc['nilai'],2,".","") }}</b></td>
            </tr>
            <?php
            $mcChildWasit = \App\Models\Transaksi\TMechanicalCourt::where('id_parent', '=', $pmc['id_m_mechanical_court'])
                ->where('id_t_match', '=', $model->id)->where('referee', '=', $user->id)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
            $j = 0;
            ?>
            @if($mcChildWasit)
                @foreach($mcChildWasit as $smc)
                    <tr>
                        <td></td>
                        <td>{{ $arr[$j] }}. {{ $smc['nama'] }}</td>
                        <td>{{ number_format($smc['nilai'],0,".","") }}</td>
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
            <td colspan="2"><b>{{ $mcTotalWasit['nama'] }}</b></td>
            <td><b>{{ number_format($mcTotalWasit['sum'],2,".","") }}</b></td>
            <td><b>{{ number_format($mcTotalWasit['avg'],2,".","") }}</b></td>
            <td><b>{{ number_format($mcTotalWasit['persentase'],2,".","") . " %" }}</b></td>
            <td><b>{{ number_format($mcTotalWasit['nilai'],2,".","") }}</b></td>
        </tr>
    @endif
</table>