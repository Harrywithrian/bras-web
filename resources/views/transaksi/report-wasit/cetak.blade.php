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
    ?>
    <center><img height="60px;" src="{{ public_path() . "/demo1/media/logos/logo.png" }}"></center>
    <center><h2>{{ $event->nama }}</h2></center>
    <center><h2>{{ $match->nama }}</h2></center>
    <center><h4>Waktu Pertandingan : {{ date('H:i d/m/Y', strtotime($match->waktu_pertandingan)) }}</h4></center>
    <center><h4>Lokasi Pertandingan : {{ $lokasi->nama }}</h4></center>
    <center><h4>Report Individu : {{ $user->name }} - {{ $refereeMatch->posisi }}</h4></center>

    <br><br><br>
    <table>
        <tr>
            <td colspan="3"><center><b>Profile Wasit</b></center></td>
        </tr>
        <tr>
            <td width="30%" rowspan="6"><img width="100%" class="responsive" src="{{ public_path() . "/storage/".$foto->path }}"></td>
            <td width="30%">Nama</td>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <td>Nomor Lisensi</td>
            <td>{{ $detail->no_lisensi }}</td>
        </tr>
        <tr>
            <td>Jenis Lisensi</td>
            <td>{{ $license->license }}</td>
        </tr>
        <tr>
            <td>Pengurus Provinsi</td>
            <td>{{ $region->region }}</td>
        </tr>
        <tr>
            <td>Tempat, Tanggal Lahir</td>
            <td>{{ $detail->tempat_lahir }}, {{ date('d-m-Y', strtotime($detail->tanggal_lahir)) }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>{{ $detail->alamat }}</td>
        </tr>
    </table>

    <div class="page_break"></div>

    <table id="template-preview" class="table">
        <tr>
            <td colspan="9"><center><b>Play Calling</b></center></td>
        </tr>
        <tr>
            <td width="3%"><b> No </b></td>
            <td><b> Quarter </b></td>
            <td><b> Time </b></td>
            <td><b> Call </b></td>
            <td><b> Type </b></td>
            <td><b> Position </b></td>
            <td><b> Box </b></td>
            <td><b> Score </b></td>
            <td><b> IOT </b></td>
        </tr>
        <?php $i = 1 ?>
        @foreach($playCalling as $item)
            <?php
            $query = \App\Models\Transaksi\TPlayCallingIot::where('id_t_play_calling', '=', $item['id'])->get();
            $count = $query->count();
            $iot = $query->toArray();
            ?>
            <tr>
                <td @if ($count > 0) rowspan="{{ $count }}" @endif width="3%">{{ $i }}</td>
                <td @if ($count > 0) rowspan="{{ $count }}" @endif>{{ $item['quarter'] }}</td>
                <td @if ($count > 0) rowspan="{{ $count }}" @endif>{{ $item['time'] }}</td>
                <td @if ($count > 0) rowspan="{{ $count }}" @endif>{{ $item['call_analysis'] }}</td>
                <td @if ($count > 0) rowspan="{{ $count }}" @endif>{{ $item['call_type'] }}</td>
                <td @if ($count > 0) rowspan="{{ $count }}" @endif>{{ $item['position'] }}</td>
                <td @if ($count > 0) rowspan="{{ $count }}" @endif>{{ $item['zone_box'] }}</td>
                <td @if ($count > 0) rowspan="{{ $count }}" @endif>{{ $item['score'] }}</td>
                @if ($iot)
                    <td>{{ $iot[0]['iot_alias'] }}</td>
                    </tr>
                    <?php $i = 1; ?>
                    @foreach($iot as $iotItem)
                        @if($i != 1) <tr><td>{{ $iotItem['iot_alias'] }}</td></tr> @endif
                        <?php $i++; ?>
                    @endforeach
                @else
                    <td> - </td>
                    </tr>
                @endif
        @endforeach
        <tr>
            <td colspan="7"><b>Total</b></td>
            <td colspan="2"><b>{{ $playCallingTotal }}</b></td>
        </tr>
    </table>

    <div class="page_break"></div>

    <table>
        <tr>
            <td colspan="5"><center><b>Game Management</b></center></td>
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
        @if($gmWasit)
            @foreach($gmWasit as $pgm)
                <tr>
                    <td><b>{{ $i }}</b></td>
                    <td><b>{{ $pgm['nama'] }}</b></td>
                    <td><b>{{ number_format($pgm['sum'],2,".","") }}</b></td>
                    <td><b>{{ number_format($pgm['avg'],2,".","") }}</b></td>
                    <td><b>{{ number_format($pgm['nilai'],2,".","") }}</b></td>
                </tr>
                <?php
                $gmChildWasit = \App\Models\Transaksi\TGameManagement::where('id_parent', '=', $pgm['id_m_game_management'])
                    ->where('id_t_match', '=', $id)->where('referee', '=', $wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
                $j = 0;
                ?>
                @if($gmChildWasit)
                    @foreach($gmChildWasit as $sgm)
                        <tr>
                            <td></td>
                            <td>{{ $arr[$j] }}. {{ $sgm['nama'] }}</td>
                            <td>{{ number_format($sgm['nilai'],0,".","") }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php $j++; ?>
                    @endforeach
                @endif
                <?php $i++; ?>
            @endforeach
            <tr>
                <td colspan="2"><b>{{ $gmTotalWasit['nama'] }}</b></td>
                <td><b>{{ number_format($gmTotalWasit['sum'],2,".","") }}</b></td>
                <td><b>{{ number_format($gmTotalWasit['avg'],2,".","") }}</b></td>
                <td><b>{{ number_format($gmTotalWasit['nilai'],2,".","") }}</b></td>
            </tr>
        @endif
    </table>

    <div class="page_break"></div>

    <table>
        <tr>
            <td colspan="5"><center><b>Mechanical Court</b></center></td>
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
        @if($mcWasit)
            @foreach($mcWasit as $pmc)
                <tr>
                    <td><b>{{ $i }}</b></td>
                    <td><b>{{ $pmc['nama'] }}</b></td>
                    <td><b>{{ number_format($pmc['sum'],2,".","") }}</b></td>
                    <td><b>{{ number_format($pmc['avg'],2,".","") }}</b></td>
                    <td><b>{{ number_format($pmc['nilai'],2,".","") }}</b></td>
                </tr>
                <?php
                $mcChildWasit = \App\Models\Transaksi\TMechanicalCourt::where('id_parent', '=', $pmc['id_m_mechanical_court'])
                    ->where('id_t_match', '=', $id)->where('referee', '=', $wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
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
                <td><b>{{ number_format($mcTotalWasit['nilai'],2,".","") }}</b></td>
            </tr>
        @endif
    </table>

    <div class="page_break"></div>

    <table>
        <tr>
            <td colspan="5"><center><b>Appearance</b></center></td>
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
        @if($aWasit)
            @foreach($aWasit as $pa)
                <tr>
                    <td><b>{{ $i }}</b></td>
                    <td><b>{{ $pa['nama'] }}</b></td>
                    <td><b>{{ number_format($pa['sum'],2,".","") }}</b></td>
                    <td><b>{{ number_format($pa['avg'],2,".","") }}</b></td>
                    <td><b>{{ number_format($pa['nilai'],2,".","") }}</b></td>
                </tr>
                <?php
                $aChildWasit = \App\Models\Transaksi\TAppearance::where('id_parent', '=', $pa['id_m_appearance'])
                    ->where('id_t_match', '=', $id)->where('referee', '=', $wasit)->where('level', '=', 2)->orderBy('order_by')->get()->toArray();
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
                <td><b>{{ number_format($aTotalWasit['nilai'],2,".","") }}</b></td>
            </tr>
        @endif
    </table>

    <div class="page_break"></div>

    <table>
        <tr>
            <td colspan="4"><center><b>Evaluasi</b></center></td>
        </tr>
        <tr>
            <td width="3%"><b> No </b></td>
            <td><b> Nama </b></td>
            <td width="10%"><b> Nilai </b></td>
            <td width="10%"><b>Nilai Akhir</b></td>
        </tr>
        <tr>
            <td width="3%">1</td>
            <td> Play Calling </td>
            <td width="10%">{{ ($playCallingTotal) ? $playCallingTotal : 'Belum Di nilai' }}</td>
            <td width="10%"><center> {{ $evaluation->play_calling }} </center></td>
        </tr>
        <tr>
            <td width="3%">2</td>
            <td> Game Management </td>
            <td width="10%">{{ ($gmTotalWasit) ? $gmTotalWasit->nilai : 'Belum Di nilai' ; }}</td>
            <td width="10%"><center> {{ $evaluation->game_management }} </center></td>
        </tr>
        <tr>
            <td width="3%">3</td>
            <td> Mechanical Court </td>
            <td width="10%">{{ ($mcTotalWasit) ? $mcTotalWasit->nilai : 'Belum Di nilai' ; }}</td>
            <td width="10%"><center> {{ $evaluation->mechanical_court }} </center></td>
        </tr>
        <tr>
            <td width="3%">4</td>
            <td> Appearance </td>
            <td width="10%">{{ ($aTotalWasit) ? $aTotalWasit->nilai : 'Belum Di nilai' ; }}</td>
            <td width="10%"><center> {{ $evaluation->appearance }} </center></td>
        </tr>
        <tr>
            <td colspan="3"><b> Score Akhir </b></td>
            <td width="10%"><b><center> {{ $evaluation->total_score }} </center></b></td>
        </tr>
    </table>

    <br>
    
    <table>
        <tr>
            <td><center><b>Catatan</b></center></td>
        </tr>
        <tr>
            <td>{{  $evaluation->notes }}</td>
        </tr>
    </table>
</body>
</html>