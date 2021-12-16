<html>
<head>
    <style>
        h2, h4 {
            margin-top:3px;
            margin-bottom:3px;
        }
        table {
            /*border: solid 1px black;*/
            width:100%;
            border-collapse: collapse;
        }
        td {
            border: solid 1px black;
            padding: 5px;
        }
        .page_break {
            page-break-before: always;
        }

        hr {
            border-bottom: solid 1px black;
        }
    </style>
</head>
<body>
    <?php
    $arr = array_merge(range('a', 'z'));

    $pcTotalWasit1 = \App\Models\Transaksi\TPlayCalling::where('id_t_match', '=', $id)->where('referee', '=', $wst1->wasit)->sum('score');
    $pcTotalWasit2 = \App\Models\Transaksi\TPlayCalling::where('id_t_match', '=', $id)->where('referee', '=', $wst2->wasit)->sum('score');
    $pcTotalWasit3 = \App\Models\Transaksi\TPlayCalling::where('id_t_match', '=', $id)->where('referee', '=', $wst3->wasit)->sum('score');

    $gmWasit1      = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=', $id)->where('referee', '=', $wst1->wasit)->where('level', '=', 1)->get()->toArray();
    $gmWasit2      = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=', $id)->where('referee', '=', $wst2->wasit)->where('level', '=', 1)->get()->toArray();
    $gmWasit3      = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=', $id)->where('referee', '=', $wst3->wasit)->where('level', '=', 1)->get()->toArray();

    $gmTotalWasit1 = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=', $id)->where('referee', '=', $wst1->wasit)->where('level', '=', 3)->first();
    $gmTotalWasit2 = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=', $id)->where('referee', '=', $wst2->wasit)->where('level', '=', 3)->first();
    $gmTotalWasit3 = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=', $id)->where('referee', '=', $wst3->wasit)->where('level', '=', 3)->first();

    $mcWasit1      = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $id)->where('referee', '=', $wst1->wasit)->where('level', '=', 1)->get()->toArray();
    $mcWasit2      = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $id)->where('referee', '=', $wst2->wasit)->where('level', '=', 1)->get()->toArray();
    $mcWasit3      = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $id)->where('referee', '=', $wst3->wasit)->where('level', '=', 1)->get()->toArray();

    $mcTotalWasit1 = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $id)->where('referee', '=', $wst1->wasit)->where('level', '=', 3)->first();
    $mcTotalWasit2 = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $id)->where('referee', '=', $wst2->wasit)->where('level', '=', 3)->first();
    $mcTotalWasit3 = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $id)->where('referee', '=', $wst3->wasit)->where('level', '=', 3)->first();

    $aWasit1      = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $id)->where('referee', '=', $wst1->wasit)->where('level', '=', 1)->get()->toArray();
    $aWasit2      = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $id)->where('referee', '=', $wst2->wasit)->where('level', '=', 1)->get()->toArray();
    $aWasit3      = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $id)->where('referee', '=', $wst3->wasit)->where('level', '=', 1)->get()->toArray();

    $aTotalWasit1 = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $id)->where('referee', '=', $wst1->wasit)->where('level', '=', 3)->first();
    $aTotalWasit2 = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $id)->where('referee', '=', $wst2->wasit)->where('level', '=', 3)->first();
    $aTotalWasit3 = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $id)->where('referee', '=', $wst3->wasit)->where('level', '=', 3)->first();

    $evaluation1 = \App\Models\Transaksi\TMatchEvaluation::where('id_t_match', '=', $id)->where('referee', '=', $wst1->wasit)->first();
    $evaluation2 = \App\Models\Transaksi\TMatchEvaluation::where('id_t_match', '=', $id)->where('referee', '=', $wst2->wasit)->first();
    $evaluation3 = \App\Models\Transaksi\TMatchEvaluation::where('id_t_match', '=', $id)->where('referee', '=', $wst3->wasit)->first();
    ?>

    <center><img height="60px;" src="{{ public_path() . "/demo1/media/logos/logo.png" }}"></center>
    <center><h2>{{ $event->nama }}</h2></center>
    <center><h2>{{ $match->nama }}</h2></center>
    <center><h4>Waktu Pertandingan : {{ date('H:i d/m/Y', strtotime($match->waktu_pertandingan)) }}</h4></center>
    <center><h4>Lokasi Pertandingan : {{ $lokasi->nama }}</h4></center>

    <br><br>

    <table width="100%">
        <tr>
            <td><center><b>Crew Chief</b></center></td>
            <td><center><b>Official 1</b></center></td>
            <td><center><b>Official 2</b></center></td>
        </tr>
        <tr>
            <td><center><img width="20%" class="responsive" src="{{ public_path() . "/storage/".$foto1->path }}"></center></td>
            <td><center><img width="20%" class="responsive" src="{{ public_path() . "/storage/".$foto2->path }}"></center></td>
            <td><center><img width="20%" class="responsive" src="{{ public_path() . "/storage/".$foto3->path }}"></center></td>
        </tr>
        <tr>
            <td><center>{{ $wst1->name }}</center></td>
            <td><center>{{ $wst2->name }}</center></td>
            <td><center>{{ $wst3->name }}</center></td>
        </tr>
        <tr>
            <td><center>{{ $detail1->no_lisensi }} ({{$license1->license}})</center></td>
            <td><center>{{ $detail2->no_lisensi }} ({{$license2->license}})</center></td>
            <td><center>{{ $detail3->no_lisensi }} ({{$license3->license}})</center></td>
        </tr>
        <tr>
            <td><center>{{ $region1->region }}</center></td>
            <td><center>{{ $region2->region }}</center></td>
            <td><center>{{ $region3->region }}</center></td>
        </tr>
    </table>

    <div class="page_break"></div>

    <?php
    $playCalling = \App\Models\Transaksi\TPlayCalling::where('id_t_match', '=', $id)->orderBy('quarter', 'ASC')->orderBy('time', 'DESC')->get()->toArray();

    $wasit = [];
    $wasit[$wst1->wasit] = \App\Models\Transaksi\TMatchReferee::where('id_t_match', '=', $id)->leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('wasit', '=', $wst1->wasit)->first();
    $wasit[$wst2->wasit] = \App\Models\Transaksi\TMatchReferee::where('id_t_match', '=', $id)->leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('wasit', '=', $wst2->wasit)->first();
    $wasit[$wst3->wasit] = \App\Models\Transaksi\TMatchReferee::where('id_t_match', '=', $id)->leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('wasit', '=', $wst3->wasit)->first();
    ?>

    <table>
        <tr>
            <td colspan="3"><center><b>Play Calling</b></center></td>
        </tr>
        <tr>
            <td> Crew Chief </td>
            <td> Official 1 </td>
            <td> Official 2 </td>
        </tr>
        <tr>
            <td> {{ $wst1->name }} </td>
            <td> {{ $wst2->name }} </td>
            <td> {{ $wst3->name }} </td>
        </tr>
    </table>

    <br>

    <table>
        <tr>
            <td width="3%"><b> No </b></td>
            <td><b> Quarter </b></td>
            <td><b> Time </b></td>
            <td><b> AR </b></td>
            <td><b> Call </b></td>
            <td><b> Type </b></td>
            <td><b> Position </b></td>
            <td><b> Box </b></td>
            <td><b> Score </b></td>
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
                <td @if ($count > 0) rowspan="{{ $count }}" @endif  width="3%"> {{ $i }} </td>
                <td @if ($count > 0) rowspan="{{ $count }}" @endif> {{ $pcItem['quarter'] }} </td>
                <td @if ($count > 0) rowspan="{{ $count }}" @endif> {{ $pcItem['time'] }} </td>
                <td @if ($count > 0) rowspan="{{ $count }}" @endif> {{ $wasit[$pcItem['referee']]->posisi }} </td>
                <td @if ($count > 0) rowspan="{{ $count }}" @endif> {{ $pcItem['call_analysis'] }} </td>
                <td @if ($count > 0) rowspan="{{ $count }}" @endif> {{ $pcItem['call_type'] }} </td>
                <td @if ($count > 0) rowspan="{{ $count }}" @endif> {{ $pcItem['position'] }} </td>
                <td @if ($count > 0) rowspan="{{ $count }}" @endif> {{ $pcItem['zone_box'] }} </td>
                <td @if ($count > 0) rowspan="{{ $count }}" @endif> {{ $pcItem['score'] }} </td>
            @if ($iot)
                <td>{{ $iot[0]['iot_alias'] }}</td>
                </tr>
                <?php $j = 1; ?>
                @foreach($iot as $iotItem)
                    @if($j != 1)<tr><td>{{ $iotItem['iot_alias'] }}</td></tr>@endif
                    <?php $j++ ?>
                @endforeach
            @else
                <td> - </td>
                </tr>
            @endif
            <?php $i++; ?>
        @endforeach
    </table>

    <div class="page_break"></div>

    <table>
        <tr>
            <td colspan="5"><center><b>Game Management : {{ $wst1->name }} (Crew Chief)</b></center></td>
        </tr>
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
        @if($gmWasit1)
            @foreach($gmWasit1 as $pgm1)
                <tr>
                    <td><b>{{ $i }}</b></td>
                    <td><b>{{ $pgm1['nama'] }}</b></td>
                    <td><b>{{ number_format($pgm1['sum'],2,".","") }}</b></td>
                    <td><b>{{ number_format($pgm1['avg'],2,".","") }}</b></td>
                    <td><b>{{ number_format($pgm1['nilai'],2,".","") }}</b></td>
                </tr>
                <?php
                $gmChildWasit1 = \App\Models\Transaksi\TGameManagement::where('id_parent', '=', $pgm1['id_m_game_management'])
                    ->where('id_t_match', '=', $id)->where('referee', '=', $wst1->wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
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
                <td><b>{{ number_format($gmTotalWasit1['nilai'],2,".","") }}</b></td>
            </tr>
        @endif
    </table>

    <div class="page_break"></div>

    <table>
        <tr>
            <td colspan="5"><center><b>Game Management : {{ $wst2->name }} (Official 1)</b></center></td>
        </tr>
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
        @if($gmWasit2)
            @foreach($gmWasit2 as $pgm2)
                <tr>
                    <td><b>{{ $i }}</b></td>
                    <td><b>{{ $pgm2['nama'] }}</b></td>
                    <td><b>{{ number_format($pgm2['sum'],2,".","") }}</b></td>
                    <td><b>{{ number_format($pgm2['avg'],2,".","") }}</b></td>
                    <td><b>{{ number_format($pgm2['nilai'],2,".","") }}</b></td>
                </tr>
                <?php
                $gmChildWasit2 = \App\Models\Transaksi\TGameManagement::where('id_parent', '=', $pgm2['id_m_game_management'])
                    ->where('id_t_match', '=', $id)->where('referee', '=', $wst2->wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
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
                <td><b>{{ number_format($gmTotalWasit2['nilai'],2,".","") }}</b></td>
            </tr>
        @endif
    </table>

    <div class="page_break"></div>

    <table>
        <tr>
            <td colspan="5"><center><b>Game Management : {{ $wst3->name }} (Official 2)</b></center></td>
        </tr>
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
        @if($gmWasit3)
            @foreach($gmWasit3 as $pgm3)
                <tr>
                    <td><b>{{ $i }}</b></td>
                    <td><b>{{ $pgm3['nama'] }}</b></td>
                    <td><b>{{ number_format($pgm3['sum'],2,".","") }}</b></td>
                    <td><b>{{ number_format($pgm3['avg'],2,".","") }}</b></td>
                    <td><b>{{ number_format($pgm3['nilai'],2,".","") }}</b></td>
                </tr>
                <?php
                $gmChildWasit3 = \App\Models\Transaksi\TGameManagement::where('id_parent', '=', $pgm3['id_m_game_management'])
                    ->where('id_t_match', '=', $id)->where('referee', '=', $wst3->wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
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
                <td><b>{{ number_format($gmTotalWasit3['nilai'],2,".","") }}</b></td>
            </tr>
        @endif
    </table>

    <div class="page_break"></div>

    <table>
        <tr>
            <td colspan="5"><center><b>Mechanical Court : {{ $wst1->name }} (Crew Chief)</b></center></td>
        </tr>
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
                    ->where('id_t_match', '=', $id)->where('referee', '=', $wst1->wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
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

    <div class="page_break"></div>

    <table>
        <tr>
            <td colspan="5"><center><b>Mechanical Court : {{ $wst2->name }} (Official 1)</b></center></td>
        </tr>
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
                    ->where('id_t_match', '=', $id)->where('referee', '=', $wst2->wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
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

    <div class="page_break"></div>

    <table>
        <tr>
            <td colspan="5"><center><b>Mechanical Court : {{ $wst3->name }} (Official 2)</b></center></td>
        </tr>
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
                    ->where('id_t_match', '=', $id)->where('referee', '=', $wst3->wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
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

    <div class="page_break"></div>

    <table>
        <tr>
            <td colspan="5"><center><b>Appearance : {{ $wst1->name }} (Crew Chief)</b></center></td>
        </tr>
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
                    ->where('id_t_match', '=', $id)->where('referee', '=', $wst1->wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
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

    <div class="page_break"></div>

    <table>
        <tr>
            <td colspan="5"><center><b>Appearance : {{ $wst2->name }} (Official 1)</b></center></td>
        </tr>
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
                    ->where('id_t_match', '=', $id)->where('referee', '=', $wst2->wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
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

    <div class="page_break"></div>

    <table>
        <tr>
            <td colspan="5"><center><b>Appearance : {{ $wst3->name }} (Official 2)</b></center></td>
        </tr>
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
                    ->where('id_t_match', '=', $id)->where('referee', '=', $wst3->wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
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

    <div class="page_break"></div>

    <table>
        <tr>
            <td colspan="4"><center><b>Evaluasi : {{ $wst1->name }} (Chief Crew)</b></center></td>
        </tr>
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

    <br>

    <table>
        <tr>
            <td colspan="4"><center><b>Evaluasi : {{ $wst2->name }} (Official 1)</b></center></td>
        </tr>
        <tr>
            <td width="3%"><b> No </b></td>
            <td><b> Nama </b></td>
            <td width="10%"><b> Nilai </b></td>
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

    <br>

    <table>
        <tr>
            <td colspan="4"><center><b>Evaluasi : {{ $wst3->name }} (Official 2)</b></center></td>
        </tr>
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

    <div class="page_break"></div>

    <table width="100%">
        <tr>
            <td><center><b>Catatan Evaluasi</b></center></td>
        </tr>
        <tr>
            <td><b>{{ $wst1->name }}</b></td>
        </tr>
        <tr>
            <td>{{ $evaluation1->notes }}</td>
        </tr>
        <tr>
            <td><b>{{ $wst2->name }}</b></td>
        </tr>
        <tr>
            <td>{{ $evaluation2->notes }}</td>
        </tr>
        <tr>
            <td><b>{{ $wst3->name }}</b></td>
        </tr>
        <tr>
            <td>{{ $evaluation3->notes }}</td>
        </tr>
    </table>

</body>
</html>