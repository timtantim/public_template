<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Http;
use DB;
use Auth;
use Str;

class ThirdPartyController extends Controller
{
    /**
     * 第三方驗證回傳Code.
     *
     * @param  \App\Http\Requests\Auth\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request)
    {
     
        $code=$request->input('code');
        $oauth_clisnt = DB::table('oauth_clients')->where('user_id', Auth::user()->id)->first();
        $state = $request->session()->pull('state');
        $codeVerifier = $request->session()->pull('code_verifier');
        // throw_unless(
        //     strlen($state) > 0 && $state === $request->state,
        //     InvalidArgumentException::class
        // );
        $response = Http::asForm()->post(url('https://www.thirdparty.com/oauth/token'), [
            'grant_type' => 'authorization_code',
            'client_id' => '',
            'client_secret' => '',
            'redirect_uri' => '',
            // 'code_verifier' => $codeVerifier,
            'code' => $code
        ]);
        if($response->status()!=200){
            return $response;
        }
        $response_array=json_decode((string) $response->getBody(), true);
        DB::table('users')->where('id', Auth::user()->id)->update([
            'third_party_api_token' => $response_array['access_token'],
            'third_party_api_refresh_token'=> $response_array['refresh_token']
        ]);
        return redirect(\App::getLocale() . RouteServiceProvider::HOME);
    }
  /**
     * 第三方驗證轉址.
     *
     * @param  \App\Http\Requests\Auth\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function third_party_login_redirect(Request $request)
    {
        $request->session()->put('state', $state = Str::random(40));
        $request->session()->put(
            'code_verifier', $code_verifier = Str::random(128)
        );
        $codeChallenge = strtr(rtrim(
            base64_encode(hash('sha256', $code_verifier, true))
        , '='), '+/', '-_');

        $query = http_build_query([
            'client_id' => '',
            'redirect_uri' => '',
            'response_type' => 'code',
            'scope' => 'wallet:accounts:read',
        ]);
        return redirect('https://www.thirdparty.com/oauth/authorize?'.$query);
    }
    
}
