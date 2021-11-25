$(document).ready(function() {
    showAllData();
});

/* FUNGSI MENAMPILKAN SEMUA DATA */
function showAllData() {
    $('#content-table').DataTable({
        ajax: "/m-user/get",
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
                searchable: false
            },
            {
                data: 'status',
                name: 'status',
                orderable: false,
                searchable: false
            },
            {
                data: 'username',
                name: 'username',
                orderable: true,
                searchable: true
            },
            {
                data: 'name',
                name: 'name',
                title: 'nama',
                orderable: true,
                searchable: true
            },
            {
                data: 'role',
                name: 'role',
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
            url: '/m-user/search',
            data: function (d) {
                d.username = $('#username').val();
                d.nama     = $('#nama').val();
                d.status   = $('#status').val();
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
                searchable: false
            },
            {
                data: 'status',
                name: 'status',
                orderable: false,
                searchable: false
            },
            {
                data: 'username',
                name: 'username',
                orderable: true,
                searchable: true
            },
            {
                data: 'name',
                name: 'name',
                title: 'nama',
                orderable: true,
                searchable: true
            },
            {
                data: 'role',
                name: 'role',
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

$("body").on("click", ".switchStatus", function () {
    var id     = $(this).data("id");
    var toogle = $(this).data("toogle");
    var table  = $('#content-table').DataTable();
    var token  = $("meta[name='csrf-token']").attr("content");

    if (toogle == 'inactive') {
        warningMessage = 'Apakah anda akan menonaktifkan data ini?';
        buttonName = "Non-Aktifkan";
    } else {
        warningMessage = 'Apakah anda akan mengaktifkan data ini?';
        buttonName = "Aktifkan";
    }

    Swal.fire({
        title: "Change Data Status",
        text: warningMessage,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: buttonName
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: '/m-user/status',
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
                                table.draw();
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

$("body").on("click", ".switchLock", function () {
    var id     = $(this).data("id");
    var toogle = $(this).data("toogle");
    var table  = $('#content-table').DataTable();
    var token  = $("meta[name='csrf-token']").attr("content");

    warningMessage = 'Apakah anda akan mengunci user ini?';
    buttonName = "Kunci";

    Swal.fire({
        title: "Lock Data",
        text: warningMessage,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: buttonName
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: '/m-user/lock',
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
                                table.draw();
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