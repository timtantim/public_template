<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLanguage
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
        // \App::setLocale($request->language);
        // return $next($request);
        if (!isset($request->language) || !in_array($request->language, config('app.available_locales'))) {
            \App::setLocale('en');
            // return Redirect::to('en/');
        }else{
            \App::setLocale($request->language);
        }

        return $next($request);
    }
}
