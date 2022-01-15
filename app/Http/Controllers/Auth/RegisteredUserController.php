<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Laravel\Passport\ClientRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        event(new Registered($user));
        $ClientRepository=new ClientRepository();
        $ClientRepository->create($user->id,$request->name.'_token',env('APP_URL','http://localhost'),'users',false,true);

        Auth::login($user);
        $oauth_clisnt=DB::table('oauth_clients')->where('user_id',Auth::user()->id)->first();
        $response = Http::asForm()->post(env('PROXY_URL'.'/oauth/token',url('oauth/token')), [
            'grant_type' => 'password',
            'client_id' => $oauth_clisnt->id,
            'client_secret' => $oauth_clisnt->secret,
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '*',
        ]);
        $status_code=$response->status();
        $response_array=json_decode((string) $response->getBody(), true);
        User::where('id', Auth::user()->id)->update([
            'api_token' => $response_array['access_token'],
            'refresh_token' => $response_array['refresh_token']
        ]);

        return redirect(\App::getLocale() .RouteServiceProvider::HOME);
    }
}
