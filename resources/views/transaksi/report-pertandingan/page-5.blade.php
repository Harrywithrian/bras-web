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
$aWasit1      = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $model->id)->where('referee', '=', $wst1->wasit)->where('level', '=', 1)->get()->toArray();
$aWasit2      = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $model->id)->where('referee', '=', $wst2->wasit)->where('level', '=', 1)->get()->toArray();
$aWasit3      = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $model->id)->where('referee', '=', $wst3->wasit)->where('level', '=', 1)->get()->toArray();

$aTotalWasit1 = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $model->id)->where('referee', '=', $wst1->wasit)->where('level', '=', 3)->first();
$aTotalWasit2 = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $model->id)->where('referee', '=', $wst2->wasit)->where('level', '=', 3)->first();
$aTotalWasit3 = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $model->id)->where('referee', '=', $wst3->wasit)->where('level', '=', 3)->first();

$arr           = array_merge(range('a', 'z'));
?>

<div class="d-flex flex-column flex-md-row rounded border p-5">
    <ul class="nav nav-tabs nav-pills border-0 flex-row flex-md-column me-5 mb-3 mb-md-0 fs-6">
        <li class="nav-item w-md-150px me-0">
            <a class="nav-link active" data-bs-toggle="tab" href="#a-wst1"> {{ $wst1->name }} </a>
        </li>
        <li class="nav-item w-md-150px me-0">
            <a class="nav-link" data-bs-toggle="tab" href="#a-wst2"> {{ $wst2->name }} </a>
        </li>
        <li class="nav-item w-md-150px">
            <a class="nav-link" data-bs-toggle="tab" href="#a-wst3"> {{ $wst3->name }} </a>
        </li>
    </ul>

    <div class="card" style="width:100% !important;">
        <div class="card-body p-0">
            <div class="tab-content" id="tab">

                <div class="tab-pane fade show active" id="a-wst1" role="tabpanel" aria-labelledby="a-wst1">
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
                        @if($aWasit1)
                            @foreach($aWasit1 as $pa1)
                                <tr>
                                    <td><b>{{ $i }}</b></td>
                                    <td><b>{{ $pa1['nama'] }}</b></td>
                                    <td><b>{{ number_format($pa1['sum'],2,".","") }}</b></td>
                                    <td><b>{{ number_format($pa1['avg'],2,".","") }}</b></td>
                                    <td><b>{{ number_format($pa1['nilai'],2,".","") }}</b></td>
                                </tr>
                                <?php
                                $aChildWasit1 = \App\Models\Transaksi\TAppearance::where('id_parent', '=', $pa1['id_m_appearance'])
                                    ->where('id_t_match', '=', $model->id)->where('referee', '=', $wst1->wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
                                $j = 0;
                                ?>
                                @if($aChildWasit1)
                                    @foreach($aChildWasit1 as $sa1)
                                        <tr>
                                            <td></td>
                                            <td>{{ $arr[$j] }}. {{ $sa1['nama'] }}</td>
                                            <td>{{ number_format($sa1['nilai'],0,".","") }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <?php $j++; ?>
                                    @endforeach
                                @endif
                                <?php $i++; ?>
                            @endforeach
                            <tr>
                                <td colspan="2"><b>{{ $aTotalWasit1['nama'] }}</b></td>
                                <td><b>{{ number_format($aTotalWasit1['sum'],2,".","") }}</b></td>
                                <td><b>{{ number_format($aTotalWasit1['avg'],2,".","") }}</b></td>
                                <td><b>{{ number_format($aTotalWasit1['nilai'],2,".","") }}</b></td>
                            </tr>
                        @endif
                    </table>
                </div>

                <div class="tab-pane fade show" id="a-wst2" role="tabpanel" aria-labelledby="a-wst2">
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
                        @if($aWasit2)
                            @foreach($aWasit2 as $pa2)
                                <tr>
                                    <td><b>{{ $i }}</b></td>
                                    <td><b>{{ $pa2['nama'] }}</b></td>
                                    <td><b>{{ number_format($pa2['sum'],2,".","") }}</b></td>
                                    <td><b>{{ number_format($pa2['avg'],2,".","") }}</b></td>
                                    <td><b>{{ number_format($pa2['nilai'],2,".","") }}</b></td>
                                </tr>
                                <?php
                                $aChildWasit2 = \App\Models\Transaksi\TAppearance::where('id_parent', '=', $pa2['id_m_appearance'])
                                    ->where('id_t_match', '=', $model->id)->where('referee', '=', $wst2->wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
                                $j = 0;
                                ?>
                                @if($aChildWasit2)
                                    @foreach($aChildWasit2 as $sa2)
                                        <tr>
                                            <td></td>
                                            <td>{{ $arr[$j] }}. {{ $sa2['nama'] }}</td>
                                            <td>{{ number_format($sa2['nilai'],0,".","") }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <?php $j++; ?>
                                    @endforeach
                                @endif
                                <?php $i++; ?>
                            @endforeach
                            <tr>
                                <td colspan="2"><b>{{ $aTotalWasit2['nama'] }}</b></td>
                                <td><b>{{ number_format($aTotalWasit2['sum'],2,".","") }}</b></td>
                                <td><b>{{ number_format($aTotalWasit2['avg'],2,".","") }}</b></td>
                                <td><b>{{ number_format($aTotalWasit2['nilai'],2,".","") }}</b></td>
                            </tr>
                        @endif
                    </table>
                </div>

                <div class="tab-pane fade show" id="a-wst3" role="tabpanel" aria-labelledby="a-wst3">
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
                        @if($aWasit3)
                            @foreach($aWasit3 as $pa3)
                                <tr>
                                    <td><b>{{ $i }}</b></td>
                                    <td><b>{{ $pa3['nama'] }}</b></td>
                                    <td><b>{{ number_format($pa3['sum'],2,".","") }}</b></td>
                                    <td><b>{{ number_format($pa3['avg'],2,".","") }}</b></td>
                                    <td><b>{{ number_format($pa3['nilai'],2,".","") }}</b></td>
                                </tr>
                                <?php
                                $aChildWasit3 = \App\Models\Transaksi\TAppearance::where('id_parent', '=', $pa3['id_m_appearance'])
                                    ->where('id_t_match', '=', $model->id)->where('referee', '=', $wst3->wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
                                $j = 0;
                                ?>
                                @if($aChildWasit3)
                                    @foreach($aChildWasit3 as $sa3)
                                        <tr>
                                            <td></td>
                                            <td>{{ $arr[$j] }}. {{ $sa3['nama'] }}</td>
                                            <td>{{ number_format($sa3['nilai'],0,".","") }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <?php $j++; ?>
                                    @endforeach
                                @endif
                                <?php $i++; ?>
                            @endforeach
                            <tr>
                                <td colspan="2"><b>{{ $aTotalWasit3['nama'] }}</b></td>
                                <td><b>{{ number_format($aTotalWasit3['sum'],2,".","") }}</b></td>
                                <td><b>{{ number_format($aTotalWasit3['avg'],2,".","") }}</b></td>
                                <td><b>{{ number_format($aTotalWasit3['nilai'],2,".","") }}</b></td>
                            </tr>
                        @endif
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
