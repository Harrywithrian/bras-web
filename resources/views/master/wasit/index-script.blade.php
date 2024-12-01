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

        $("#license").select2({
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
                url: "/wasit/get",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                data: function (d) {
                    d.search  = $('#input-search').val();
                    d.lisensi = $('#license').val();
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
                    title: 'Nama',
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
        showAllData();
    }

    function resets(event) {
        event.preventDefault();
        document.getElementById('search').reset();
        $("#license").val('').trigger('change');
        showAllData();
    }

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