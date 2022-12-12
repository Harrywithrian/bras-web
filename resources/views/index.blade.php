<x-base-layout>
    <div class="row">
        <div class="col-md-12">
            <h3 style="text-align: right; margin-bottom:-10px;">{{ $data['tanggal'] }}</h3>
        </div>
    </div>

    <br>

    <div class="row">
        @if(isset($data['total_wasit']))
            <div class="col-md-4">
                <div class="card shadow-sm" style="border-radius:20px;">
                    <div class="card-body text-light" style="border-radius:20px;padding-top:20px;padding-bottom:10px;background-image: url('{{ asset(theme()->getMediaUrlPath() . 'logos/bg-2.png') }}');background-repeat: no-repeat;background-size: 100% auto;">
                        <h2 class="text-light" style="text-align: left;">Total Wasit</h2>
                        <p style="text-align: right; font-size:50px">{{ $data['total_wasit'] }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($data['total_event']))
            <div class="col-md-4">
                <div class="card shadow-sm" style="border-radius:20px;">
                    <div class="card-body text-light" style="border-radius:20px;padding-top:20px;padding-bottom:10px;background-image: url('{{ asset(theme()->getMediaUrlPath() . 'logos/bg-2.png') }}');background-repeat: no-repeat;background-size: 100% auto;">
                        <h2 class="text-light" style="text-align: left;">Total Event</h2>
                        <p style="text-align: right; font-size:50px">{{ $data['total_event'] }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($data['total_pertandingan']))
            <div class="col-md-4">
                <div class="card shadow-sm" style="border-radius:20px;">
                    <div class="card-body text-light" style="border-radius:20px;padding-top:20px;padding-bottom:10px;background-image: url('{{ asset(theme()->getMediaUrlPath() . 'logos/bg-2.png') }}');background-repeat: no-repeat;background-size: 100% auto;">
                        <h2 class="text-light" style="text-align: left;">Total Pertandingan</h2>
                        <p style="text-align: right; font-size:50px">{{ $data['total_pertandingan'] }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <br>

    <div class="row">
        @if(isset($data['rank']))
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        
                        <h4 class="card-title">Referee Rank</h4>
    
                        <?php $i = 1 ?>
                        @foreach($data['rank'] as $item)
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
        @endif
    </div>
</x-base-layout>
