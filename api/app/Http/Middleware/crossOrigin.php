<?php

namespace App\Http\Middleware;
date_default_timezone_set('Europe/London');
use Closure;

class crossOrigin
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
        header('Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, PATCH, DELETE');
        header("Access-Control-Allow-Origin: *");  
        header('Access-Control-Allow-Headers: Authorization, Content-Type' );

        return $next($request);
    }
}
