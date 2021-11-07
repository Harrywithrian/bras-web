<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\MAppearance;
use App\Models\Transaksi\TAppearance;
use App\Models\Transaksi\TEvent;
use App\Models\Transaksi\TMatch;
use App\Models\Transaksi\TMatchEvaluation;
use App\Models\Transaksi\TMatchReferee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TAppearanceController extends Controller
{
    public function show($id, $wasit) {
        $model = TMatch::find($id);
        $event = TEvent::find($model->id_t_event);
        $modelWasit = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $id)->where('t_match_referee.wasit', '=', $wasit)->first();
        $appearance = TAppearance::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->where('level', '=', 1)->get()->toArray();
        $total = TAppearance::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->where('level', '=', 3)->first();

        return view('transaksi.t-match.appearance.show', [
            'model' => $model,
            'event' => $event,
            'modelWasit' => $modelWasit,
            'appearance' => $appearance,
            'total' => $total,
        ]);
    }

    public function create($id) {
        $model = TMatch::find($id);
        $event = TEvent::find($model->id_t_event);

        $data = MAppearance::where('level', '=', 1)
            ->whereNull('deletedon')
            ->orderBy('order_by')
            ->get()
            ->toArray();

        $wst1 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->select(['t_match_referee.id', 't_match_referee.wasit', 'users.name'])->where('id_t_match', '=', $id)->where('posisi', '=', 'Crew Chief')->first();
        $wst2 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->select(['t_match_referee.id', 't_match_referee.wasit', 'users.name'])->where('id_t_match', '=', $id)->where('posisi', '=', 'Official 1')->first();
        $wst3 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->select(['t_match_referee.id', 't_match_referee.wasit', 'users.name'])->where('id_t_match', '=', $id)->where('posisi', '=', 'Official 2')->first();

        return view('transaksi.t-match.appearance.create', [
            'model' => $model,
            'event' => $event,
            'data' => $data,
            'wst1' => $wst1,
            'wst2' => $wst2,
            'wst3' => $wst3,
        ]);
    }

    public function store(Request $request, $id) {
        $data = MAppearance::where('level', '=', 1)
            ->whereNull('deletedon')
            ->orderBy('order_by')
            ->get()
            ->toArray();

        DB::beginTransaction();
        if ($data) {

            # DATA AKHIR
            $sumtotal1 = 0;
            $sumtotal2 = 0;
            $sumtotal3 = 0;
            $avgTotal1 = 0;
            $avgTotal2 = 0;
            $avgTotal3 = 0;
            $count1    = 0;
            $count2    = 0;
            $count3    = 0;
            $akhir1    = 0;
            $akhir2    = 0;
            $akhir3    = 0;
            $countData = 0;

            foreach ($data as $item) {
                $child = MAppearance::where('id_m_appearance', '=', $item['id'])->whereNull('deletedon')->orderBy('order_by')->get()->toArray();
                if ($child) {
                    # DATA DETAIL
                    $sum1 = 0;
                    $sum2 = 0;
                    $sum3 = 0;
                    $tot1 = 0;
                    $tot2 = 0;
                    $tot3 = 0;

                    # PERULANGAN SUB ITEM
                    foreach ($child as $subitem) {
                        # VALIDASI PENILAIAN KOSONG
                        if (empty($request->index[$subitem['id']][$request->wst1]) || empty($request->index[$subitem['id']][$request->wst2]) || empty($request->index[$subitem['id']][$request->wst3])) {
                            DB::rollBack();
                            Session::flash('error', 'Nilai belum lengkap, mohon lengkapi penilaian.');
                            return redirect(route('appearance.create', $id))->withInput();
                        }

                        # INSERT MODEL CHILD
                        $val = $this->insertChildA($id, $request->wst1, $subitem, $item, $request->index[$subitem['id']][$request->wst1]);
                        if ($val == 500) {
                            DB::rollBack();
                            Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                            return redirect(route('mechanical-court.create', $id))->withInput();
                        }

                        $val = $this->insertChildA($id, $request->wst2, $subitem, $item, $request->index[$subitem['id']][$request->wst2]);
                        if ($val == 500) {
                            DB::rollBack();
                            Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                            return redirect(route('mechanical-court.create', $id))->withInput();
                        }

                        $val = $this->insertChildA($id, $request->wst3, $subitem, $item, $request->index[$subitem['id']][$request->wst3]);
                        if ($val == 500) {
                            DB::rollBack();
                            Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                            return redirect(route('mechanical-court.create', $id))->withInput();
                        }
                        # END INSERT MODEL CHILD

                        # PERHITUNGAN TOTAL SUB ITEM
                        $sum1 = $sum1 + $request->index[$subitem['id']][$request->wst1];
                        $sum2 = $sum2 + $request->index[$subitem['id']][$request->wst2];
                        $sum3 = $sum3 + $request->index[$subitem['id']][$request->wst3];

                        # COUNTING ITEM PER SUB
                        $tot1++;
                        $tot2++;
                        $tot3++;

                        # COUNTING ITEM KESELURUHAN
                        $count1++;
                        $count2++;
                        $count3++;
                    }
                    # PERHITUNGAN RATA RATA PARENT ITEM
                    $avg1 = $sum1 / $tot1;
                    $avg2 = $sum2 / $tot2;
                    $avg3 = $sum3 / $tot3;

                    # PERHITUNGAN HASIL AKHIR PARENT ITEM
                    $hasil1 = $avg1 * ( $item['persentase'] / 100 );
                    $hasil2 = $avg2 * ( $item['persentase'] / 100 );
                    $hasil3 = $avg3 * ( $item['persentase'] / 100 );

                    # PENGUMPULAN TOTAL NILAI AWAL
                    $sumtotal1 = $sumtotal1 + $sum1;
                    $sumtotal2 = $sumtotal2 + $sum2;
                    $sumtotal3 = $sumtotal3 + $sum3;

                    # PENGUMPULAN TOTAL NILAI AKHIR
                    $avgTotal1 = $avgTotal1 + $avg1;
                    $avgTotal2 = $avgTotal2 + $avg2;
                    $avgTotal3 = $avgTotal3 + $avg3;

                    # PENGUMPULAN TOTAL NILAI AKHIR
                    $akhir1    = $akhir1 + $hasil1;
                    $akhir2    = $akhir2 + $hasil2;
                    $akhir3    = $akhir3 + $hasil3;

                    # END INSERT MODEL PAREMT
                    $val = $this->insertParentA($id, $request->wst1, $item, $sum1, $avg1, $hasil1);
                    if ($val == 500) {
                        DB::rollBack();
                        Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                        return redirect(route('game-management.create', $id))->withInput();
                    }

                    $val = $this->insertParentA($id, $request->wst2, $item, $sum2, $avg2, $hasil2);
                    if ($val == 500) {
                        DB::rollBack();
                        Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                        return redirect(route('game-management.create', $id))->withInput();
                    }

                    $val = $this->insertParentA($id, $request->wst3, $item, $sum3, $avg3, $hasil3);
                    if ($val == 500) {
                        DB::rollBack();
                        Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                        return redirect(route('game-management.create', $id))->withInput();
                    }
                    # END INSERT MODEL PARENT
                }
                $countData++;
            }

            $insertTotal = new TAppearance();
            $insertTotal->referee = $request->wst1;
            $insertTotal->nama    = 'Total';
            $insertTotal->level   = 3;
            $insertTotal->id_t_match = $id;
            $insertTotal->persentase = $sumtotal1 / $count1;
            $insertTotal->order_by = 1;
            $insertTotal->sum      = $sumtotal1;
            $insertTotal->avg      = $avgTotal1 / $countData;
            $insertTotal->nilai    = $akhir1;
            $insertTotal->createdby  = Auth::id();
            $insertTotal->createdon  = Carbon::now();
            $insertTotal->save();

            $insertTotal = new TAppearance();
            $insertTotal->referee = $request->wst2;
            $insertTotal->nama    = 'Total';
            $insertTotal->level   = 3;
            $insertTotal->id_t_match = $id;
            $insertTotal->persentase = $sumtotal2 / $count2;
            $insertTotal->order_by = 1;
            $insertTotal->sum      = $sumtotal2;
            $insertTotal->avg      = $avgTotal2 / $countData;
            $insertTotal->nilai    = $akhir2;
            $insertTotal->createdby  = Auth::id();
            $insertTotal->createdon  = Carbon::now();
            $insertTotal->save();

            $insertTotal = new TAppearance();
            $insertTotal->referee = $request->wst3;
            $insertTotal->nama    = 'Total';
            $insertTotal->level   = 3;
            $insertTotal->id_t_match = $id;
            $insertTotal->persentase = $sumtotal3 / $count3;
            $insertTotal->order_by = 1;
            $insertTotal->sum      = $sumtotal3;
            $insertTotal->avg      = $avgTotal3 / $countData;
            $insertTotal->nilai    = $akhir3;
            $insertTotal->createdby  = Auth::id();
            $insertTotal->createdon  = Carbon::now();
            $insertTotal->save();

            $evaluation1 = TMatchEvaluation::where('id_t_match', '=', $id)->where('referee', '=', $request->wst1)->first();
            if (empty($evaluation1)) {
                $evaluation1 = new TMatchEvaluation();
                $evaluation1->id_t_match = $id;
                $evaluation1->referee = $request->wst1;
                $evaluation1->createdby  = Auth::id();
                $evaluation1->createdon  = Carbon::now();
            }
            $evaluation1->appearance      = $akhir1 * ( 5 / 100 );
            $evaluation1->total_score     = $evaluation1->play_calling + $evaluation1->game_management + $evaluation1->mechanical_court + $evaluation1->appearance;
            $evaluation1->modifiedby      = Auth::id();
            $evaluation1->modifiedon      = Carbon::now();
            $evaluation1->save();

            $evaluation2 = TMatchEvaluation::where('id_t_match', '=', $id)->where('referee', '=', $request->wst2)->first();
            if (empty($evaluation2)) {
                $evaluation2 = new TMatchEvaluation();
                $evaluation2->id_t_match = $id;
                $evaluation2->referee = $request->wst2;
                $evaluation2->createdby  = Auth::id();
                $evaluation2->createdon  = Carbon::now();
            }
            $evaluation2->appearance      = $akhir2 * ( 5 / 100 );
            $evaluation2->total_score     = $evaluation2->play_calling + $evaluation2->game_management + $evaluation2->mechanical_court + $evaluation2->appearance;
            $evaluation2->modifiedby      = Auth::id();
            $evaluation2->modifiedon      = Carbon::now();
            $evaluation2->save();

            $evaluation3 = TMatchEvaluation::where('id_t_match', '=', $id)->where('referee', '=', $request->wst3)->first();
            if (empty($evaluation3)) {
                $evaluation3 = new TMatchEvaluation();
                $evaluation3->id_t_match = $id;
                $evaluation3->referee = $request->wst3;
                $evaluation3->createdby  = Auth::id();
                $evaluation3->createdon  = Carbon::now();
            }
            $evaluation3->appearance      = $akhir3 * ( 5 / 100 );
            $evaluation3->total_score     = $evaluation3->play_calling + $evaluation3->game_management + $evaluation3->mechanical_court + $evaluation3->appearance;
            $evaluation3->modifiedby      = Auth::id();
            $evaluation3->modifiedon      = Carbon::now();
            $evaluation3->save();

            DB::commit();
            Session::flash('success', 'Appearance berhasil dibuat.');
            return redirect()->route('t-match.show', $id);
        }
        return redirect()->route('t-match.show', $id);
    }

    public function insertChildA($id, $wasit, $subitem, $item, $nilai) {
        $model = new TAppearance();
        $model->referee = $wasit;
        $model->nama    = $subitem['nama'];
        $model->level   = 2;
        $model->id_t_match = $id;
        $model->id_m_appearance = $subitem['id'];
        $model->id_parent  = $item['id'];
        $model->persentase = null;
        $model->order_by   = $subitem['order_by'];
        $model->nilai      = $nilai;
        $model->createdby  = Auth::id();
        $model->createdon  = Carbon::now();
        if ($model->save()) {
            return 200;
        }
        return 500;
    }

    public function insertParentA($id, $wasit, $item, $sum, $avg, $hasil) {
        $model = new TAppearance();
        $model->referee = $wasit;
        $model->nama    = $item['nama'];
        $model->level   = 1;
        $model->id_t_match = $id;
        $model->id_m_appearance = $item['id'];
        $model->persentase = $item['persentase'];
        $model->order_by   = $item['order_by'];
        $model->sum        = $sum;
        $model->avg        = $avg;
        $model->nilai      = $hasil;
        $model->createdby  = Auth::id();
        $model->createdon  = Carbon::now();
        if ($model->save()) {
            return 200;
        }
        return 500;
    }
}
