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
        $response = Http::asForm()->post(url('https://www.coinbase.com/oauth/token'), [
            'grant_type' => 'authorization_code',
            'client_id' => 'ff6597aef98a6120d62cf14cd6ac2257d276cea7f592259b325d89ef380e1522',
            'client_secret' => '9ea1f7ba6ec28be7208da769ce3ad9d00bebe29ba5f31f08f99b51b07ec69c73',
            'redirect_uri' => 'http://ec2-54-254-86-34.ap-southeast-1.compute.amazonaws.com/E-Wallet/third_party_callback',
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
            'client_id' => 'ff6597aef98a6120d62cf14cd6ac2257d276cea7f592259b325d89ef380e1522',
            'redirect_uri' => 'http://ec2-54-254-86-34.ap-southeast-1.compute.amazonaws.com/E-Wallet/third_party_callback',
            'response_type' => 'code',
            'scope' => 'wallet:accounts:read',
            // 'state' => $state,
            // 'code_challenge' => $codeChallenge,
            // 'code_challenge_method' => 'S256',
        ]);
        return redirect('https://www.coinbase.com/oauth/authorize?'.$query);
        // return redirect('https://www.coinbase.com/oauth/authorize?client_id=ff6597aef98a6120d62cf14cd6ac2257d276cea7f592259b325d89ef380e1522&redirect_uri=http%3A%2F%2Fec2-54-254-86-34.ap-southeast-1.compute.amazonaws.com%2FE-Wallet%2Fthird_party_callback&response_type=code&scope=wallet%3Auser%3Aread');
    }
    
}
