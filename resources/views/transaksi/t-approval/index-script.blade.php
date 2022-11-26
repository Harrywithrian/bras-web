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
    });

    $("#jenis").select2({
        // the following code is used to disable x-scrollbar when click in select input and
        // take 100% width in responsive also
        placeholder: "Pilih ...",
        dropdownAutoWidth: true,
        width: '100%',
    });

    $("#lisensi").select2({
        // the following code is used to disable x-scrollbar when click in select input and
        // take 100% width in responsive also
        placeholder: "Pilih ...",
        dropdownAutoWidth: true,
        width: '100%',
    });
    
    $("#status").select2({
        // the following code is used to disable x-scrollbar when click in select input and
        // take 100% width in responsive also
        placeholder: "Pilih ...",
        dropdownAutoWidth: true,
        width: '100%',
    });

    function showAllData() {
        var mainTable = $('#content-table');
        mainTable.DataTable().clear().destroy();

        mainTable.DataTable({
            ajax: {
                type: 'post',
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                url: '/t-approval/get',
                data: function (d) {
                    d.username = $('#username').val();
                    d.nama     = $('#nama').val();
                    d.jenis    = $('#jenis').val();
                    d.no_lisensi = $('#no_lisensi').val();
                    d.lisensi    = $('#lisensi').val();
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
                    data: 'username',
                    name: 'username',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'nama',
                    name: 'nama',
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
                    data: 'license',
                    name: 'license',
                    title: 'Jenis Lisensi',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'jenis_daftar',
                    name: 'jenis_daftar',
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
        $("#jenis").val('').trigger('change');
        $("#lisensi").val('').trigger('change');
        $("#status").val('').trigger('change');
        showAllData();
    }
</script>