<x-base-layout>
  <?php $title = 'Match Evaluation' ?>

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
      <div>
        <div x-data="{ open: false }">
          <span @click="open = true">xx</span>

          <div x-show="open" @click.away="open = false">
            aa
          </div>
        </div>
      </div>


      <!-- referee -->
      <div class="d-flex flex-column flex-lg-row my-10" style="gap: 15px;">

        <div class="flex-fill">
          <!--begin::Option-->
          <input type="radio" class="btn-check" name="radio_buttons_2" value="apps" checked="checked" id="kt_radio_buttons_2_option_1" />
          <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center mb-5" for="kt_radio_buttons_2_option_1">
            <div class="image-input image-input-circle me-5" data-kt-image-input="true" style="background-image: url( {{ asset('/demo1/media/avatars/150-1.jpg')}} )">
              <div class="image-input-wrapper w-50px h-50px" style="background-image: url({{ asset('/demo1/media/avatars/150-1.jpg')}})"></div>
            </div>

            <span class="d-block fw-bold text-start">
              <span class="text-dark fw-bolder d-block fs-3 test">Referee 1</span>
              <span class="text-muted fw-bold fs-6">
                Referee 1 info
              </span>
            </span>
          </label>
          <!--end::Option-->
        </div>


        <div class="flex-fill">
          <!--begin::Option-->
          <input type="radio" class="btn-check" name="radio_buttons_2" value="sms" id="kt_radio_buttons_2_option_2" />
          <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center" for="kt_radio_buttons_2_option_2">

            <div class="image-input image-input-circle me-5" data-kt-image-input="true" style="background-image: url( {{ asset('/demo1/media/avatars/150-1.jpg')}} )">
              <div class="image-input-wrapper w-50px h-50px" style="background-image: url({{ asset('/demo1/media/avatars/150-1.jpg')}})"></div>
            </div>

            <span class="d-block fw-bold text-start">
              <span class="text-dark fw-bolder d-block fs-3 test">Referee 2</span>
              <span class="text-muted fw-bold fs-6">
                Referee 2 info
              </span>
            </span>
          </label>
          <!--end::Option-->
        </div>

        <div class="flex-fill">
          <!--begin::Option-->
          <input type="radio" class="btn-check" name="radio_buttons_2" value="sms" id="kt_radio_buttons_2_option_3" />
          <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center" for="kt_radio_buttons_2_option_3">
            <div class="image-input image-input-circle me-5" data-kt-image-input="true" style="background-image: url( {{ asset('/demo1/media/avatars/150-1.jpg')}} )">
              <div class="image-input-wrapper w-50px h-50px" style="background-image: url({{ asset('/demo1/media/avatars/150-1.jpg')}})"></div>
            </div>

            <span class="d-block fw-bold text-start">
              <span class="text-dark fw-bolder d-block fs-3 test">Referee 3</span>
              <span class="text-muted fw-bold fs-6">
                Referee 3 info
              </span>
            </span>
          </label>
          <!--end::Option-->
        </div>

      </div>

      <!-- referee evalution -->
      <div class="d-flex flex-column flex-lg-row" style="gap: 15px;">

        @foreach($evaluation_data as $evaluation_type)
        <div class="flex-fill flex-column">
          <h6>{{ $evaluation_type->title }}</h6>
          <div data-kt-buttons="true">

            @foreach($evaluation_type->data as $evaluation_type_item)
            <label class="btn btn-outline btn-outline-dashed d-flex flex-stack text-start p-3 mb-3 active">
              <div class="d-flex align-items-center">
                <div class="form-check form-check-custom form-check-solid form-check-primary me-2 form-check-sm">
                  <input class="form-check-input" type="radio" name="{{ $evaluation_type->identifier}}" value="`@json($evaluation_type_item)`" data- />
                </div>

                <div class="flex-grow-1">
                  <h6 class="d-flex align-items-center flex-wrap me-0 m-0">
                    {{ $evaluation_type_item->text}}
                  </h6>
                </div>
              </div>
            </label>
            @endforeach

          </div>
        </div>
        @endforeach

      </div>


      <!-- table -->
    </div>
  </div>

  @section('styles')
  <style>
    .test {
      /* color: red !important; */
    }
  </style>
  @endsection

  @section('scripts')
  <script src="{{asset('demo1/plugins/custom/datatables/datatables.bundle.js')}}"></script>

  <script>
    $(document).ready(() => {
      
      // evaluate value whenever violation click
      const evaluate = () => {
        const callAnalyis = +$( "input[type=radio][name=call_analysis]:checked" ).val() || 0
        const position = +$( "input[type=radio][name=position]:checked" ).val() || 0
        const zoneBox = +$( "input[type=radio][name=zone_box]:checked" ).val() || 0
        const callType = +$( "input[type=radio][name=call_type]:checked" ).val() || 0
        const iot = +$( "input[type=radio][name=iot]:checked" ).val() || 0

        console.log(callAnalyis, position, zoneBox, callType, iot)

        const evaluationPoint = callAnalyis - (position + zoneBox + callType + iot)
        console.log(evaluationPoint)

        // const evaluation = {
        //   callAnalysis: {
        //     id:
        //   }
        // }
      }

      // violation handler
      $( "input[type=radio][name=call_analysis]" ).on( "click", evaluate )
      $( "input[type=radio][name=position]" ).on( "click", evaluate )
      $( "input[type=radio][name=call_analysis]" ).on( "click", evaluate )
      $( "input[type=radio][name=zone_box]" ).on( "click", evaluate )
      $( "input[type=radio][name=call_type]" ).on( "click", evaluate )
      $( "input[type=radio][name=iot]" ).on( "click", evaluate )
    })
  </script>
  @endsection

</x-base-layout>