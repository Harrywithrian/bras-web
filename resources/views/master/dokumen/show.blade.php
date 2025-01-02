<x-base-layout>
    <style>
        tr td {
            border: 1px solid #ddd !important;
        }
    </style>

    <?php $title = $model->nama_dokumen ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('dokumen.index') }}" class="pe-3">Dokumen</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#181C32;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">
            <a href="{{ route('dokumen.index') }}" class="btn btn-secondary"> Kembali </a>

            <br><br>

            <section class="card bg-primary mb-0" style="border-radius: 0">
                <div class="card-header">
                    <h4 class="card-title" style="color: white;">General</h4>
                </div>
            </section>
            <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                <tr>
                    <td width="25%">Nama Dokumen</td>
                    <td>{{ $model->nama_dokumen }}</td>
                </tr>
                <tr>
                    <td width="25%">Deskripsi</td>
                    <td>{{ ($model->deskripsi) ? $model->deskripsi : "-" }}</td>
                </tr>
                <tr>
                    <td width="25%">Tanggal Upload</td>
                    <td>{{ date('d-m-Y', strtotime($model->createdon)) }}</td>
                </tr>
            </table>

            <br>

            <center>
                @if($model->extension == 'pdf')
                    <iframe 
                        src="{{ route('dokumen.read', $model->id) }}"
                        width="70%" 
                        height="1000px" 
                        style="border: none;">
                    </iframe>
                @elseif($model->extension == 'mp4')
                    <iframe 
                        src="{{ route('dokumen.read', $model->id) }}"
                        width="100%" 
                        height="500px" 
                        style="border: none;">
                    </iframe>
                @endif
            </center>
        </div>
    </div>

    @section('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.js"></script>
    @endsection

</x-base-layout>