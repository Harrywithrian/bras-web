<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TNomorSurat extends Model
{
    use HasFactory;

    protected $table = "t_nomor_surat";

    const CREATED_AT = NULL;
    const UPDATED_AT = NULL;
    const DELETED_AT = NULL;

    protected $fillable = [
        'bulan',
        'tahun',
        'count',
    ];

    public static function createNomor() {
        $month = date('n');
        $year  = date('Y');
        $romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $count = null;

        $model = TNomorSurat::where('bulan', '=', $month)->where('tahun', '=', $year)->first();
        if ($model) {
            $count = $model->count + 1;
            $model->count = $count;
        } else {
            $model = new TNomorSurat();
            $model->bulan = $month;
            $model->tahun = $year;
            $model->count = 1;
            $count = 1;
        }
        $model->save();

        $month = $month - 1;
        return str_pad($count, 3, '0', STR_PAD_LEFT) . "/" . $romawi[$month] . "/PP/" . $year;
    }
}
