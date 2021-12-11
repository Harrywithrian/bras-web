$(document).ready(function() {
    showAllData();
});

$("#tanggal_event").daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: false,
    locale: {
        cancelLabel: 'Clear'
    }
});

$("#tanggal_event").on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD'));
});

/* FUNGSI MENAMPILKAN SEMUA DATA */
function showAllData() {
    $('#content-table').DataTable({
        ajax: "/t-match/get-event",
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
                data: 'nama',
                name: 'nama',
                orderable: true,
                searchable: true
            },
            {
                data: 'tanggal_mulai',
                name: 'tanggal_mulai',
                orderable: true,
                searchable: true
            },
            {
                data: 'tanggal_selesai',
                name: 'tanggal_selesai',
                orderable: true,
                searchable: true
            },
            {
                data: 'penyelenggara',
                name: 'penyelenggara',
                title: 'Komisi Teknik',
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
            url: '/t-match/search-event',
            data: function (d) {
                d.nama          = $('#nama').val();
                d.no_lisensi    = $('#no_lisensi').val();
                d.penyelenggara = $('#penyelenggara').val();
                d.tanggal       = $('#tanggal_event').val();
                d.status        = $('#status').val();
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
                data: 'nama',
                name: 'nama',
                orderable: true,
                searchable: true
            },
            {
                data: 'tanggal_mulai',
                name: 'tanggal_mulai',
                orderable: true,
                searchable: true
            },
            {
                data: 'tanggal_selesai',
                name: 'tanggal_selesai',
                orderable: true,
                searchable: true
            },
            {
                data: 'penyelenggara',
                name: 'penyelenggara',
                title: 'Komisi Teknik',
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