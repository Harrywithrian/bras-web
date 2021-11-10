<input id="id_wasit" type="hidden" value="{{$user->id}}">

<div id="main-table">

    @include('transaksi.report-wasit.search-match')

    <table id="content-table" class="table table-hover table-rounded table-row-bordered border gy-5 gs-5" style="width:100%;">
        <thead>
        <tr>
            <th>No</th>
            <th>Action</th>
            <th>Status</th>
            <th>Nama</th>
            <th>Lokasi</th>
            <th>Waktu Pertandingan</th>
            <th>Event</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    @section('scripts')
        <script src="{{asset('demo1/js/transaksi/report-wasit/index-match.js')}}"></script>
        <script src="{{asset('demo1/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    @endsection
</div>