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
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;

class TEventLetterController extends Controller
{
    public function index() {
        return view('transaksi.t-event-letter.index');
    }

    public function get(Request $request) {
        if ($request->ajax()) {
            $data = TEventLetter::select(['id', 'perihal', 'no_surat', 'sent'])
                ->orderBy('createdon', 'DESC')
                ->get();

            return $this->dataTable($data);
        }
        return null;
    }

    public function search(Request $request) {
        $data = TEventLetter::select(['id', 'perihal', 'no_surat', 'sent'])
            ->orderBy('createdon', 'DESC');

        if ($request->perihal != '') {
            $data->where('perihal','LIKE','%'.$request->perihal.'%');
        }

        if ($request->no_surat != '') {
            $data->where('no_surat','LIKE','%'.$request->no_surat.'%');
        }

        if ($request->sent != '') {
            if ($request->sent == 0) {
                $data->where('sent', '=', $request->sent);
            } else {
                $data->where('sent', '>=', $request->sent);
            }
        }

        $data->get();
        return $this->dataTable($data);
    }

    public function dataTable($data) {
        $dataTables = DataTables::of($data);

        # KOLOM INDEX ANGKA
        $dataTables = $dataTables->addIndexColumn();

        # KOLOM STATUS
        $dataTables = $dataTables->addColumn('sent', function ($row) {
            if ($row->sent == 0) {
                return "Belum Terkirim";
            } else {
                return "Terkirim";
            }
        });

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $view   = '<a class="btn btn-info" title="Show" style="padding:5px;" href="' . route('t-event-letter.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
            $button = $view;
            return $button;
        });

        $dataTables = $dataTables->rawColumns(['sent', 'action'])->make(true);
        return $dataTables;
    }

    public function show($id) {
        $letter   = TEventLetter::find($id);
        $model    = TEvent::find($letter->id_t_event);
        $location = TEventLocation::select('m_location.nama', 'm_region.region', 'm_location.alamat')
            ->where('t_event_location.id_t_event', '=', $letter->id_t_event)
            ->leftJoin('m_location', 'm_location.id', '=', 't_event_location.id_m_location')
            ->leftJoin('m_region', 'm_region.id', '=', 'm_location.id_m_region')
            ->get()->toArray();

        $region   = TEventRegion::select('m_region.kode', 'm_region.region')
            ->where('t_event_region.id_t_event', '=', $letter->id_t_event)
            ->leftJoin('m_region', 'm_region.id', '=', 't_event_region.id_m_region')
            ->get()->toArray();

        $participant = TEventParticipant::select('users.name', 'users.email', 'm_license.license', 'user_infos.no_lisensi', 'm_region.region', 'user_infos.role')
            ->where('t_event_participant.id_t_event', '=', $letter->id_t_event)
            ->leftJoin('user_infos', 'user_infos.user_id', '=', 't_event_participant.user')
            ->leftJoin('m_region', 'm_region.id', '=', 'user_infos.id_m_region')
            ->leftJoin('users', 'users.id', '=', 't_event_participant.user')
            ->leftJoin('m_license', 'user_infos.id_m_lisensi', '=', 'm_license.id')
            ->orderBy('user_infos.role', 'ASC')
            ->get()->toArray();

        $tembusan = TEventTembusan::where('id_t_event', '=', $letter->id_t_event)->get()->toArray();
        $cp       = TEventContact::where('id_t_event', '=', $letter->id_t_event)->get()->toArray();

        return view('transaksi.t-event-letter.show', [
            'letter' => $letter,
            'model' => $model,
            'location' => $location,
            'region' => $region,
            'participant' => $participant,
            'tembusan' => $tembusan,
            'cp' => $cp,
        ]);
    }

    public function dokumen($id) {
        $month    = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $letter   = TEventLetter::find($id);
        if (empty($letter->sent_date)) {
            $letter->sent_date = date('Y-m-d');
            $letter->save();
        }

        $model    = TEvent::find($letter->id_t_event);
        $location = TEventLocation::select('m_location.nama', 'm_region.region', 'm_location.alamat')
            ->where('t_event_location.id_t_event', '=', $letter->id_t_event)
            ->leftJoin('m_location', 'm_location.id', '=', 't_event_location.id_m_location')
            ->leftJoin('m_region', 'm_region.id', '=', 'm_location.id_m_region')
            ->get()->toArray();

        $region   = TEventRegion::select('m_region.kode', 'm_region.region')
            ->where('t_event_region.id_t_event', '=', $letter->id_t_event)
            ->leftJoin('m_region', 'm_region.id', '=', 't_event_region.id_m_region')
            ->get()->toArray();

        $pengawas = TEventParticipant::select('users.name', 'users.email', 'm_license.license', 'user_infos.no_lisensi', 'm_region.region', 'user_infos.role')
            ->where('t_event_participant.id_t_event', '=', $letter->id_t_event)
            ->where('t_event_participant.role', '=', 6)
            ->leftJoin('user_infos', 'user_infos.user_id', '=', 't_event_participant.user')
            ->leftJoin('m_region', 'm_region.id', '=', 'user_infos.id_m_region')
            ->leftJoin('users', 'users.id', '=', 't_event_participant.user')
            ->leftJoin('m_license', 'user_infos.id_m_lisensi', '=', 'm_license.id')
            ->orderBy('user_infos.role', 'ASC')
            ->get()->toArray();

        $koordinator = TEventParticipant::select('users.name', 'users.email', 'm_license.license', 'user_infos.no_lisensi', 'm_region.region', 'user_infos.role')
            ->where('t_event_participant.id_t_event', '=', $letter->id_t_event)
            ->where('t_event_participant.role', '=', 7)
            ->leftJoin('user_infos', 'user_infos.user_id', '=', 't_event_participant.user')
            ->leftJoin('m_region', 'm_region.id', '=', 'user_infos.id_m_region')
            ->leftJoin('users', 'users.id', '=', 't_event_participant.user')
            ->leftJoin('m_license', 'user_infos.id_m_lisensi', '=', 'm_license.id')
            ->orderBy('user_infos.role', 'ASC')
            ->get()->toArray();

        $wasit = TEventParticipant::select('users.name', 'users.email', 'm_license.license', 'user_infos.no_lisensi', 'm_region.region', 'user_infos.role')
            ->where('t_event_participant.id_t_event', '=', $letter->id_t_event)
            ->where('t_event_participant.role', '=', 8)
            ->leftJoin('user_infos', 'user_infos.user_id', '=', 't_event_participant.user')
            ->leftJoin('m_region', 'm_region.id', '=', 'user_infos.id_m_region')
            ->leftJoin('users', 'users.id', '=', 't_event_participant.user')
            ->leftJoin('m_license', 'user_infos.id_m_lisensi', '=', 'm_license.id')
            ->orderBy('user_infos.role', 'ASC')
            ->get()->toArray();

        $tembusan = TEventTembusan::where('id_t_event', '=', $letter->id_t_event)->get()->toArray();
        $cp       = TEventContact::where('id_t_event', '=', $letter->id_t_event)->get()->toArray();

        $numbMonth      = date('n', strtotime($letter->sent_date)) - 1;
        $numbMonthStart = date('n', strtotime($model->tanggal_mulai)) - 1;
        $numbMonthEnd   = date('n', strtotime($model->tanggal_selesai)) - 1;

        $sent_date  = date('d', strtotime($letter->sent_date)) . " " . $month[$numbMonth] . " " . date('Y', strtotime($letter->sent_date));
        $monthStart = date('d', strtotime($model->tanggal_mulai)) . " " . $month[$numbMonthStart] . " " . date('Y', strtotime($model->tanggal_mulai));
        $monthEnd   = date('d', strtotime($model->tanggal_selesai)) . " " . $month[$numbMonthEnd] . " " . date('Y', strtotime($model->tanggal_selesai));

        $data = [
            'letter' => $letter,
            'model' => $model,
            'location' => $location,
            'region' => $region,
            'pengawas' => $pengawas,
            'koordinator' => $koordinator,
            'wasit' => $wasit,
            'tembusan' => $tembusan,
            'cp' => $cp,
            'sent_date' => $sent_date,
            'monthStart' => $monthStart,
            'monthEnd' => $monthEnd,
        ];

//        return view('transaksi.t-event-letter.dokumen', $data);
        $pdf = PDF::loadView('transaksi.t-event-letter.dokumen', $data)->setPaper('a4', 'potrait');
        return $pdf->download('Surat Tugas_' . $letter->no_surat);
    }
}
