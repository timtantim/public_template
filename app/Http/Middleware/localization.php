<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;

class localization
{
    const SESSION_KEY = 'locale';
    const LOCALES = ['en', 'zh_TW'];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check header request and determine localizaton
        $local = ($request->hasHeader('X-localization')) ? $request->header('X-localization') : 'en';
        // set laravel localization
        app()->setLocale($local);
        // continue request
        return $next($request);
    }
}
