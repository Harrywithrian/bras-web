<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TFile extends Model
{
    use HasFactory;

    protected $table = "t_file";

    const CREATED_AT = null;
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $fillable = [
        'name',
        'path',
        'extension',
    ];
}
