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

    @if($dokumenList)
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Dokumen Penting</h4>
                    <hr>

                    <table class="table table-bordered table-striped table-hover">
                        <tr>
                            <td style="padding:10px;font-weight:bold">Dokumen Terbaru</td>
                            <td style="padding:10px;font-weight:bold">Tanggal Upload</td>
                        </tr>
                        @foreach($dokumenList as $item)
                        <tr>
                            <td style="padding:10px;"><a href="{{ route('dokumen.show', ['id' => $item['id']]) }}">{{ $item['nama_dokumen'] }}</a></td>
                            <td style="padding:10px;">{{ date('d-m-Y', strtotime($item['createdon'])) }}</td>
                        </tr>
                        @endforeach
                    </table>

                    {{-- <ul>
                        <li><a href="{{ route('getpdf', ['filename' => 'FIBA_RULES_CHANGES_2024_v2_0a']) }}" target="_blank">FIBA_RULES_CHANGES_2024_v2_0a</a></li>
                        <li><a href="{{ route('getpdf', ['filename' => 'FIBAOfficialBasketballRules2024_v1_0a']) }}" target="_blank">FIBAOfficialBasketballRules2024_v1_0a</a></li>
                    </ul> --}}
                </div>
            </div>
        </div>
    </div>
    @endif

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
