<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Support\Facades\Http;

class GoogleRecapchaV3
{
    public function handle($request, Closure $next)
    {
        /* production 才驗證 recaptcha */
        // if (config('app.env') === 'production') {
        //     $validator = \Validator::make($request->all(), [
        //         'g-recaptcha-response' => ['required|captcha'],
        //     ]);
        //     if ($validator->fails()) {
        //         $errors = $validator->errors();
        //         return response($errors, 500);
        //     }
        // }
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        // $remoteip = $_SERVER['REMOTE_ADDR'];
        
        $response = Http::asForm()->post($url, [
            'secret' => config('recaptchav3.secret'),
            'response' => $request->get('recaptcha'),
            'remoteip'=>null
        ]);
        $response_array = json_decode($response);  
        if ($response_array->success != true) {
            throw new Exception('g-recaptcha認證失敗');
        }
        if ($response_array->score >= 0.3) {
            //Validation was successful, add your form submission logic here
            return $next($request);
        } else {
            throw new Exception('g-recaptcha認證失敗');
        }
    }
}
