<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MKetuaUmum extends Model
{
    use HasFactory;

    protected $table = "m_ketua_umum";

    const CREATED_AT = false;
    const UPDATED_AT = 'modifiedon';
    const DELETED_AT = false;
}
