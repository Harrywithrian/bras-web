<x-base-layout>
  <?php $title = 'Match Play Calling' ?>

  <link href="{{asset('demo1/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />

  <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
    <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"> <i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home </a></li>
    <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
  </ol>

  <div class="card shadow-sm" id="main-layout">
    <div class="card-header" style="background-color:#1e1e2d; color:white;">
      <h3 class="card-title text-light">{{ $title }}</h3>
    </div>

    <div class="card-body">
      <!-- quarter -->
      <div class="d-flex flex-direction-column justify-content-center w-100">
        <div class="w-25">
          <select class="form-select form-select-solid" data-placeholder="Select quarter" name="quarter" id="quarter-picker">
            <option></option>
            <option value="1"> Quarter 1</option>
            <option value="2"> Quarter 2</option>
            <option value="3"> Quarter 3</option>
            <option value="4"> Quarter 4</option>
          </select>
        </div>
      </div>

      <!-- timer -->
      <div>
        <div class="d-flex flex-direction-column justify-content-center align-item-center">
          <span class="fs-5tx" id="timer-display"> --:-- </span>
        </div>
        <div class="d-flex justify-content-center hide-content" style="gap: 20px;" id="timer-control">
          <div class="btn btn-white btn-icon" id="timer-start"> <i class="fas fa-play-circle fs-2tx"></i> </div>
          <div class="btn btn-white btn-icon" id="timer-pause"> <i class="fas fa-pause-circle fs-2tx"></i> </div>
          <div class="btn btn-white btn-icon" id="timer-stop"> <i class="fas fa-stop-circle fs-2tx"></i> </div>
        </div>
      </div>



      <!-- referee -->
      <div class="d-flex flex-column flex-lg-row my-10" style="gap: 20px;">

        @foreach($match->referee as $referee)
        <div style="flex-basis: 0; flex-grow: 1;">
          <!--begin::Option-->
          <input type="radio" class="btn-check" name="referee" value="{{ $referee->user->id }}" data-value='@json($referee->user)' id="kt_radio_referee_{{$referee->user->id}}" />
          <label class="btn btn-outline btn-outline-dashed btn-outline-default py-3 px-3 d-flex align-items-center mb-5" for="kt_radio_referee_{{$referee->user->id}}">
            <div class="image-input image-input-circle me-5" data-kt-image-input="true" style="background-image: url( {{ asset($referee->user->info->getAvatarUrlAttribute())}} )">
              <div class="image-input-wrapper w-100px h-100px" style="background-image: url({{ asset($referee->user->info->getAvatarUrlAttribute())}})"></div>
            </div>

            <span class="d-block fw-bold text-start">
              <span class="text-dark fw-bolder d-block fs-3 test">{{ $referee->user->name }}</span>
              <span class="text-muted fw-bold fs-6">
                {{ $referee->posisi }}
              </span>
            </span>
          </label>
          <!--end::Option-->
        </div>
        @endforeach

      </div>

      <!-- referee evalution -->
      <div class="d-flex flex-column flex-lg-row" style="gap: 20px;">

        @foreach($play_calling_data as $play_calling_type)
        <div class="flex-fill flex-column">
          <h6>{{ $play_calling_type->title }}</h6>
          <div data-kt-buttons="true">

            @foreach($play_calling_type->data as $play_calling_type_item)
            <label class="btn btn-outline btn-outline-dashed d-flex flex-stack text-start p-3 mb-3 active">
              <div class="d-flex align-items-center">
                <div class="form-check form-check-custom form-check-solid form-check-primary me-2 form-check-sm">
                  <input class="form-check-input" type="{{ $play_calling_type->identifier_type }}" name="{{ $play_calling_type->identifier}}" value="{{ $play_calling_type_item->value }}" data-value='@json($play_calling_type_item)' />
                </div>

                <div class="flex-grow-1">
                  <h6 class="d-flex align-items-center flex-wrap me-0 m-0">
                    {{ $play_calling_type_item->text}}
                  </h6>
                </div>
              </div>
            </label>
            @endforeach

          </div>
        </div>
        @endforeach

      </div>


      <div class="d-grid my-5">
        <div class="btn btn-light-primary hide-content" id="add-play-calling"> Tambah </div>
      </div>

      <!-- table -->
      <div class="row">
        <div class="col-12">
          <div id="main-table">
            <table id="content-table" class="table table-hover table-rounded table-row-bordered border gy-5 gs-5" style="width:100%;">
              <thead>
                <tr>
                  <!-- <th>No</th> -->
                  <!-- <th>Act</th> -->
                  <th>Quarter</th>
                  <th>Nama Wasit</th>
                  <th>Time</th>
                  <th>Call Analysis</th>
                  <th>Posisi</th>
                  <th>Zone Box</th>
                  <th>Call Type</th>
                  <th>IOT</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>


      <form id="form-play-calling" action="{{ route('t-match.play-calling.store', $match->id) }}" method="POST" style="display: none;">
        @csrf
        @method('POST')
        <input type="hidden" name="play_calling" />
      </form>

      <div class="d-grid my-5">
        <div class="btn btn-light-primary" id="submit-play-calling"> Simpan </div>
      </div>


    </div>
  </div>

  @section('styles')
  <style>
    .hide-content {
      /* color: red !important; */
      visibility: hidden;
    }

    .show-content {
      visibility: visible;
    }
  </style>
  @endsection

  @section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.10.7/dayjs.min.js" integrity="sha512-bwD3VD/j6ypSSnyjuaURidZksoVx3L1RPvTkleC48SbHCZsemT3VKMD39KknPnH728LLXVMTisESIBOAb5/W0Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.10.7/plugin/duration.min.js" integrity="sha512-4/QKmrYrL+3JbEBiIxAUwlsjv1duqB5biE640aqvCJEqgTfyhmCA9WeqJmVfQdeh2hqK9+Fc9WFVpel4N2O/1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="{{asset('demo1/js/transaksi/t-match/play-calling.js')}}"></script>
  <script src="{{asset('demo1/js/transaksi/t-match/timer.js')}}"></script>
  <script src="{{asset('demo1/js/transaksi/t-match/quarter.js')}}"></script>
  <script src="{{asset('demo1/js/transaksi/t-match/index-play-calling.js')}}"></script>
  <script src="{{asset('demo1/plugins/custom/datatables/datatables.bundle.js')}}"></script>
  @endsection

</x-base-layout>