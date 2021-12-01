<x-base-layout>
  <!-- <style>
        #template-preview {
            border: 1px solid #ddd;
        }
        #template-preview td, #template-preview th {
            border: 1px solid #ddd;
            padding: 8px;
        }
    </style> -->

  <?php
  $title = 'Play Calling : ' . $summary->name;
  // $i     = 1;
  // $arr   = array_merge(range('a', 'z'));
  ?>

  <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
    <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"> <i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home </a></li>
    <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.index-event') }}" class="pe-3"> List Event </a></li>
    <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.index', $match->event->id) }}" class="pe-3"> Pertandingan Event {{ $match->event->nama }} </a></li>
    <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.show', $match->id) }}" class="pe-3"> Pertandingan {{ $match->nama }} </a></li>
    <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
  </ol>


  <div class="card shadow-sm">
    <div class="card-header" style="background-color:#1e1e2d; color:white;">
      <h3 class="card-title text-light"> {{ $title }} </h3>
    </div>

    <div class="card-body">

      <a href="{{ route('t-match.show', $match->id) }}" class="btn btn-secondary mb-5"> Kembali </a>

      <div class="row">
        <div class="col-12">
          <div id="main-table">
            <table id="content-table" class="table table-hover table-rounded table-row-bordered border gy-5 gs-5" style="width:100%;">
              <thead>
                <tr>
                  <!-- <th>No</th> -->
                  <!-- <th>Act</th> -->
                  <th>Quarter</th>
                  <!-- <th>Nama Wasit</th> -->
                  <th>Time</th>
                  <th>Call Analysis</th>
                  <th>Position</th>
                  <th>Zone Box</th>
                  <th>Call Type</th>
                  <th>IOT</th>
                  <th>Score</th>
                </tr>
              </thead>
              <tbody>
                @foreach($summary->playCalling as $play_calling)
                <tr>
                  <td>
                    {{ $play_calling->quarter }}
                  </td>
                  <td>
                    {{ $play_calling->time }}
                  </td>
                  <td>
                    {{ $play_calling->call_analysis }}
                  </td>
                  <td>
                    {{ $play_calling->position }}
                  </td>
                  <td>
                    {{ $play_calling->zone_box }}
                  </td>
                  <td>
                    {{ $play_calling->call_type }}
                  </td>
                  <td>
                    @foreach($play_calling->playCallingIot as $play_calling_iot)
                    <div> - {{ $play_calling_iot->iot }} </div>
                    @endforeach
                  </td>
                  <td>
                    {{ $play_calling->score }}
                  </td>
                </tr>
                @endforeach
                <tr>
                  <td colspan="7">
                    Total
                  </td>
                  <td>
                    {{ $summary->playCalling ->sum('score')}}
                  </td>
                </tr>
                {{--<tr>--}}
                  {{--<td colspan="7">--}}
                    {{--Average--}}
                  {{--</td>--}}
                  {{--<td>--}}
                    {{--{{ $summary->playCalling ->avg('score')}}--}}
                  {{--</td>--}}
                {{--</tr>--}}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  @section('scripts')

  @endsection

</x-base-layout>