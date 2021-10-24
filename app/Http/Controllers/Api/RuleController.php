<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// rule book, contain pdf
class RuleController extends Controller
{
    // rules
    public function rules()
    {
        return response()->json([
            'statusCode' => 200,
            'message' => []
        ]);
    }
}
