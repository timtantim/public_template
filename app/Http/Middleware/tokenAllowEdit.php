<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class tokenAllowEdit
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
        if ($request->user()->tokenCan('edit')) {
            return $next($request);
        }else{
            return response('無權限',500);
        }
    }
}
