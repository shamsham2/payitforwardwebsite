<?php

namespace App\Http\Middleware;

date_default_timezone_set('Europe/London');

use Closure;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class checkApiToken
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

        if(config('services.api.api_token') == $request->get('api_token')){
          
            return $next($request);

        }else{

            if($request->has('token')){ 
                $match_token = DB::table('user_sessions')
                ->where('token', $request->get('token'))
                ->where('expires_at', '>' , Carbon::now() )
                ->get(); 

                if( count($match_token) > 0 ){
                    return $next($request);
                }else{abort(403);}
            }

            abort(403);
        }

    }
}
