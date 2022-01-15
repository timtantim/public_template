<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use DB;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
     
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Attempt to gain auth token
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create_oauth_token()
    {
        $oauth_clisnt=DB::table('oauth_clients')->where('user_id',Auth::user()->id)->first();
        $response = Http::asForm()->post(env('PROXY_URL'.'/oauth/token',url('oauth/token')), [
            'grant_type' => 'password',
            'client_id' => $oauth_clisnt->id,
            'client_secret' => $oauth_clisnt->secret,
            // 'client_id' => env('API_CLIENT_ID'),
            // 'client_secret' => env('API_CLIENT_SECRET'),
            'username' => $this->email,
            'password' => $this->password,
            'scope' => '*',
        ]);
        $status_code=$response->status();
        $response_array=json_decode((string) $response->getBody(), true);
        if($status_code!=200){
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
        User::where('id', Auth::user()->id)->update([
            'api_token' => $response_array['access_token'],
            'refresh_token' => $response_array['refresh_token']
        ]);
        return ['access_token'=>$response_array['access_token'],'refresh_token'=>$response_array['refresh_token']];
    }

/**
     * Attempt to refresh auth token
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function refresh_oauth_token()
    {
        $oauth_clisnt=DB::table('oauth_clients')->where('user_id',Auth::user()->id)->first();
        $response = Http::asForm()->post(env('PROXY_URL'.'/oauth/token',url('oauth/token')), [
            'grant_type' => 'refresh_token',
            'refresh_token' => Auth::user()->refresh_token,
            'client_id' => $oauth_clisnt->id,
            'client_secret' => $oauth_clisnt->secret,
            'scope' => '',
        ]);
        $status_code=$response->status();
        $response_array=json_decode((string) $response->getBody(), true);
        if($status_code!=200){
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
        User::where('id', Auth::user()->id)->update([
            'api_token' => $response_array['access_token'],
            'refresh_token' => $response_array['refresh_token']
        ]);
        return ['access_token'=>$response_array['access_token'],'refresh_token'=>$response_array['refresh_token']];
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')).'|'.$this->ip();
    }
}
