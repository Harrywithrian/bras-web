<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Region;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RegionController extends Controller
{
    public function index() {
        return view('master.region.index');
    }

    public function get(Request $request) {
        if ($request->ajax()) {
            $data = Region::select(['id', 'kode', 'region'])
                ->whereNull('deletedon')
                ->get();

            return $this->dataTable($data);
        }
        return null;
    }

    public function dataTable($data) {
        $dataTables = DataTables::of($data);

        # KOLOM INDEX ANGKA
        $dataTables = $dataTables->addIndexColumn();

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $view = '<a class="btn btn-success" style="padding:5px;" href="' . route('region.index', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
            $edit = '<a class="btn btn-warning" style="padding:5px; margin-left:5px;" href="' . route('region.index', $row->id) . '"> &nbsp<i class="bi bi-pencil-square"></i> </a>';
            $delete = '<a class="btn btn-danger" style="padding:5px; margin-left:5px;" href="' . route('region.index', $row->id) . '"> &nbsp<i class="bi bi-trash"></i> </a>';

            $button = $view;
            $button .= $edit;
            $button .= $delete;
            return $button;
        });

        $dataTables = $dataTables->rawColumns(['action'])->make(true);
        return $dataTables;
    }
}
