<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Location;
use App\Models\Transaksi\TEvent;
use App\Models\Transaksi\TEventContact;
use App\Models\Transaksi\TEventLocation;
use App\Models\Transaksi\TEventParticipant;
use App\Models\Transaksi\TEventRegion;
use App\Models\Transaksi\TEventTembusan;
use App\Models\Transaksi\TMatch;
use App\Models\Transaksi\TMatchReferee;
use App\Models\Transaksi\TNotification;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TNotifikasiController extends Controller
{
    public function event($id) {
        $notifikasi = TNotification::find($id);

        if ($notifikasi->status == 0) {
            $notifikasi->status = 1;
        }
        $notifikasi->save();

        $event      = TEvent::find($notifikasi->id_event_match);
        $eventLocation = TEventLocation::select('m_location.nama', 'm_region.region', 'm_location.alamat')
            ->where('t_event_location.id_t_event', '=', $event->id)
            ->leftJoin('m_location', 'm_location.id', '=', 't_event_location.id_m_location')
            ->leftJoin('m_region', 'm_region.id', '=', 'm_location.id_m_region')
            ->get()->toArray();

        $eventRegion = TEventRegion::select('m_region.kode', 'm_region.region')
            ->where('t_event_region.id_t_event', '=', $event->id)
            ->leftJoin('m_region', 'm_region.id', '=', 't_event_region.id_m_region')
            ->get()->toArray();

        $participant = TEventParticipant::select('users.name', 'users.email', 'm_license.license', 'user_infos.no_lisensi', 'm_region.region', 'user_infos.role')
            ->where('t_event_participant.id_t_event', '=', $event->id)
            ->leftJoin('user_infos', 'user_infos.user_id', '=', 't_event_participant.user')
            ->leftJoin('m_region', 'm_region.id', '=', 'user_infos.id_m_region')
            ->leftJoin('users', 'users.id', '=', 't_event_participant.user')
            ->leftJoin('m_license', 'user_infos.id_m_lisensi', '=', 'm_license.id')
            ->orderBy('user_infos.role', 'ASC')
            ->get()->toArray();

        $tembusan = TEventTembusan::where('id_t_event', '=', $event->id)->get()->toArray();
        $cp       = TEventContact::where('id_t_event', '=', $event->id)->get()->toArray();

        return view('transaksi.t-notifikasi.event', [
            'notifikasi' => $notifikasi,
            'event' => $event,
            'location' => $eventLocation,
            'region' => $eventRegion,
            'participant' => $participant,
            'tembusan' => $tembusan,
            'cp' => $cp,
        ]);
    }

    public function replyEvent(Request $request, $id) {
        try {
            $rules = [
                'reply' => 'required',
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
            ];

            $this->validate($request, $rules, $customMessages);

            $model = TNotification::find($id);
            $model->reply  = $request->reply;
            $model->status = 2;
            if ($model->save()) {
                Session::flash('success', 'Event berhasil dibalas.');
                return redirect()->route('notifikasi.event', $id);
            }

            Session::flash('error', 'Event gagal dibalas.');
            return redirect()->route('notifikasi.event', $id);
        }  catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('notifikasi.event', $id);
        }
    }

    public function match($id) {
        $notifikasi = TNotification::find($id);

        if ($notifikasi->status == 0) {
            $notifikasi->status = 1;
        }
        $notifikasi->save();

        $model = TMatch::find($notifikasi->id_event_match);
        $lokasi = Location::find($model->id_m_location);
        $event = TEvent::find($model->id_t_event);

        $wst1 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $model->id)->where('posisi', '=', 'Crew Chief')->first();
        $wst2 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $model->id)->where('posisi', '=', 'Official 1')->first();
        $wst3 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $model->id)->where('posisi', '=', 'Official 2')->first();

        $foto1 = UserInfo::select(['t_file.path'])->leftJoin('t_file', 't_file.id', '=', 'user_infos.id_t_file_foto')->where('user_id', '=', $wst1->id)->first();
        $foto2 = UserInfo::select(['t_file.path'])->leftJoin('t_file', 't_file.id', '=', 'user_infos.id_t_file_foto')->where('user_id', '=', $wst2->id)->first();
        $foto3 = UserInfo::select(['t_file.path'])->leftJoin('t_file', 't_file.id', '=', 'user_infos.id_t_file_foto')->where('user_id', '=', $wst3->id)->first();

        return view('transaksi.t-notifikasi.match', [
            'notifikasi' => $notifikasi,
            'model' => $model,
            'lokasi' => $lokasi,
            'event' => $event,
            'wst1' => $wst1,
            'wst2' => $wst2,
            'wst3' => $wst3,
            'foto1' => $foto1,
            'foto2' => $foto2,
            'foto3' => $foto3,
        ]);
    }

    public function replyMatch(Request $request, $id) {
        try {
            $rules = [
                'reply' => 'required',
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
            ];

            $this->validate($request, $rules, $customMessages);

            $model = TNotification::find($id);
            $model->reply  = $request->reply;
            $model->status = 2;
            if ($model->save()) {
                Session::flash('success', 'Pertandingan berhasil dibalas.');
                return redirect()->route('notifikasi.match', $id);
            }

            Session::flash('error', 'Pertandingan gagal dibalas.');
            return redirect()->route('notifikasi.match', $id);
        }  catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('notifikasi.match', $id);
        }
    }
}
