<x-base-layout>
  <?php $title = 'Match' ?>

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

    </div> test
  </div>

  </div>
  </div>

  @section('scripts')
  <script src="{{asset('demo1/plugins/custom/datatables/datatables.bundle.js')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/@snapboard/flipdown@0.2.3/src/flipdown.min.js"></script>

  <script>
    // console.log(new FlipDown())
  </script>
  @endsection

</x-base-layout>