<input id="id_user" type="hidden" value="{{$id}}">

@include('master.profile.search-match')

<div class="row">
    <div class="col-12">
        <div id="main-table">
            <table id="content-table" class="table table-hover table-rounded table-row-bordered border gy-5 gs-5" style="width:100%;">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Action</th>
                    <th>Status</th>
                    <th>Pertandingan</th>
                    <th>Event</th>
                    <th>Waktu Pertandingan</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>