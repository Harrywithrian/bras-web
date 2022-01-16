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
use App\Models\Transaksi\TNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
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
                return "<span class='w-130px badge badge-info me-4'> Belum Terkirim </span>";
            } else {
                return "<span class='w-130px badge badge-success me-4'> Terkirim </span>";
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

    public function update(Request $request) {
        $letter   = TEventLetter::find($request->id);
        $model    = TEvent::find($letter->id_t_event);

        if (empty($letter) || empty($model)) {
            $status  = 500;
            $header  = 'Error';
            $message = 'Event Tidak Ditemukan.';

            return response()->json([
                'status' => $status,
                'header' => $header,
                'message' => $message
            ]);
        }

        $exist = TEvent::where('no_lisensi', '=', $request->no_surat)->whereNull('deletedby')->first();
        if ($exist) {
            $status  = 500;
            $header  = 'Error';
            $message = 'Nomor Surat Sudah Terdaftar.';

            return response()->json([
                'status' => $status,
                'header' => $header,
                'message' => $message
            ]);
        }
        $letter->no_surat = $request->no_surat;
        $letter->save();

        $model->no_lisensi = $request->no_surat;
        $model->save();

        $status  = 200;
        $header  = 'Success';
        $message = 'Nomor Surat Berhasil Diubah.';

        return response()->json([
            'status' => $status,
            'header' => $header,
            'message' => $message
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
        $ketum    = User::find($model->penindak);

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
            'ketum' => $ketum,
            'sent_date' => $sent_date,
            'monthStart' => $monthStart,
            'monthEnd' => $monthEnd,
        ];

        $pdf = PDF::loadView('transaksi.t-event-letter.dokumen', $data)->setPaper('a4', 'potrait');
        return $pdf->download('Surat Tugas_' . $letter->no_surat);
    }

    public function send($id) {
        $letter   = TEventLetter::find($id);
        $event    = TEvent::find($letter->id_t_event);
        $participant = TEventParticipant::where('id_t_event', '=', $letter->id_t_event)->get()->toArray();
        $to       = null;
        if ($participant) {
            foreach ($participant as $item) {
                $notif = new TNotification();
                $notif->user = $item['user'];
                $notif->type = 1;
                $notif->id_event_match = $letter->id_t_event;
                $notif->status         = 0;
                $notif->createdby   = Auth::id();
                $notif->createdon   = Carbon::now();
                $notif->save();

                $user = User::find($item['user']);
                $to[] = $user->email;
            }
        }

        $letter->sent = $letter->sent + 1;
        $letter->save();

        # PDF
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

        $region   = TEventRegion::select('m_region.kode', 'm_region.region', 'm_region.email')
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
        $ketum    = User::find($model->penindak);

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
            'ketum' => $ketum,
            'sent_date' => $sent_date,
            'monthStart' => $monthStart,
            'monthEnd' => $monthEnd,
        ];

        $pdf = PDF::loadView('transaksi.t-event-letter.dokumen', $data)->setPaper('a4', 'potrait');
        #END PDF

        #MAIL
        if ($region) {
            foreach ($region as $item) {
                if($item['email']) {
                    $to[] = $item['email'];
                };
            }
        }

        if ($tembusan) {
            foreach ($tembusan as $item) {
                if($item['email']) {
                    $to[] = $item['email'];
                };
            }
        }

        if ($to) {
            Mail::send('mail.event-notification', $data, function ($message) use ($to, $data, $pdf) {
                $message->to($to)
                    ->subject('Surat Tugas/Tembusan')
                    ->attachData($pdf->output(), 'surat_undangan.pdf');
            });
        }

        #END MAIL
        Session::flash('success', 'Surat tugas telah dikirim.');
        return redirect()->route('t-event-letter.show', $id);
    }
}
