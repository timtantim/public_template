<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CheckVerifyRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Mail\VerifyCode;
use App\Models\Pages;
use Illuminate\Support\Facades\Log;
use App\Models\Record;
use App\Models\User;
use App\Notifications\SendNotification;
use App\Actions\Generate_Verify_Code;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Laravel\Passport\Token;

class AccountApiController extends Controller
{
    /**
     * query_pages
     *
     * 讀取所有的頁面資料.
     *
     * @authenticated
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     * @responseField API 狀態 啟用
     */
    public function query_pages(Request $request)
    {
        Log::info('Query Pages',['user_name'=>Auth::user()->name]);
        $pages = PageResource::collection(Cache::remember('pages', 60 * 60 * 24, function () {
            return Pages::all();
        }));
        
        // Auth::user()->name
        // Notification::send(new SendNotification('123'));
        // Auth::user()->notify(new SendNotification(Auth::user()->name));
        return response($pages, 200);

    }

    /**
     * customlogin
     *
     * 用來登入使用者帳號密碼.
     *
     * 成功登入會回傳token，之後調用API 必須使用該Token 作為金鑰.
     *
     * @unauthenticated
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     * @responseField token 金鑰.
     */
    public function customlogin(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => ['required'],
            'password' => ['required'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response($errors, 500);
        }
        if (!auth()->attempt($request->all())) {
            return response(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();

        // 建立沒有範圍的 Token...
        $token = $user->createToken('Token Name')->accessToken;
        // $token = $user->createToken($user->name . '-' . now(),['read']);//,['place-orders']

        // $user = Auth::user();
        // $token = $user->createToken('Personal');
        // $token = $user->createToken($user->name . '-' . now())->accessToken;//,['place-orders']
        return response($token, 200);

    }

    /**
     * verify_code
     *
     * 隨機產生驗證碼到資料庫.
     *
     * @authenticated
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     * @responseField API 狀態 啟用
     */
    public function verify_code(Request $request,Generate_Verify_Code $action)
    {
        // return $action->handle(6,Auth::user()->id,Auth::user()->email);
        return $action->handle(6,1,'yutengchen0116@gmail.com');
    }


    /**
     * action_record
     *
     * 歷史紀錄
     *
     * @authenticated
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     * @responseField API 狀態 啟用
     */
    public function action_record(Request $request)
    {
        $user_id = Auth::user()->id;
        try {
            //檢查驗證碼是否正確且時間是否超過
            // $data = User::with('records')->get();
            $data = DB::table('records')->where('user_id',$user_id)->get();
            return response($data, 200);
        } catch (\Exception $e) {
            return response('Error - ' . $e, 500);
        }
    }

}
