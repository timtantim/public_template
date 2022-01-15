<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class tokenAllowRead
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->tokenCan('read')) {
            return $next($request);
        }else{
            return response('無權限',500);
        }
    }
}
