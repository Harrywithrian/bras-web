<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\TEvent;
use App\Models\Transaksi\TEventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TMatchController extends Controller
{
    public function event() {
        return view('transaksi.t-match.event');
    }

    public function getEvent(Request $request) {
        if ($request->ajax()) {
            $id = Auth::id();
            $data = TEventParticipant::select([
                't_event.id',
                't_event.nama',
                't_event.tanggal_mulai',
                't_event.tanggal_selesai',
                't_event.no_lisensi',
                'a.name',
                't_event.no_lisensi',
            ])
                ->leftJoin('t_event', 't_event.id', '=', 't_event_participant.id_t_event')
                ->leftJoin('users AS a', 't_event.penyelenggara', '=', 'a.id')
                ->where('t_event.status', '=', 1)
                ->where('t_event_participant.user', '=', $id)
                ->orWhere('t_event.penyelenggara', '=', $id)
                ->orderBy('t_event.tanggal_mulai', 'DESC')
                ->get();

            return $this->dataTableEvent($data);
        }
        return null;
    }

    public function dataTableEvent($data) {
        $dataTables = DataTables::of($data);

        # KOLOM INDEX ANGKA
        $dataTables = $dataTables->addIndexColumn();

        # KOLOM
        $dataTables = $dataTables->addColumn('penyelenggara', function ($row) {
            return $row->name;
        });


        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $view = '<a class="btn btn-info" title="Show" style="padding:5px;" href="' . route('t-match.show-event', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';

            $button = $view;

            return $button;
        });

        $dataTables = $dataTables->rawColumns(['action'])->make(true);
        return $dataTables;
    }
}
