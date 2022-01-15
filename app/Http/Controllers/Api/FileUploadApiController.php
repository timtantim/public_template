<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Traits\ResponseAPI;
use Auth;
use Validator;
use Illuminate\Http\Request;
use Storage;

/**
 * @File Management
 *
 * APIs for managing files
 */
class FileUploadApiController extends Controller
{
    use ResponseAPI;


    /**
     * show_user_file
     *
     * 讀取用戶檔案.
     *
     * @group File Management
     * @authenticated
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     * @responseField Base64 檔案
     */
    public function show_user_file($slug)
    {
        $storagePath = public_path('storage/files/' . Auth::user()->id . '/' . $slug);
        $b64image = base64_encode(file_get_contents($storagePath));
        return response($b64image, 200);
    }
    /**
     * upload_file
     *
     * 上傳檔案.
     * @group File Management
     * @authenticated
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     * @responseField message 提示訊息   
     * @responseField error bool 錯誤狀態 
     * @responseField code int HTTP Code  
     * @responseField results json 回傳結果 
     */
    public function upload_file(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:doc,docx,pdf,txt,csv|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 401);
        }
        try {
            if ($file = $request->file('file')) {
                $path = $file->store('public/files/'.Auth::user()->id);
                $name = $file->getClientOriginalName();
                //basename($path)//取得加密後的檔名
                //store your file into directory and db
                $save = new File();
                $save->name = $name;
                $save->path = Storage::url($path);
                $save->save();
                return $this->success("File successfully uploaded", $file);
            }

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

    }

     /**
     * upload_multiple_file
     *
     * 上傳檔案.
     * @group File Management
     * @authenticated
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     * @responseField message 提示訊息   
     * @responseField error bool 錯誤狀態 
     * @responseField code int HTTP Code  
     * @responseField results json 回傳結果 
     */
    public function upload_multiple_file(Request $request)
    {
        if(!$request->hasFile('files')) {
            return $this->error('upload_file_not_found', 400);
        }
       
        try {
            $allowedfileExtension=['pdf','jpg','png','doc','docx','pdf','txt','csv'];

            foreach($request->file('files') as $file)
            {
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension,$allowedfileExtension);
                if($check) {
                    //basename($path)//取得加密後的檔名
                    $path = $file->store('public/files/'.Auth::user()->id);
                    $name = $file->getClientOriginalName();
    
                    $save = new File();
                    $save->name = $name;
                    $save->path = Storage::url($path);
                    $save->save();
                }
            }
            return $this->success("File successfully uploaded", $file);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

    }
}
