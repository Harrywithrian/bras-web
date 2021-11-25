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
$mcWasit1      = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $model->id)->where('referee', '=', $wst1->wasit)->where('level', '=', 1)->get()->toArray();
$mcWasit2      = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $model->id)->where('referee', '=', $wst2->wasit)->where('level', '=', 1)->get()->toArray();
$mcWasit3      = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $model->id)->where('referee', '=', $wst3->wasit)->where('level', '=', 1)->get()->toArray();

$mcTotalWasit1 = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $model->id)->where('referee', '=', $wst1->wasit)->where('level', '=', 3)->first();
$mcTotalWasit2 = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $model->id)->where('referee', '=', $wst2->wasit)->where('level', '=', 3)->first();
$mcTotalWasit3 = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $model->id)->where('referee', '=', $wst3->wasit)->where('level', '=', 3)->first();

$arr           = array_merge(range('a', 'z'));
?>

<div class="d-flex flex-column flex-md-row rounded border p-5">
    <ul class="nav nav-tabs nav-pills border-0 flex-row flex-md-column me-5 mb-3 mb-md-0 fs-6">
        <li class="nav-item w-md-150px me-0">
            <a class="nav-link active" data-bs-toggle="tab" href="#mc-wst1"> {{ $wst1->name }} </a>
        </li>
        <li class="nav-item w-md-150px me-0">
            <a class="nav-link" data-bs-toggle="tab" href="#mc-wst2"> {{ $wst2->name }} </a>
        </li>
        <li class="nav-item w-md-150px">
            <a class="nav-link" data-bs-toggle="tab" href="#mc-wst3"> {{ $wst3->name }} </a>
        </li>
    </ul>

    <div class="card" style="width:100% !important;">
        <div class="card-body p-0">
            <div class="tab-content" id="tab">

                <div class="tab-pane fade show active" id="mc-wst1" role="tabpanel" aria-labelledby="mc-wst1">
                    <table id="template-preview" class="table">
                        <tr>
                            <td width="3%"><b> No </b></td>
                            <td><b> Nama </b></td>
                            <td width="10%"><b> Nilai </b></td>
                            <td width="10%"><b> Rata-rata </b></td>
                            <td width="10%"><b> Nilai Akhir </b></td>
                        </tr>
                        <?php
                        $i = 1;
                        ?>
                        @if($mcWasit1)
                            @foreach($mcWasit1 as $pmc1)
                                <tr>
                                    <td><b>{{ $i }}</b></td>
                                    <td><b>{{ $pmc1['nama'] }}</b></td>
                                    <td><b>{{ number_format($pmc1['sum'],2,".","") }}</b></td>
                                    <td><b>{{ number_format($pmc1['avg'],2,".","") }}</b></td>
                                    <td><b>{{ number_format($pmc1['nilai'],2,".","") }}</b></td>
                                </tr>
                                <?php
                                $mcChildWasit1 = \App\Models\Transaksi\TMechanicalCourt::where('id_parent', '=', $pmc1['id_m_mechanical_court'])
                                    ->where('id_t_match', '=', $model->id)->where('referee', '=', $wst1->wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
                                $j = 0;
                                ?>
                                @if($mcChildWasit1)
                                    @foreach($mcChildWasit1 as $smc1)
                                        <tr>
                                            <td></td>
                                            <td>{{ $arr[$j] }}. {{ $smc1['nama'] }}</td>
                                            <td>{{ number_format($smc1['nilai'],0,".","") }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <?php $j++; ?>
                                    @endforeach
                                @endif
                                <?php $i++; ?>
                            @endforeach
                            <tr>
                                <td colspan="2"><b>{{ $mcTotalWasit1['nama'] }}</b></td>
                                <td><b>{{ number_format($mcTotalWasit1['sum'],2,".","") }}</b></td>
                                <td><b>{{ number_format($mcTotalWasit1['avg'],2,".","") }}</b></td>
                                <td><b>{{ number_format($mcTotalWasit1['nilai'],2,".","") }}</b></td>
                            </tr>
                        @endif
                    </table>
                </div>

                <div class="tab-pane fade show" id="mc-wst2" role="tabpanel" aria-labelledby="mc-wst2">
                    <table id="template-preview" class="table">
                        <tr>
                            <td width="3%"><b> No </b></td>
                            <td><b> Nama </b></td>
                            <td width="10%"><b> Nilai </b></td>
                            <td width="10%"><b> Rata-rata </b></td>
                            <td width="10%"><b> Nilai Akhir </b></td>
                        </tr>
                        <?php
                        $i = 1;
                        ?>
                        @if($mcWasit2)
                            @foreach($mcWasit2 as $pmc2)
                                <tr>
                                    <td><b>{{ $i }}</b></td>
                                    <td><b>{{ $pmc2['nama'] }}</b></td>
                                    <td><b>{{ number_format($pmc2['sum'],2,".","") }}</b></td>
                                    <td><b>{{ number_format($pmc2['avg'],2,".","") }}</b></td>
                                    <td><b>{{ number_format($pmc2['nilai'],2,".","") }}</b></td>
                                </tr>
                                <?php
                                $mcChildWasit2 = \App\Models\Transaksi\TMechanicalCourt::where('id_parent', '=', $pmc2['id_m_mechanical_court'])
                                    ->where('id_t_match', '=', $model->id)->where('referee', '=', $wst2->wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
                                $j = 0;
                                ?>
                                @if($mcChildWasit2)
                                    @foreach($mcChildWasit2 as $smc2)
                                        <tr>
                                            <td></td>
                                            <td>{{ $arr[$j] }}. {{ $smc2['nama'] }}</td>
                                            <td>{{ number_format($smc2['nilai'],0,".","") }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <?php $j++; ?>
                                    @endforeach
                                @endif
                                <?php $i++; ?>
                            @endforeach
                            <tr>
                                <td colspan="2"><b>{{ $mcTotalWasit2['nama'] }}</b></td>
                                <td><b>{{ number_format($mcTotalWasit2['sum'],2,".","") }}</b></td>
                                <td><b>{{ number_format($mcTotalWasit2['avg'],2,".","") }}</b></td>
                                <td><b>{{ number_format($mcTotalWasit2['nilai'],2,".","") }}</b></td>
                            </tr>
                        @endif
                    </table>
                </div>

                <div class="tab-pane fade show" id="mc-wst3" role="tabpanel" aria-labelledby="mc-wst3">
                    <table id="template-preview" class="table">
                        <tr>
                            <td width="3%"><b> No </b></td>
                            <td><b> Nama </b></td>
                            <td width="10%"><b> Nilai </b></td>
                            <td width="10%"><b> Rata-rata </b></td>
                            <td width="10%"><b> Nilai Akhir </b></td>
                        </tr>
                        <?php
                        $i = 1;
                        ?>
                        @if($mcWasit3)
                            @foreach($mcWasit3 as $pmc3)
                                <tr>
                                    <td><b>{{ $i }}</b></td>
                                    <td><b>{{ $pmc3['nama'] }}</b></td>
                                    <td><b>{{ number_format($pmc3['sum'],2,".","") }}</b></td>
                                    <td><b>{{ number_format($pmc3['avg'],2,".","") }}</b></td>
                                    <td><b>{{ number_format($pmc3['nilai'],2,".","") }}</b></td>
                                </tr>
                                <?php
                                $mcChildWasit3 = \App\Models\Transaksi\TMechanicalCourt::where('id_parent', '=', $pmc3['id_m_mechanical_court'])
                                    ->where('id_t_match', '=', $model->id)->where('referee', '=', $wst3->wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
                                $j = 0;
                                ?>
                                @if($mcChildWasit3)
                                    @foreach($mcChildWasit3 as $smc3)
                                        <tr>
                                            <td></td>
                                            <td>{{ $arr[$j] }}. {{ $smc3['nama'] }}</td>
                                            <td>{{ number_format($smc3['nilai'],0,".","") }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <?php $j++; ?>
                                    @endforeach
                                @endif
                                <?php $i++; ?>
                            @endforeach
                            <tr>
                                <td colspan="2"><b>{{ $mcTotalWasit3['nama'] }}</b></td>
                                <td><b>{{ number_format($mcTotalWasit3['sum'],2,".","") }}</b></td>
                                <td><b>{{ number_format($mcTotalWasit3['avg'],2,".","") }}</b></td>
                                <td><b>{{ number_format($mcTotalWasit3['nilai'],2,".","") }}</b></td>
                            </tr>
                        @endif
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
