<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => '抱歉, 您已閒置過久導致權限失效, 系統將會自動登出, 請重新登入',
                ], 401);
            }
        });

        $this->renderable(function (\Junges\ACL\Exceptions\UnauthorizedException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => [
                        'status' => 'pause',
                        'message' => '您或您所在的群組並無相關的權限使用此功能',
                    ],
                ], 401);
            }

            header('refresh:3;url=' . url('logout'));
            return response('抱歉, 您無權檢視此頁面!! 系統即將導回登入頁面, 請您重新登入!!', 403);
        });

        $this->renderable(function (\Exception $e) {
            if ($e->getPrevious() instanceof \Illuminate\Session\TokenMismatchException) {
                return redirect()->route('login');
            };
        });
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
        ? response()->json(['message' => $exception->getMessage()], 401)
        : redirect()->guest($exception->redirectTo() ?? route('login', app()->getLocale()));
    }
}
