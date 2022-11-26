<x-base-layout>
    <style>
        tr td {
            border: 1px solid #ddd !important;
        }
    </style>

    <?php $title = $model->nama ?>

    <ol class="breadcrumb text-muted fs-6 fw-bold mb-5">
        <li class="breadcrumb-item pe-3"><a href="{{ route('index') }}" class="pe-3"><i class="bi bi-house-door" style="margin-bottom:5px;"></i> Home</a></li>
        <li class="breadcrumb-item pe-3"><a href="{{ route('m-game-management.index') }}" class="pe-3">Template Game Management</a></li>
        <li class="breadcrumb-item px-3 text-muted">{{ $title }}</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#181C32;">
            <h3 class="card-title text-light"> {{ $title }} </h3>
        </div>

        <div class="card-body">

            @if($model->level == 1)
                <a href="{{ route('m-game-management.edit-header', $model->id) }}" class="btn btn-warning"> Edit </a>
            @else
                <a href="{{ route('m-game-management.edit-content', $model->id) }}" class="btn btn-warning"> Edit </a>
            @endif
            <button class="btn btn-danger" id="delete" data-id="{{ $model->id }}" onClick="hapus(event)"> Delete </button>
            <a href="{{ route('m-game-management.index') }}" class="btn btn-secondary"> Kembali </a>

            <br><br>

            <section class="card bg-primary mb-0" style="border-radius: 0">
                <div class="card-header">
                    <h4 class="card-title" style="color: white;">General</h4>
                </div>
            </section>
            <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                <tr>
                    <td width="25%">Nama</td>
                    <td>{{ $model->nama }}</td>
                </tr>
                <tr>
                    <td width="25%">Level</td>
                    <td>{{ ($model->level == 1) ? 'Header' : 'Content' ; }}</td>
                </tr>
                @if($model->level != 1)
                <tr>
                    <td width="25%">Header</td>
                    <td>{{ ( $header) ? $header->nama : '-' ; }} </td>
                </tr>
                @endif
                @if($model->level == 1)
                    <tr>
                        <td width="25%">Persentase</td>
                        <td>{{ $model->persentase . " %" }}</td>
                    </tr>
                @endif
                <tr>
                    <td width="25%">Urutan</td>
                    <td>{{ $model->order_by }}</td>
                </tr>
            </table>

            <section class="card bg-primary mt-0 mb-0" style="border-radius: 0">
                <div class="card-header">
                    <h4 class="card-title" style="color: white;">System</h4>
                </div>
            </section>
            <table class="table table-striped border mb-0 gy-7 gs-7" style="margin-top:-5px;">
                <tr>
                    <td width="25%">Dibuat Oleh</td>
                    <td>{{ \App\Models\User::find($model->createdby)->name }}</td>
                </tr>
                <tr>
                    <td width="25%">Waktu Dibuat</td>
                    <td>{{ date("H:i:s d-m-Y", strtotime($model->createdon)) }}</td>
                </tr>
                <tr>
                    <td width="25%">Diubah Oleh</td>
                    <td>{{ \App\Models\User::find($model->modifiedby)->name }}</td>
                </tr>
                <tr>
                    <td width="25%">Waktu Diubah</td>
                    <td>{{ date("H:i:s d-m-Y", strtotime($model->modifiedon)) }}</td>
                </tr>
            </table>

        </div>
    </div>

    @section('scripts')
        <script>
            $(document).ready( function() {
                @if(\Illuminate\Support\Facades\Session::has('success'))
                    var msg = JSON.parse('<?php echo json_encode(\Illuminate\Support\Facades\Session::get('success')); ?>');
                    toastr['success'](msg, 'Success', {
                        closeButton: true,
                        tapToDismiss: false,
                        rtl: false
                    });
                @endif

                @if(\Illuminate\Support\Facades\Session::has('error'))
                    var msg = JSON.parse('<?php echo json_encode(\Illuminate\Support\Facades\Session::get('error')); ?>');
                    toastr['error'](msg, 'Error', {
                        closeButton: true,
                        tapToDismiss: false,
                        rtl: false
                    });
                @endif
            });

            function hapus(event) {
                event.preventDefault();
                var id = $('#delete').data("id");
                var token  = $("meta[name='csrf-token']").attr("content");

                Swal.fire({
                    title: "Delete Data",
                    text: 'Apakah anda akan menghapus data ini?',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Hapus"
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: '/m-game-management/delete',
                            type: 'POST',
                            data: {
                                _token: token,
                                id: id
                            },
                            success: function (response) {
                                if (response.status == 200) {
                                    Swal.fire({
                                        icon: "success",
                                        title: response.header,
                                        text: response.message,
                                        confirmButtonClass: 'btn btn-success'
                                    }).then(function (result) {
                                        if (result.value) {
                                            window.location.replace("/m-game-management/index");
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: "warning",
                                        title: response.header,
                                        text: response.message,
                                        confirmButtonClass: 'btn btn-success'
                                    }).then(function (result) {
                                        if (result.value) {
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            }
        </script>
    @endsection

</x-base-layout>