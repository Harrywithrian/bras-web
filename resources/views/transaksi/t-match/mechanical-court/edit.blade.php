<x-base-layout>
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
    $title = 'Edit Mechanical Court : ' . $wst->name;
    $i = 1;
    $arr = array_merge(range('a', 'z'));
    ?>

        <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
            <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"> <i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home </a></li>
            <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.index-event') }}" class="pe-3"> List Event </a></li>
            <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.index', $event->id) }}" class="pe-3"> Pertandingan Event {{ $event->nama }} </a></li>
            <li class="breadcrumb-item pe-3"><a href="{{ route('t-match.show', $model->id) }}" class="pe-3"> Pertandingan {{ $model->nama }} </a></li>
            <li class="breadcrumb-item pe-3"><a href="{{ route('mechanical-court.show', [$id, $wasit]) }}" class="pe-3"> Mechanical Court : {{ $wst->name }} </a></li>
            <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
        </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#1e1e2d; color:white;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">

            <form method="post" action="{{ route('mechanical-court.update', [$id, $wasit]) }}">
            @csrf
                <table id="template-preview" class="table">
                    <tr>
                        <td width="3%"><b> No </b></td>
                        <td><b> Nama </b></td>
                        <td width="20%"><center><b> Penilaian </b></center></td>
                    </tr>
                    @foreach($data as $item)
                        <tr>
                            <td><b>{{ $i }}</b></td>
                            <td colspan="4"><b>{{ $item['nama'] }}</b></td>
                        </tr>
                        <?php
                            $child = \App\Models\Master\MMechanicalCourt::where('id_m_mechanical_court', '=', $item['id'])->whereNull('deletedon')->orderBy('order_by')->get()->toArray();
                            $j = 0;
                        ?>
                        @if($child)
                            @foreach($child as $subitem)
                                <tr>
                                    <td></td>
                                    <td>{{ $arr[$j] }}. {{ $subitem['nama'] }}</td>
                                    <td>
                                        <?php
                                            $oldItem = null;
                                            if (old('index')) {
                                                if (!empty(old('index')[$subitem['id']])) {
                                                    $oldItem = old('index')[$subitem['id']];
                                                } else {
                                                    if (!empty($dataMC[$subitem['id']])) {
                                                        $oldItem = $dataMC[$subitem['id']];
                                                    }
                                                }
                                            } else {
                                                if (!empty($dataMC[$subitem['id']])) {
                                                    $oldItem = $dataMC[$subitem['id']];
                                                }
                                            }
                                        ?>
                                        <select class="form-select" name="index[{{ $subitem['id'] }}]">
                                            <option value=""></option>
                                            <option value="100" @if ($oldItem) {{ ($oldItem == 100) ? 'selected' : null ; }} @endif>Baik Sekali</option>
                                            <option value="90" @if ($oldItem) {{ ($oldItem == 90) ? 'selected' : null ; }} @endif>Baik</option>
                                            <option value="80" @if ($oldItem) {{ ($oldItem == 80) ? 'selected' : null ; }} @endif>Cukup</option>
                                            <option value="70" @if ($oldItem) {{ ($oldItem == 70) ? 'selected' : null ; }} @endif>Kurang</option>
                                            <option value="60" @if ($oldItem) {{ ($oldItem == 60) ? 'selected' : null ; }} @endif>Buruk</option>
                                        </select>
                                    </td>
                                </tr>
                                <?php $j++; ?>
                            @endforeach
                        @endif
                        <?php $i++; ?>
                    @endforeach
                </table>

                <div class="form-group float-end">
                    <button type="submit" class="btn btn-primary"> Submit </button>
                    <a href="{{ route('mechanical-court.show', [$id, $wasit]) }}" class="btn btn-secondary"> Kembali </a>
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
        <script>
            @if(\Illuminate\Support\Facades\Session::has('error'))
                var msg = JSON.parse('<?php echo json_encode(\Illuminate\Support\Facades\Session::get('error')); ?>');
                toastr['error'](msg, 'Error', {
                    closeButton: true,
                    tapToDismiss: false,
                    rtl: false
                });
            @endif
        </script>
    @endsection

</x-base-layout>