<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Transaksi\TEvent;
use App\Models\Transaksi\TEventContact;
use App\Models\Transaksi\TEventLocation;
use App\Models\Transaksi\TEventParticipant;
use App\Models\Transaksi\TEventRegion;
use App\Models\Transaksi\TEventTembusan;
use App\Models\Transaksi\TNomorSurat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Mail;

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
            ->orderBy('t_event.createdon', 'DESC');

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

        $data->get();
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
            $view   = '<a class="btn btn-primary" title="Show" style="padding:5px; margin-top:-5px;" href="' . route('t-event.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
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

        $participant = TEventParticipant::select('users.id', 'users.name', 'users.email', 'm_license.license', 'user_infos.no_lisensi', 'm_region.region', 'user_infos.role')
            ->where('t_event_participant.id_t_event', '=', $id)
            ->leftJoin('user_infos', 'user_infos.user_id', '=', 't_event_participant.user')
            ->leftJoin('m_region', 'm_region.id', '=', 'user_infos.id_m_region')
            ->leftJoin('users', 'users.id', '=', 't_event_participant.user')
            ->leftJoin('m_license', 'user_infos.id_m_lisensi', '=', 'm_license.id')
            ->orderBy('user_infos.role', 'ASC')
            ->get()->toArray();

        $tembusan = TEventTembusan::where('id_t_event', '=', $id)->get()->toArray();
        $cp       = TEventContact::where('id_t_event', '=', $id)->get()->toArray();

        return view('transaksi.t-event.show', [
            'model' => $model,
            'location' => $location,
            'region' => $region,
            'participant' => $participant,
            'tembusan' => $tembusan,
            'cp' => $cp,
        ]);
    }

    public function create() {
        return view('transaksi.t-event.create');
    }

    public function store(Request $request) {
        try {
            $rules = [
                'nama' => 'required',
                'tanggal_mulai' => 'required|date|before_or_equal:tanggal_selesai',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'provinsi' => 'required',
                'location' => 'required',
                'tipe_event' => 'required',
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
                'before_or_equal' => 'Kolom :attribute tidak boleh melebihi Tanggal Selesai.',
                'after_or_equal' => 'Kolom :attribute tidak boleh kurang dari Tanggal Mulai.',
            ];

            $this->validate($request, $rules, $customMessages);

            $val = $this->customValidation($request);
            if ($val['status'] == 500) {
                Session::flash('error', $val['message']);
                return redirect(route('t-event.create'))->withInput();
            }

            DB::beginTransaction();
            $model = new TEvent();
            $model->nama = $request->nama;
            $model->deskripsi = $request->deskripsi;
            $model->tanggal_mulai = $request->tanggal_mulai;
            $model->tanggal_selesai = $request->tanggal_selesai;
            $model->tipe            = $request->tipe_event;
            $model->penyelenggara   = Auth::id();
            $model->no_lisensi      = TNomorSurat::createNomor();
            $model->status          = 0;
            $model->createdby       = Auth::id();
            $model->createdon       = Carbon::now();
            $model->modifiedby      = Auth::id();
            $model->modifiedon      = Carbon::now();
            if ($model->save()) {
                foreach ($request->location as $itemLocation) {
                    $location = new TEventLocation();
                    $location->id_t_event = $model->id;
                    $location->id_m_location = $itemLocation;
                    $location->createdby     = Auth::id();
                    $location->createdon     = Carbon::now();
                    if (!$location->save()) {
                        DB::rollBack();
                        Session::flash('error', 'Lokasi Event gagal dibuat, mohon ulangi kembali.');
                        return redirect(route('t-event.create'))->withInput();
                    };
                }

                foreach ($request->provinsi as $itemProvinsi) {
                    $provinsi = new TEventRegion();
                    $provinsi->id_t_event = $model->id;
                    $provinsi->id_m_region = $itemProvinsi;
                    $provinsi->createdby     = Auth::id();
                    $provinsi->createdon     = Carbon::now();
                    if (!$provinsi->save()) {
                        DB::rollBack();
                        Session::flash('error', 'Provinsi Event gagal dibuat, mohon ulangi kembali.');
                        return redirect(route('t-event.create'))->withInput();
                    };
                }

                foreach ($request->nama_pengawas as $itemPengawas) {
                    $participant = new TEventParticipant();
                    $participant->id_t_event = $model->id;
                    $participant->user = $itemPengawas;
                    $participant->role = 6;
                    $participant->createdby = Auth::id();
                    $participant->createdon = Carbon::now();
                    if (!$participant->save()) {
                        DB::rollBack();
                        Session::flash('error', 'Pengawas Pertandingan gagal dibuat, mohon ulangi kembali.');
                        return redirect(route('t-event.create'))->withInput();
                    };
                }

                foreach ($request->nama_koordinator as $itemKoordinator) {
                    $participant = new TEventParticipant();
                    $participant->id_t_event = $model->id;
                    $participant->user = $itemKoordinator;
                    $participant->role = 7;
                    $participant->createdby = Auth::id();
                    $participant->createdon = Carbon::now();
                    if (!$participant->save()) {
                        DB::rollBack();
                        Session::flash('error', 'Koordinator Wasit gagal dibuat, mohon ulangi kembali.');
                        return redirect(route('t-event.create'))->withInput();
                    };
                }

                foreach ($request->nama_wasit as $itemWasit) {
                    $participant = new TEventParticipant();
                    $participant->id_t_event = $model->id;
                    $participant->user = $itemWasit;
                    $participant->role = 8;
                    $participant->createdby = Auth::id();
                    $participant->createdon = Carbon::now();
                    if (!$participant->save()) {
                        DB::rollBack();
                        Session::flash('error', 'Wasit gagal dibuat, mohon ulangi kembali.');
                        return redirect(route('t-event.create'))->withInput();
                    };
                }

                $k = 0;
                foreach ($request->nama_tembusan as $itemTembusan) {
                    $tembusan = new TEventTembusan();
                    $tembusan->id_t_event = $model->id;
                    $tembusan->nama       = $itemTembusan;
                    $tembusan->email      = $request->email_tembusan[$k];
                    $tembusan->createdby  = Auth::id();
                    $tembusan->createdon  = Carbon::now();
                    if (!$tembusan->save()) {
                        DB::rollBack();
                        Session::flash('error', 'Tembusan gagal dibuat, mohon ulangi kembali.');
                        return redirect(route('t-event.create'))->withInput();
                    };
                    $k++;
                }

                $k = 0;
                foreach ($request->nama_cp as $itemCp) {
                    $cp = new TEventContact();
                    $cp->id_t_event = $model->id;
                    $cp->nama       = $itemCp;
                    $cp->telepon    = $request->telp_cp[$k];
                    $cp->createdby  = Auth::id();
                    $cp->createdon  = Carbon::now();
                    if (!$cp->save()) {
                        DB::rollBack();
                        Session::flash('error', 'Contact Person gagal dibuat, mohon ulangi kembali.');
                        return redirect(route('t-event.create'))->withInput();
                    };
                    $k++;
                }

                $this->send();

                DB::commit();
                Session::flash('success', 'Event berhasil dibuat.');
                return redirect(route('t-event.show', $model->id));
            };
            DB::rollBack();
            Session::flash('error', 'Event gagal dibuat, mohon ulangi kembali.');
            return redirect(route('t-event.create'))->withInput();
        }  catch(Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
            return redirect()->route('t-event.create');
        }
    }

    public function send() {
        $user = User::select('name', 'email')->leftJoin('user_infos','user_infos.user_id', '=', 'users.id')->where('role', '=', 3)->where('status', '!=', 0)->get()->toArray();
        if ($user) {
            foreach ($user as $item) {
                $to   = $item['email'];
                $data = [
                    'nama' => $item['name']
                ];

                Mail::send('mail.event-approval', $data, function ($message) use ($to, $data) {
                    $message->to($to)
                        ->subject('Approval Penugasan Wasit');
                });
            }
        }
    }

    private function customValidation($request) {
        $i = 0;
        if (!empty($request->nama_pengawas)) {
            foreach ($request->nama_pengawas as $item) {
                foreach ($request->nama_pengawas as $pembanding) {
                    if ($item == $pembanding) {
                        $i++;
                        if ($i > 1) {
                            return ['status' => 500, 'message' => 'Nama Pengawas tidak boleh sama.'];
                        }
                    }
                }
                $i = 0;
            }
        } else {
            return ['status' => 500, 'message' => 'Pengawas Pertandingan tidak boleh kosong.'];
        }

        if (!empty($request->nama_koordinator)) {
            foreach ($request->nama_koordinator as $item) {
                foreach ($request->nama_koordinator as $pembanding) {
                    if ($item == $pembanding) {
                        $i++;
                        if ($i > 1) {
                            return ['status' => 500, 'message' => 'Nama Koordinator Wasit tidak boleh sama.'];
                        }
                    }
                }
                $i = 0;
            }
        } else {
            return ['status' => 500, 'message' => 'Koordinator Wasit tidak boleh kosong.'];
        }

        if (!empty($request->nama_wasit)) {
            foreach ($request->nama_wasit as $item) {
                foreach ($request->nama_wasit as $pembanding) {
                    if ($item == $pembanding) {
                        $i++;
                        if ($i > 1) {
                            return ['status' => 500, 'message' => 'Nama Wasit tidak boleh sama.'];
                        }
                    }
                }
                $i = 0;
            }
        } else {
            return ['status' => 500, 'message' => 'Wasit tidak boleh kosong.'];
        }

        if (!empty($request->nama_tembusan) && !empty($request->email_tembusan)) {
            foreach ($request->nama_tembusan as $namaTembusan) {
                if (empty($namaTembusan)) return ['status' => 500, 'message' => 'Nama Tembusan tidak boleh kosong.'];
            }

            foreach ($request->email_tembusan as $emailTembusan) {
                if (empty($emailTembusan)) return ['status' => 500, 'message' => 'Email Tembusan tidak boleh kosong.'];
            }
        } else {
            return ['status' => 500, 'message' => 'Tembusan tidak boleh kosong.'];
        }

        if (!empty($request->nama_cp) && !empty($request->telp_cp)) {
            foreach ($request->nama_cp as $namaCp) {
                if (empty($namaCp)) return ['status' => 500, 'message' => 'Nama Contact Person tidak boleh kosong.'];
            }

            foreach ($request->telp_cp as $telpCp) {
                if (empty($telpCp)) return ['status' => 500, 'message' => 'Telepon Contact Person tidak boleh kosong.'];
                if (!is_numeric($telpCp)) return ['status' => 500, 'message' => 'Telepon Contact Person tidak valid (Gunakan hanya angka).'];
            }
        } else {
            return ['status' => 500, 'message' => 'Contact Person tidak boleh kosong.'];
        }

        if (count($request->nama_wasit) < 3) {
            return ['status' => 500, 'message' => 'Wasit tidak boleh kurang dari 3 orang.'];
        }
        return ['status' => 200];
    }

    public function edit($id) {
        $model = TEvent::find($id);
        return view('transaksi.t-event.edit', [
            'model' => $model
        ]);
    }

    public function update(Request $request, $id) {
        try {
            $rules = [
                'nama' => 'required',
                'no_lisensi' => 'required',
                'tanggal_mulai' => 'required|date|before_or_equal:tanggal_selesai',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'provinsi' => 'required',
                'location' => 'required',
                'tipe_event' => 'required',
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
                'before_or_equal' => 'Kolom :attribute tidak boleh melebihi Tanggal Selesai.',
                'after_or_equal' => 'Kolom :attribute tidak boleh kurang dari Tanggal Mulai.',
            ];

            $this->validate($request, $rules, $customMessages);

            $val = $this->customValidation($request);
            if ($val['status'] == 500) {
                Session::flash('error', $val['message']);
                return redirect(route('t-event.edit', $id))->withInput();
            }

            DB::beginTransaction();
            $model = TEvent::find($id);
            $model->nama = $request->nama;
            $model->deskripsi = $request->deskripsi;
            $model->tanggal_mulai = $request->tanggal_mulai;
            $model->tanggal_selesai = $request->tanggal_selesai;
            $model->tipe            = $request->tipe_event;
            $model->penyelenggara   = Auth::id();
            $model->no_lisensi      = $request->no_lisensi;
            $model->status          = 0;
            $model->penindak        = null;
            $model->tanggal_tindakan = null;
            $model->keterangan_tolak = null;
            $model->modifiedby      = Auth::id();
            $model->modifiedon      = Carbon::now();
            if ($model->save()) {
                TEventLocation::where('id_t_event', '=', $id)->delete();
                foreach ($request->location as $itemLocation) {
                    $location = new TEventLocation();
                    $location->id_t_event = $model->id;
                    $location->id_m_location = $itemLocation;
                    $location->createdby     = Auth::id();
                    $location->createdon     = Carbon::now();
                    if (!$location->save()) {
                        DB::rollBack();
                        Session::flash('error', 'Lokasi Event gagal dibuat, mohon ulangi kembali.');
                        return redirect(route('t-event.edit', $id))->withInput();
                    };
                }

                TEventRegion::where('id_t_event', '=', $id)->delete();
                foreach ($request->provinsi as $itemProvinsi) {
                    $provinsi = new TEventRegion();
                    $provinsi->id_t_event = $model->id;
                    $provinsi->id_m_region = $itemProvinsi;
                    $provinsi->createdby     = Auth::id();
                    $provinsi->createdon     = Carbon::now();
                    if (!$provinsi->save()) {
                        DB::rollBack();
                        Session::flash('error', 'Provinsi Event gagal dibuat, mohon ulangi kembali.');
                        return redirect(route('t-event.edit', $id))->withInput();
                    };
                }

                TEventParticipant::where('id_t_event', '=', $id)->delete();
                foreach ($request->nama_pengawas as $itemPengawas) {
                    $participant = new TEventParticipant();
                    $participant->id_t_event = $model->id;
                    $participant->user = $itemPengawas;
                    $participant->role = 6;
                    $participant->createdby = Auth::id();
                    $participant->createdon = Carbon::now();
                    if (!$participant->save()) {
                        DB::rollBack();
                        Session::flash('error', 'Pengawas Pertandingan gagal dibuat, mohon ulangi kembali.');
                        return redirect(route('t-event.edit', $id))->withInput();
                    };
                }

                foreach ($request->nama_koordinator as $itemKoordinator) {
                    $participant = new TEventParticipant();
                    $participant->id_t_event = $model->id;
                    $participant->user = $itemKoordinator;
                    $participant->role = 7;
                    $participant->createdby = Auth::id();
                    $participant->createdon = Carbon::now();
                    if (!$participant->save()) {
                        DB::rollBack();
                        Session::flash('error', 'Koordinator Wasit gagal dibuat, mohon ulangi kembali.');
                        return redirect(route('t-event.edit', $id))->withInput();
                    };
                }

                foreach ($request->nama_wasit as $itemWasit) {
                    $participant = new TEventParticipant();
                    $participant->id_t_event = $model->id;
                    $participant->user = $itemWasit;
                    $participant->role = 8;
                    $participant->createdby = Auth::id();
                    $participant->createdon = Carbon::now();
                    if (!$participant->save()) {
                        DB::rollBack();
                        Session::flash('error', 'Wasit gagal dibuat, mohon ulangi kembali.');
                        return redirect(route('t-event.edit', $id))->withInput();
                    };
                }

                TEventTembusan::where('id_t_event', '=', $id)->delete();
                $k = 0;
                foreach ($request->nama_tembusan as $itemTembusan) {
                    $tembusan = new TEventTembusan();
                    $tembusan->id_t_event = $model->id;
                    $tembusan->nama       = $itemTembusan;
                    $tembusan->email      = $request->email_tembusan[$k];
                    $tembusan->createdby  = Auth::id();
                    $tembusan->createdon  = Carbon::now();
                    if (!$tembusan->save()) {
                        DB::rollBack();
                        Session::flash('error', 'Tembusan gagal dibuat, mohon ulangi kembali.');
                        return redirect(route('t-event.create'))->withInput();
                    };
                    $k++;
                }


                TEventContact::where('id_t_event', '=', $id)->delete();
                $k = 0;
                foreach ($request->nama_cp as $itemCp) {
                    $cp = new TEventContact();
                    $cp->id_t_event = $model->id;
                    $cp->nama       = $itemCp;
                    $cp->telepon    = $request->telp_cp[$k];
                    $cp->createdby  = Auth::id();
                    $cp->createdon  = Carbon::now();
                    if (!$cp->save()) {
                        DB::rollBack();
                        Session::flash('error', 'Contact Person gagal dibuat, mohon ulangi kembali.');
                        return redirect(route('t-event.create'))->withInput();
                    };
                    $k++;
                }
                DB::commit();
                return redirect(route('t-event.show', $id));
            };
            DB::rollBack();
            Session::flash('error', 'Event gagal dibuat, mohon ulangi kembali.');
            return redirect(route('t-event.edit', $id))->withInput();
        }  catch(Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
            return redirect(route('t-event.edit', $id))->withInput();
        }
    }

    public function delete(Request $request) {
        $model = TEvent::find($request->id);
        $model->deletedby = Auth::id();
        $model->deletedon = Carbon::now();
        $model->save();

        $status  = 200;
        $header  = 'Success';
        $message = 'Event berhasil di hapus.';

        return response()->json([
            'status' => $status,
            'header' => $header,
            'message' => $message
        ]);
    }
}
