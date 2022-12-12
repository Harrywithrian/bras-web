<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\TEvent;
use App\Models\Transaksi\TEventContact;
use App\Models\Transaksi\TEventLetter;
use App\Models\Transaksi\TEventLocation;
use App\Models\Transaksi\TEventParticipant;
use App\Models\Transaksi\TEventRegion;
use App\Models\Transaksi\TEventTembusan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TEventApprovalController extends Controller
{
    public function index() {
        return view('transaksi.t-event-approval.index');
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
                'users.name as penyelenggara',
            ])->leftJoin('users', 'users.id', '=', 't_event.penyelenggara')
                ->whereNull('t_event.deletedon')
                ->orderBy('t_event.createdon', 'DESC')
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
            'users.name as penyelenggara',
        ])->leftJoin('users', 'users.id', '=', 't_event.penyelenggara')
            ->whereNull('t_event.deletedon')
            ->orderBy('t_event.createdon', 'DESC')
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
                return "<span class='w-130px badge badge-success me-4'> Approved </span>";
            } if ($row->status == 0) {
                return "<span class='w-130px badge badge-info me-4'> Waiting Approval</span>";
            } if ($row->status == 2) {
                return "<span class='w-130px badge badge-primary me-4'> Selesai </span>";
            } else {
                return "<span class='w-130px badge badge-danger me-4'> Rejected </span>";
            }
        });

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $view   = '<a class="btn btn-primary" title="Show" style="padding:5px; margin-top:-5px;" href="' . route('t-event-approval.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
            $button = $view;

            return $button;
        });

        $dataTables = $dataTables->rawColumns(['status', 'action'])->make(true);
        return $dataTables;
    }

    public function show($id) {
        $model    = TEvent::find($id);
        $location = TEventLocation::select('m_location.nama', 'm_region.region', 'm_location.alamat')
            ->where('t_event_location.id_t_event', '=', $id)
            ->leftJoin('m_location', 'm_location.id', '=', 't_event_location.id_m_location')
            ->leftJoin('m_region', 'm_region.id', '=', 'm_location.id_m_region')
            ->get()->toArray();

        $region   = TEventRegion::select('m_region.kode', 'm_region.region')
            ->where('t_event_region.id_t_event', '=', $id)
            ->leftJoin('m_region', 'm_region.id', '=', 't_event_region.id_m_region')
            ->get()->toArray();

        $participant = TEventParticipant::select('users.name', 'users.email', 'm_license.license', 'user_infos.no_lisensi', 'm_region.region', 'user_infos.role')
            ->where('t_event_participant.id_t_event', '=', $id)
            ->leftJoin('user_infos', 'user_infos.user_id', '=', 't_event_participant.user')
            ->leftJoin('m_region', 'm_region.id', '=', 'user_infos.id_m_region')
            ->leftJoin('users', 'users.id', '=', 't_event_participant.user')
            ->leftJoin('m_license', 'user_infos.id_m_lisensi', '=', 'm_license.id')
            ->orderBy('user_infos.role', 'ASC')
            ->get()->toArray();

        $tembusan = TEventTembusan::where('id_t_event', '=', $id)->get()->toArray();
        $cp       = TEventContact::where('id_t_event', '=', $id)->get()->toArray();

        return view('transaksi.t-event-approval.show', [
            'model' => $model,
            'location' => $location,
            'region' => $region,
            'participant' => $participant,
            'tembusan' => $tembusan,
            'cp' => $cp,
        ]);
    }

    public function approve(Request $request) {
        $model = TEvent::find($request->id);
        $model->status = 1;
        $model->penindak = Auth::id();
        $model->tanggal_tindakan = date('Y-m-d');
        $model->save();

        $letter = new TEventLetter();
        $letter->id_t_event = $request->id;
        $letter->no_surat   = $model->no_lisensi;
        $letter->perihal    = $model->nama;
        $letter->sent       = 0;
        $letter->createdby   = Auth::id();
        $letter->createdon   = Carbon::now();
        $letter->save();

        $status  = 200;
        $header  = 'Success';
        $message = 'Event berhasil di setujui.';


        return response()->json([
            'status' => $status,
            'header' => $header,
            'message' => $message
        ]);
    }

    public function reject(Request $request) {
        if (empty($request->ket)) {
            return response()->json([
                'status' => 500,
                'header' => 'Failed',
                'message' => 'Keterangan tidak boleh kosong.'
            ]);
        }

        $model = TEvent::find($request->id);
        $model->status = -1;
        $model->penindak = Auth::id();
        $model->tanggal_tindakan = date('Y-m-d');
        $model->keterangan_tolak = $request->ket;
        $model->save();

        $status  = 200;
        $header  = 'Success';
        $message = 'Event berhasil di tolak.';


        return response()->json([
            'status' => $status,
            'header' => $header,
            'message' => $message
        ]);
    }
}
