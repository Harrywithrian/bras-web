<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\TEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class TEventController extends Controller
{
    public function index() {
        return view('transaksi.t-event.index');
    }

    public function get(Request $request) {
        if ($request->ajax()) {
            $data = TEvent::select([
                't_event.id',
                't_event.status',
                't_event.nama',
                't_event.no_lisensi',
                't_event.tanggal_mulai',
                't_event.tanggal_selesai',
                'users.name',
            ])->leftJoin('users', 'users.id', '=', 't_event.penyelenggara')
                ->get();

            return $this->dataTable($data);
        }
        return null;
    }

    public function search(Request $request) {
        $data = TEvent::select([
            't_event.id',
            't_event.status',
            't_event.nama',
            't_event.no_lisensi',
            't_event.tanggal_mulai',
            't_event.tanggal_selesai',
            'users.name',
        ])->leftJoin('users', 'users.id', '=', 't_event.penyelenggara')
            ->get();

        if ($request->nama != '') {
            $data->where('t_event.nama', 'LIKE', '%'.$request->nama.'%');
        }

        if ($request->no_lisensi != '') {
            $data->where('t_event.no_lisensi', 'LIKE', '%'.$request->no_lisensi.'%');
        }

        if ($request->penyelenggara != '') {
            $data->where('users.name', 'LIKE', '%'.$request->penyelenggara.'%');
        }

        if ($request->tanggal != '') {
            $data->where('t_event.tanggal_mulai', '>=', $request->tanggal)
                ->where('t_event.tanggal_selesai', '<=', $request->tanggal);
        }

        if ($request->status != '') {
            $data->where('t_event.status', '=', $request->status);
        }
        return $this->dataTable($data);
    }

    public function dataTable($data) {
        $dataTables = DataTables::of($data);

        # KOLOM INDEX ANGKA
        $dataTables = $dataTables->addIndexColumn();

        # KOLOM STATUS
        $dataTables = $dataTables->addColumn('status', function ($row) {
            if ($row->status == 1) {
                return "<span class='rounded-pill bg-success' style='padding:5px; color: white'> Approved </span>";
            } if ($row->status == 0) {
                return "<span class='rounded-pill bg-primary' style='padding:5px; color: white'> Waiting </span>";
            } else {
                return "<span class='rounded-pill bg-danger' style='padding:5px; color: white''> Rejected </span>";
            }
        });

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $view   = '<a class="btn btn-info" title="Show" style="padding:5px; margin-top:-5px;" href="' . route('t-event.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
            $button = $view;

            return $button;
        });

        $dataTables = $dataTables->rawColumns(['status', 'action'])->make(true);
        return $dataTables;
    }

    public function show($id) {
        $model = TEvent::find($id);

        return view('transaksi.t-event.show', [
            'model' => $model,
        ]);
    }

    public function create() {
        return view('transaksi.t-event.create');
    }

    public function store(Request $request) {
        try {
            $rules = [
                'nama' => 'required',
                'no_lisensi' => 'required',
                'tanggal_mulai' => 'required|date|before_or_equal:tanggal_selesai',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'tipe_event' => 'required',
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
                'before_or_equal' => 'Kolom :attribute tidak boleh melebihi Tanggal Selesai.',
                'after_or_equal' => 'Kolom :attribute tidak boleh kurang dari Tanggal Mulai.',
            ];

            $this->validate($request, $rules, $customMessages);
//
//            $model = new Iot();
//            $model->alias = $request->alias;
//            $model->nama  = $request->nama;
//            $model->keterangan  = $request->keterangan;
//            $model->status      = 1;
//            $model->createdby   = Auth::id();
//            $model->createdon   = Carbon::now();
//            $model->modifiedby  = Auth::id();
//            $model->modifiedon  = Carbon::now();
//            if ($model->save()) {
//                Session::flash('success', 'IOT Berhasil Dibuat.');
//                return redirect()->route('iot.show', $model->id);
//            }
//
//            Session::flash('error', 'IOT Gagal Dibuat.');
//            return redirect()->route('iot.create');
        }  catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('iot.create');
        }
    }
}
