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
$gmWasit1      = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=', $model->id)->where('referee', '=', $wst1->wasit)->where('level', '=', 1)->get()->toArray();
$gmWasit2      = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=', $model->id)->where('referee', '=', $wst2->wasit)->where('level', '=', 1)->get()->toArray();
$gmWasit3      = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=', $model->id)->where('referee', '=', $wst3->wasit)->where('level', '=', 1)->get()->toArray();

$gmTotalWasit1 = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=', $model->id)->where('referee', '=', $wst1->wasit)->where('level', '=', 3)->first();
$gmTotalWasit2 = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=', $model->id)->where('referee', '=', $wst2->wasit)->where('level', '=', 3)->first();
$gmTotalWasit3 = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=', $model->id)->where('referee', '=', $wst3->wasit)->where('level', '=', 3)->first();

$arr           = array_merge(range('a', 'z'));
?>

<div class="d-flex flex-column flex-md-row rounded border p-5">
    <ul class="nav nav-tabs nav-pills border-0 flex-row flex-md-column me-5 mb-3 mb-md-0 fs-6">
        <li class="nav-item w-md-150px me-0">
            <a class="nav-link active" data-bs-toggle="tab" href="#gm-wst1"> {{ $wst1->name }} </a>
        </li>
        <li class="nav-item w-md-150px me-0">
            <a class="nav-link" data-bs-toggle="tab" href="#gm-wst2"> {{ $wst2->name }} </a>
        </li>
        <li class="nav-item w-md-150px">
            <a class="nav-link" data-bs-toggle="tab" href="#gm-wst3"> {{ $wst3->name }} </a>
        </li>
    </ul>

    <div class="card" style="width:100% !important;">
        <div class="card-body p-0">
            <div class="tab-content" id="tab">

                <div class="tab-pane fade show active" id="gm-wst1" role="tabpanel" aria-labelledby="gm-wst1">
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
                        @if($gmWasit1)
                            @foreach($gmWasit1 as $pgm1)
                                <tr>
                                    <td><b>{{ $i }}</b></td>
                                    <td><b>{{ $pgm1['nama'] }}</b></td>
                                    <td><b>{{ number_format($pgm1['sum'],2,".","") }}</b></td>
                                    <td><b>{{ number_format($pgm1['avg'],2,".","") }}</b></td>
                                    <td><b>{{ $pgm1['persentase'] . " %" }}</b></td>
                                    <td><b>{{ number_format($pgm1['nilai'],2,".","") }}</b></td>
                                </tr>
                                <?php
                                $gmChildWasit1 = \App\Models\Transaksi\TGameManagement::where('id_parent', '=', $pgm1['id_m_game_management'])
                                    ->where('id_t_match', '=', $model->id)->where('referee', '=', $wst1->wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
                                $j = 0;
                                ?>
                                @if($gmChildWasit1)
                                    @foreach($gmChildWasit1 as $sgm1)
                                        <tr>
                                            <td></td>
                                            <td>{{ $arr[$j] }}. {{ $sgm1['nama'] }}</td>
                                            <td>{{ number_format($sgm1['nilai'],0,".","") }}</td>
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
                                <td colspan="2"><b>{{ $gmTotalWasit1['nama'] }}</b></td>
                                <td><b>{{ number_format($gmTotalWasit1['sum'],2,".","") }}</b></td>
                                <td><b>{{ number_format($gmTotalWasit1['avg'],2,".","") }}</b></td>
                                <td><b>{{ number_format($gmTotalWasit1['persentase'],2,".","") . " %" }}</b></td>
                                <td><b>{{ number_format($gmTotalWasit1['nilai'],2,".","") }}</b></td>
                            </tr>
                        @endif
                    </table>
                </div>

                <div class="tab-pane fade" id="gm-wst2" role="tabpanel" aria-labelledby="gm-wst2">
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
                        @if($gmWasit2)
                            @foreach($gmWasit2 as $pgm2)
                                <tr>
                                    <td><b>{{ $i }}</b></td>
                                    <td><b>{{ $pgm2['nama'] }}</b></td>
                                    <td><b>{{ number_format($pgm2['sum'],2,".","") }}</b></td>
                                    <td><b>{{ number_format($pgm2['avg'],2,".","") }}</b></td>
                                    <td><b>{{ $pgm2['persentase'] . " %" }}</b></td>
                                    <td><b>{{ number_format($pgm2['nilai'],2,".","") }}</b></td>
                                </tr>
                                <?php
                                $gmChildWasit2 = \App\Models\Transaksi\TGameManagement::where('id_parent', '=', $pgm2['id_m_game_management'])
                                    ->where('id_t_match', '=', $model->id)->where('referee', '=', $wst2->wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
                                $j = 0;
                                ?>
                                @if($gmChildWasit2)
                                    @foreach($gmChildWasit2 as $sgm2)
                                        <tr>
                                            <td></td>
                                            <td>{{ $arr[$j] }}. {{ $sgm2['nama'] }}</td>
                                            <td>{{ number_format($sgm2['nilai'],0,".","") }}</td>
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
                                <td colspan="2"><b>{{ $gmTotalWasit2['nama'] }}</b></td>
                                <td><b>{{ number_format($gmTotalWasit2['sum'],2,".","") }}</b></td>
                                <td><b>{{ number_format($gmTotalWasit2['avg'],2,".","") }}</b></td>
                                <td><b>{{ number_format($gmTotalWasit2['persentase'],2,".","") . " %" }}</b></td>
                                <td><b>{{ number_format($gmTotalWasit2['nilai'],2,".","") }}</b></td>
                            </tr>
                        @endif
                    </table>
                </div>

                <div class="tab-pane fade" id="gm-wst3" role="tabpanel" aria-labelledby="gm-wst3">
                    <table id="template-preview" class="table">
                        <tr>
                            <td width="3%"><b> No </b></td>
                            <td><b> Nama </b></td>
                            <td width="10%"><b> Nilai </b></td>
                            <td width="10%"><b> Rata-rata </b></td>
                            <td width="10%"><b> Persentase </b></td>
                            <td width="10%"><b> Nilai Akhir </b></td>
                        </tr>
                        @if($gmWasit3)
                            @foreach($gmWasit3 as $pgm3)
                                <tr>
                                    <td><b>{{ $i }}</b></td>
                                    <td><b>{{ $pgm3['nama'] }}</b></td>
                                    <td><b>{{ number_format($pgm3['sum'],2,".","") }}</b></td>
                                    <td><b>{{ number_format($pgm3['avg'],2,".","") }}</b></td>
                                    <td><b>{{ $pgm3['persentase'] . " %" }}</b></td>
                                    <td><b>{{ number_format($pgm3['nilai'],2,".","") }}</b></td>
                                </tr>
                                <?php
                                $gmChildWasit3 = \App\Models\Transaksi\TGameManagement::where('id_parent', '=', $pgm3['id_m_game_management'])
                                    ->where('id_t_match', '=', $model->id)->where('referee', '=', $wst3->wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
                                $j = 0;
                                ?>
                                @if($gmChildWasit3)
                                    @foreach($gmChildWasit3 as $sgm3)
                                        <tr>
                                            <td></td>
                                            <td>{{ $arr[$j] }}. {{ $sgm3['nama'] }}</td>
                                            <td>{{ number_format($sgm3['nilai'],0,".","") }}</td>
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
                                <td colspan="2"><b>{{ $gmTotalWasit3['nama'] }}</b></td>
                                <td><b>{{ number_format($gmTotalWasit3['sum'],2,".","") }}</b></td>
                                <td><b>{{ number_format($gmTotalWasit3['avg'],2,".","") }}</b></td>
                                <td><b>{{ number_format($gmTotalWasit3['persentase'],2,".","") . " %" }}</b></td>
                                <td><b>{{ number_format($gmTotalWasit3['nilai'],2,".","") }}</b></td>
                            </tr>
                        @endif
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>