<x-base-layout>
    @if($role->role == 8)
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-danger">
                        <div style="width:100%; margin-top:10px;">
                            <center><h5 class="text-white">Next Match</h5></center>
                            @if($todayMatch) <center><h3 class="text-white">{{ $todayMatch->nama }}</h3></center> @endif
                            @if($todayMatch) <center><h5 class="text-white">{{ $todayMatchTime }}</h5></center> @endif

                        </div>
                    </div>
                    <div class="card-body bg-primary">
                        @if($todayMatch)
                            <?php 
                            $crewChief = App\Models\Transaksi\TMatchReferee::select(['users.name', 't_file.path', 'm_region.region'])
                            ->leftJoin('users', 'users.id', '=', 't_match_referee.wasit')
                            ->leftJoin('user_infos', 'user_infos.user_id', '=', 't_match_referee.wasit')
                            ->leftJoin('t_file', 'user_infos.id_t_file_foto', '=', 't_file.id')
                            ->leftJoin('m_region', 'user_infos.id_m_region', '=', 'm_region.id')
                            ->where('id_t_match', '=', $todayMatch->id)->where('t_match_referee.posisi', '=', 'Crew Chief')->first();

                            $Official1 = App\Models\Transaksi\TMatchReferee::select(['users.name', 't_file.path', 'm_region.region'])
                            ->leftJoin('users', 'users.id', '=', 't_match_referee.wasit')
                            ->leftJoin('user_infos', 'user_infos.user_id', '=', 't_match_referee.wasit')
                            ->leftJoin('t_file', 'user_infos.id_t_file_foto', '=', 't_file.id')
                            ->leftJoin('m_region', 'user_infos.id_m_region', '=', 'm_region.id')
                            ->where('id_t_match', '=', $todayMatch->id)->where('posisi', '=', 'Official 1')->first();

                            $Official2 = App\Models\Transaksi\TMatchReferee::select(['users.name', 't_file.path', 'm_region.region'])
                            ->leftJoin('users', 'users.id', '=', 't_match_referee.wasit')
                            ->leftJoin('user_infos', 'user_infos.user_id', '=', 't_match_referee.wasit')
                            ->leftJoin('t_file', 'user_infos.id_t_file_foto', '=', 't_file.id')
                            ->leftJoin('m_region', 'user_infos.id_m_region', '=', 'm_region.id')
                            ->where('id_t_match', '=', $todayMatch->id)->where('posisi', '=', 'Official 2')->first();
                            ?>

                            <div class="card shadow-sm bg-light-primary" style="padding:10px; margin:10px;">
                                <table>
                                    <tr>
                                        <td style="width:5%"><img src="{{ url('storage/'.$crewChief->path) }}" style="width: 50px; height: 50px; object-fit: cover; object-position: 100% 0"></td>
                                        <td><h4 style="margin:0px;">{{ $crewChief->name }}</h4><label>{{ $crewChief->region }}</label></td>
                                        <td class="text-end"><h4 style="margin-right:2%;">Crew Chief</h4></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="card shadow-sm bg-light-primary" style="padding:10px; margin:10px;">
                                <table>
                                    <tr>
                                        <td style="width:5%"><img src="{{ url('storage/'.$Official1->path) }}" style="width: 50px; height: 50px; object-fit: cover; object-position: 100% 0"></td>
                                        <td><h4 style="margin:0px;">{{ $Official1->name }}</h4><label>{{ $Official1->region }}</label></td>
                                        <td class="text-end"><h4 style="margin-right:2%;">Official 1</h4></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="card shadow-sm bg-light-primary" style="padding:10px; margin:10px;">
                                <table>
                                    <tr>
                                        <td style="width:5%"><img src="{{ url('storage/'.$Official2->path) }}" style="width: 50px; height: 50px; object-fit: cover; object-position: 100% 0"></td>
                                        <td><h4 style="margin:0px;">{{ $Official2->name }}</h4><label>{{ $Official2->region }}</label></td>
                                        <td class="text-end"><h4 style="margin-right:2%;">Official 2</h4></td>
                                    </tr>
                                </table>
                            </div>
                        @else
                            <center><h1 class="text-white">No Matches ...</h1></center>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <br>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    
                    <h4 class="card-title">Referee Rank</h4>

                    <?php $i = 1 ?>
                    @foreach($rank as $item)
                    <div class="card shadow-sm bg-primary" style="padding:10px; margin:10px;">
                        <table>
                            <tr>
                                <td class="text-center" style="width:5%"><h4 class="text-white" style="margin:0px;">{{ $i }}</h4></td>
                                <td style="width:10%"><img src="{{ url('storage/'.$item['path']) }}" style="width: 50px; height: 50px; object-fit: cover; object-position: 100% 0"></td>
                                <td><h4 class="text-white" style="margin:0px;">{{ $item['name'] }}</h4><label class="text-white">{{ $item['region'] }}</label></td>
                            </tr>
                        </table>
                    </div>
                    <?php $i++ ?>
                    @endforeach

                </div>
            </div>
        </div>

        <?php $user = Auth::id(); ?>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="card-title">Upcoming Match</h4>
                </div>
                <div class="card-body bg-light-primary">
                    
                    @foreach ($dataMatch as $item)
                        <h4 style="margin-bottom: 15px;">{{ $item['date'] }}</h4>

                        <?php 
                            $in = App\Models\Transaksi\TMatchReferee::where('id_t_match', '=', $item['data'][0]['id'])->where('wasit', '=', $user)->get()->toArray();
                        ?>

                        <div class="timeline-label" style="margin-bottom: 15px;">
                            <div class="timeline-item">
                                <div class="timeline-label fw-bolder text-gray-800 fs-6">{{ date('H:i', strtotime($item['data'][0]['waktu_pertandingan'])) }}</div>
                                <div class="timeline-badge">
                                    @if (empty($in))
                                        <i class="fa fa-genderless text-primary fs-1"></i>   
                                    @else
                                        <i class="fa fa-genderless text-success fs-1"></i>  
                                    @endif
                                </div>
    
                                <div class="timeline-content d-flex">
                                    <span class="fw-bolder text-gray-800 ps-3">{{ $item['data'][0]['pertandingan'] }} ({{ $item['data'][0]['event'] }})</span>
                                </div>
                            </div>
                            <?php $i = 0 ?>
                            @foreach ($item['data'] as $subitem)
                                @if ($i > 0)
                                    <?php 
                                        $in = App\Models\Transaksi\TMatchReferee::where('id_t_match', '=', $subitem['id'])->where('wasit', '=', $user)->get()->toArray();
                                    ?>

                                    <div class="timeline-item">
                                        <div class="timeline-label fw-bolder text-gray-800 fs-6">{{ date('H:i', strtotime($subitem['waktu_pertandingan'])) }}</div>
                                        
                                        <div class="timeline-badge">
                                            @if (empty($in))
                                                <i class="fa fa-genderless text-primary fs-1"></i>   
                                            @else
                                                <i class="fa fa-genderless text-success fs-1"></i>  
                                            @endif
                                        </div>

                                        <div class="timeline-content d-flex">
                                            <span class="fw-bolder text-gray-800 ps-3">{{ $subitem['pertandingan'] }} ({{ $subitem['event'] }})</span>
                                        </div>
                                    </div>
                                @endif
                            <?php $i++ ?>
                            @endforeach
                        </div>

                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-base-layout>
