<?php

namespace App\Http\Middleware;

date_default_timezone_set('Europe/London');

use Closure;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        $match_token = DB::table('user_sessions')
            ->where('token', $request->get('token'))
            ->where('expires_at', '>' , Carbon::now() )
            ->get(); 

        if( count($match_token) > 0 ){
            return $next($request);
        }else{
            return redirect('kiosk-admin-login'); 
        }

    }
}
