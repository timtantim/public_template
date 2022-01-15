<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckIpMiddleware
{
    // public $whiteIps = ['192.168.1.1', '127.0.0.1'];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!in_array($request->ip(), config('app.white_ip_address'))) {
    
            /*
                 You can redirect to any error page. 
            */
            return response()->json(['your ip address is not valid.']);
        }
    
        return $next($request);
    }
}
