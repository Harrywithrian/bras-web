$(document).ready(function() {
    showAllData();
});

/* FUNGSI MENAMPILKAN SEMUA DATA */
function showAllData() {
    $('#content-table').DataTable({
        ajax: "/report-wasit/get",
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
                data: 'name',
                name: 'name',
                title: 'nama',
                orderable: true,
                searchable: true
            },
            {
                data: 'license',
                name: 'license',
                title: 'Lisensi',
                orderable: true,
                searchable: true
            },
            {
                data: 'no_lisensi',
                name: 'no_lisensi',
                title: 'Nomor Lisensi',
                orderable: true,
                searchable: true
            },
            {
                data: 'region',
                name: 'region',
                title: 'Pengurus Provinsi',
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
            url: '/report-wasit/search',
            data: function (d) {
                d.nama       = $('#nama').val();
                d.no_lisensi = $('#no_lisensi').val();
                d.jenis_lisensi = $('#jenis_lisensi').val();
                d.pengprov      = $('#pengprov').val();
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
                data: 'name',
                name: 'name',
                title: 'nama',
                orderable: true,
                searchable: true
            },
            {
                data: 'license',
                name: 'license',
                title: 'Lisensi',
                orderable: true,
                searchable: true
            },
            {
                data: 'no_lisensi',
                name: 'no_lisensi',
                title: 'Nomor Lisensi',
                orderable: true,
                searchable: true
            },
            {
                data: 'region',
                name: 'region',
                title: 'Pengurus Provinsi',
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