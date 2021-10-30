$(document).ready(function() {
    showAllData();
});

/* FUNGSI MENAMPILKAN SEMUA DATA */
function showAllData() {
    $('#content-table').DataTable({
        ajax: "/m-mechanical-court/get",
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
    var mainTable = $('#content-table');
    mainTable.DataTable().clear().destroy();

    mainTable.DataTable({
        ajax: {
            type: 'post',
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            url: '/m-mechanical-court/search',
            data: function (d) {
                d.nama     = $('#nama').val();
                d.parent   = $('#parent').val();
                d.level    = $('#level').val();
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

function reset(event) {
    event.preventDefault();
    var mainTable = $('#content-table');
    mainTable.DataTable().clear().destroy();
    document.getElementById('search').reset();
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
            $.ajax({
                url: '/m-mechanical-court/delete',
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
                                window.location.replace("/m-mechanical-court/index");
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
                                table.draw();
                            }
                        });
                    }
                }
            });
        }
    });
});