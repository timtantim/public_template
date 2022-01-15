<?php

namespace App\Actions;
use Auth;
use DB;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Models\Record;
class Check_Verify_Code
{
    use ResponseAPI;
    public function handle(string $cryptocurrency = null,int $user_id=null)
    {
        $action="提幣";
        $request = new Request([
            'cryptocurrency' => $cryptocurrency,
            'user_id' => $user_id,
        ]);
        $validator = \Validator::make($request->all(), [
            'cryptocurrency' => ['required'],
            'user_id' => ['required','integer'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->error($errors, 400);
        }
        try {
            //檢查驗證碼是否正確且時間是否超過

            $get_user_data = DB::table('users')->where('id', $user_id)->where('verify_code', $cryptocurrency)->first();
            if ($get_user_data) {
                $get_verify_code_due_time = $get_user_data->verify_code_due_time;

                $current_time = date("Y-m-d H:i:s");
                $get_verify_code_due_time = date($get_verify_code_due_time);
                if ($current_time > $get_verify_code_due_time) {
                    Record::create([
                        'user_id' => $user_id,
                        'action' => $action,
                        'message' => '驗證碼失效',
                        'status' => '1',
                    ]);
                    return response('驗證碼失效', 500);
                }

            } else {
                Record::create([
                    'user_id' => $user_id,
                    'action' => $action,
                    'message' => '驗證碼錯誤',
                    'status' => '1',
                ]);
                return response('驗證碼錯誤', 500);
            }
            //執行用戶要做的事情

            //清空驗證碼
            DB::table('users')->where('id', $user_id)->update([
                'verify_code' => null,
                'verify_code_due_time' => null,
            ]);
            Record::create([
                'user_id' => $user_id,
                'action' => $action,
                'message' => '提幣申請成功',
                'status' => '1',
            ]);
            return response('驗證成功', 200);
        } catch (\Exception $e) {
            return response('Error - ' . $e, 500);
        }
    }
}
