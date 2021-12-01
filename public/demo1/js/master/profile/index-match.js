$(document).ready(function() {
    showAllData();
});

/* FUNGSI MENAMPILKAN SEMUA DATA */
function showAllData() {
    var user = $('#id_user').val();
    $('#content-table').DataTable({
        ajax: "/profile/match/" + user,
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
                data: 'match',
                name: 'match',
                title: 'Pertandingan',
                orderable: true,
                searchable: true
            },
            {
                data: 'event',
                name: 'event',
                title: 'event',
                orderable: true,
                searchable: true
            },
            {
                data: 'waktu_pertandingan',
                name: 'waktu_pertandingan',
                orderable: true,
                searchable: true
            }
        ]
    });
}

function search(event) {
    event.preventDefault();
    var wasit = $('#id_user').val();
    var mainTable = $('#content-table');
    mainTable.DataTable().clear().destroy();

    mainTable.DataTable({
        ajax: {
            type: 'post',
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            url: '/profile/search-match',
            data: function (d) {
                d.wasit      = wasit;
                d.nama       = $('#nama').val();
                d.event      = $('#event').val();
                d.status     = $('#status').val();
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
                data: 'match',
                name: 'match',
                title: 'Pertandingan',
                orderable: true,
                searchable: true
            },
            {
                data: 'event',
                name: 'event',
                title: 'event',
                orderable: true,
                searchable: true
            },
            {
                data: 'waktu_pertandingan',
                name: 'waktu_pertandingan',
                orderable: true,
                searchable: true
            }
        ]
    });
}

function resets(event) {
    event.preventDefault();
    var mainTable = $('#content-table');
    mainTable.DataTable().clear().destroy();
    document.getElementById('search').reset();
    showAllData();
}