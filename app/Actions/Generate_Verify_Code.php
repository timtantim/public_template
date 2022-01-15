<?php

namespace App\Actions;
use Auth;
use DB;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Mail\VerifyCode;
class Generate_Verify_Code
{
    use ResponseAPI;
    public function handle(int $total_character = 6,int $user_id=null,string $email=null)
    {
        $verify_code = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 6)), 0, $total_character);
        $verify_code_due_time = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " +10 minutes"));

        $request = new Request([
            'user_id' => $user_id,
            'email' => $email
        ]);

        $validator = \Validator::make($request->all(), [
            'user_id' => ['required','integer'],
            'email' => ['required','email']
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->error($errors, 400);
        }

        if($user_id==null || $user_id==""){
            return $this->error('使用者ID 不得為空值', 400);
        }
        if($email==null || $email==""){
            return $this->error('Email 不得為空值', $e->getCode());
        }
        try {
            $valueArray = [
                'name' => 'John',
                'verify_code' => $verify_code,
            ];

            \Mail::to($email)->send(new VerifyCode($valueArray));

            DB::table('users')->where('id', $user_id)->update([
                'verify_code' => $verify_code,
                'verify_code_due_time' => $verify_code_due_time,
            ]);
            return $this->success("Verify code mail send successfully", '');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
