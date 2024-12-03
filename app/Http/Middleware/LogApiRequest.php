<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\LogApi;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogApiRequest
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        if ($token) {
            $user = JWTAuth::parseToken()->authenticate();
            $user = $user->username;
        } else {
            $user = null;
        }

        // Menyimpan parameter request sebelum melanjutkan ke proses berikutnya
        $params = $request->all();
        $method = $request->method();
        $url = $request->url();

        // Lanjutkan request ke controller
        $response = $next($request);

        $log = new LogApi();
        $log->status = 'success';
        $log->method = $method;
        $log->url = $url;
        $log->params = json_encode($params);
        $log->response = $response->getContent();
        $log->user = $user;
        $log->save();

        return $response;
        
    }
}