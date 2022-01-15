<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use DB;
class VerifyCodeRequest extends FormRequest
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
            //
        ];
    }

        /**
     * Attempt to verify code before execute.
     *
     * @return [boolean,message]
     */
    public function verify_code()
    {
        $get_user_data = DB::table('users')->where('id', Auth::user()->id)->where('verify_code', $this->verify_code)->first();
        if ($get_user_data) {
            $get_verify_code_due_time = $get_user_data->verify_code_due_time;

            $current_time = date("Y-m-d H:i:s");
            $get_verify_code_due_time = date($get_verify_code_due_time);
            if ($current_time > $get_verify_code_due_time) {
                return [false,'驗證碼失效'];
            } else {
                //清空驗證碼
                DB::table('users')->where('id', Auth::user()->id)->update([
                    'verify_code' => null,
                    'verify_code_due_time' => null,
                ]);
            }

        } else {
            return [false,'驗證碼錯誤'];
        }
        return [true,'驗證成功'];
    }

}
