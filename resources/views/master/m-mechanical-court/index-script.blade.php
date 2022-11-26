<script>
    $(document).ready(function() {
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });

        $('#search').keyup(function(e){
            if(e.which == 13){ //Enter key pressed
                $('#cari').click(); //Trigger search button click event
            }
        });

        showAllData();

        $("#level").select2({
            // the following code is used to disable x-scrollbar when click in select input and
            // take 100% width in responsive also
            placeholder: "Pilih ...",
            dropdownAutoWidth: true,
            width: '100%'
        });
    });

    /* FUNGSI MENAMPILKAN SEMUA DATA */
    function showAllData() {
        var mainTable = $('#content-table');
        mainTable.DataTable().clear().destroy();

        mainTable.DataTable({
            ajax: {
                type:"POST",
                url: "/m-mechanical-court/get",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                data: function (d) {
                    d.search  = $('#input-search').val();
                    d.level = $('#level').val();
                }
            },
            bFilter: false,
            processing: true,
            serverSide: true,
            language: {
                sEmptyTable: "Tidak ada data yang tersedia pada tabel ini",
                sProcessing: "Sedang memproses...",
                sLengthMenu: "Tampilkan data _MENU_",
                sZeroRecords: "Tidak ditemukan data yang sesuai",
                sInfo: "_START_ - _END_ dari _TOTAL_",
                sInfoEmpty: "0 - 0 dari 0",
                sInfoFiltered: "(disaring dari _MAX_ data keseluruhan)",
                sInfoPostFix: "",
                sSearch: "",
                searchPlaceholder: "Cari ...",
                sUrl: "",
                oPaginate: {
                    sFirst: "pertama",
                    sPrevious: "sebelumnya",
                    sNext: "selanjutnya",
                    sLast: "terakhir"
                }
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    name: "DT_RowIndex",
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: "20%"
                },
                {
                    data: 'nama',
                    name: 'nama',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'level',
                    name: 'level',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'parent',
                    name: 'parent',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'persentase',
                    name: 'persentase',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'order_by',
                    name: 'order_by',
                    orderable: true,
                    searchable: true
                }
            ]
        });
    }

    function search(event) {
        event.preventDefault();
        showAllData();
    }

    function resets(event) {
        event.preventDefault();
        document.getElementById('search').reset();
        $("#level").val('').trigger('change');
        showAllData();
    }

    $("body").on("click", ".deleted", function () {
        var id     = $(this).data("id");
        var table  = $('#content-table').DataTable();
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
                loadingScreen('Mohon Tunggu ...');
                $.ajax({
                    url: '/m-mechanical-court/delete',
                    type: 'POST',
                    data: {
                        _token: token,
                        id: id
                    },
                    success: function (response) {
                        if (response.status == 200) {
                            $.unblockUI();
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
                            $.unblockUI();
                            Swal.fire({
                                icon: "warning",
                                title: response.header,
                                text: response.message,
                                confirmButtonClass: 'btn btn-success'
                            }).then(function (result) {
                                if (result.value) {
                                    table.draw();
                                }
                            });
                        }
                    }
                });
            }
        });
    });

    function loadingScreen(msg) {
        var $white = '#fff';
        var src = $("#logo_ibr").attr('src');
        src = src.replace("logo_dark", "logo");
        $.blockUI({
            message: '<img src="' + src + '" style="height: 80px; width: auto"> <br><br> <h3>' + msg + '</h2>',
            timeout: 5000, //unblock after 5 seconds
            overlayCSS: {
                backgroundColor: $white,
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
    }
</script>