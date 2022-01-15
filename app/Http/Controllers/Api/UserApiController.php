<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UserRequest;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

/**
 * @User Management
 *
 * APIs for managing users
 */
class UserApiController extends Controller
{
    protected $userRepository;

    /**
     * Create a new constructor for this controller
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * all_user
     *
     * 取得後台人員資料.
     *
     * @authenticated
     * @bodyParam row_perpage int 每頁共多少比數
     * @bodyParam filter string[] 過濾資料，Example: [["name","manager"],["email","manager@gmail.com"]]
     * @bodyParam sort string 排序 Example: asc、desc
     * @bodyParam page_num int 頁數
     * @group User Management
     * @response {
     *  "message": "All",
     *  "error": false,
     *  "code": 200,
     *  "results": [
     *    {
     *        "id": 1,
     *        "name": "YT",
     *        "verify_code": "hje1tu",
     *        "verify_code_due_time": "2021-12-09 11:14:59",
     *        "email": "yuteng@gmail.com",
     *        "email_verified_at": "2021-12-07T04:19:12.000000Z",
     *        "deleted_at": null,
     *        "created_at": null,
     *        "updated_at": "2021-12-10T08:14:59.000000Z"
     *    }
     *  ]
     *}
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     * @responseField message 提示訊息   
     * @responseField error bool 錯誤狀態 
     * @responseField code int HTTP Code  
     * @responseField results json 回傳結果 
     */
    public function all_user(Request $request)
    {
        Log::info('Showing all user profile by user: ' . Auth::user()->name);
        return $this->userRepository->all();
    }

    /**
     * all_delete_user
     *
     * 取得後台已刪除人員資料.
     *
     * @authenticated
     * @bodyParam row_perpage int 每頁共多少比數
     * @bodyParam filter string[] 過濾資料，Example: [["name","manager"],["email","manager@gmail.com"]]
     * @bodyParam sort string 排序 Example: asc、desc
     * @bodyParam page_num int 頁數
     * @group User Management
     * @response {
     *  "message": "All",
     *  "error": false,
     *  "code": 200,
     *  "results": [
     *    {
     *        "id": 1,
     *        "name": "YT",
     *        "verify_code": "hje1tu",
     *        "verify_code_due_time": "2021-12-09 11:14:59",
     *        "email": "yuteng@gmail.com",
     *        "email_verified_at": "2021-12-07T04:19:12.000000Z",
     *        "deleted_at": null,
     *        "created_at": null,
     *        "updated_at": "2021-12-10T08:14:59.000000Z"
     *    }
     *  ]
     *}
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     * @responseField message 提示訊息   
     * @responseField error bool 錯誤狀態 
     * @responseField code int HTTP Code  
     * @responseField results json 回傳結果 
     */
    public function all_delete_user(Request $request)
    {
        Log::info('Showing all delete user profile by user: ' . Auth::user()->name);
        return $this->userRepository->all_delete();
    }
    /**
     * query_user
     *
     * 取得後台人員資料.
     *
     * @authenticated
     * @bodyParam row_perpage int 每頁共多少比數
     * @bodyParam filter string[] 過濾資料，Example: [["name","manager"],["email","manager@gmail.com"]]
     * @bodyParam sort string 排序 Example: asc、desc
     * @bodyParam page_num int 頁數
     * @group User Management
     * @response {
     *  "message": "All",
     *  "error": false,
     *  "code": 200,
     *  "results": [
     *    {
     *        "id": 1,
     *        "name": "YT",
     *        "verify_code": "hje1tu",
     *        "verify_code_due_time": "2021-12-09 11:14:59",
     *        "email": "yuteng@gmail.com",
     *        "email_verified_at": "2021-12-07T04:19:12.000000Z",
     *        "deleted_at": null,
     *        "created_at": null,
     *        "updated_at": "2021-12-10T08:14:59.000000Z"
     *    }
     *  ]
     *}
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     * @responseField message 提示訊息   
     * @responseField error bool 錯誤狀態 
     * @responseField code int HTTP Code  
     * @responseField results json 回傳結果 
     */
    public function query_user(Request $request)
    {
        Log::info('Showing with pagination user profile by user: ' . Auth::user()->name);
        return $this->userRepository->get_data($request);
    }

    /**
     * query_delete_user
     *
     * 取得後台已刪除人員資料.
     *
     * @authenticated
     * @bodyParam row_perpage int 每頁共多少比數
     * @bodyParam filter string[] 過濾資料，Example: [["name","manager"],["email","manager@gmail.com"]]
     * @bodyParam sort string 排序 Example: asc、desc
     * @bodyParam page_num int 頁數
     * @group User Management
     * @response {
     *  "message": "All",
     *  "error": false,
     *  "code": 200,
     *  "results": [
     *    {
     *        "id": 1,
     *        "name": "YT",
     *        "verify_code": "hje1tu",
     *        "verify_code_due_time": "2021-12-09 11:14:59",
     *        "email": "yuteng@gmail.com",
     *        "email_verified_at": "2021-12-07T04:19:12.000000Z",
     *        "deleted_at": null,
     *        "created_at": null,
     *        "updated_at": "2021-12-10T08:14:59.000000Z"
     *    }
     *  ]
     *}
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     * @responseField message 提示訊息   
     * @responseField error bool 錯誤狀態 
     * @responseField code int HTTP Code  
     * @responseField results json 回傳結果 
     */
    public function query_delete_user(Request $request)
    {
        Log::info('Showing with delete pagination user profile by user: ' . Auth::user()->name);
        return $this->userRepository->get_data_delete($request);
    }

    /**
     * store_user
     *
     * Store a newly created resource in storage.
     *
     * @group User Management
     * @authenticated
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     * @responseField message 提示訊息   
     * @responseField error bool 錯誤狀態 
     * @responseField code int HTTP Code  
     * @responseField results json 回傳結果 
     */
    public function store_user(UserRequest $request)
    {
        Log::info('create or update user by user: ' . Auth::user()->name);
        return $this->userRepository->create($request->all());
    }

    /**
     * show_user
     *
     * Display the specified resource.
     * 
     * @response {
     *  "message": "All",
     *  "error": false,
     *  "code": 200,
     *  "results": [
     *    {
     *        "id": 1,
     *        "name": "YT",
     *        "verify_code": "hje1tu",
     *        "verify_code_due_time": "2021-12-09 11:14:59",
     *        "email": "yuteng@gmail.com",
     *        "email_verified_at": "2021-12-07T04:19:12.000000Z",
     *        "deleted_at": null,
     *        "created_at": null,
     *        "updated_at": "2021-12-10T08:14:59.000000Z"
     *    }
     *  ]
     *}
     * @group User Management
     * @authenticated
     * @response 404 {"message": "No element with ID ","error": true,"code": 404}
     * @responseField message 提示訊息   
     * @responseField error bool 錯誤狀態 
     * @responseField code int HTTP Code  
     * @responseField results json 回傳結果 
     */
    public function show_user($id)
    {
        Log::info('show specific user with id by user: ' . Auth::user()->name);
        return $this->userRepository->find($id);
    }

    /**
     * show_delete_user
     *
     * Display the specified resource.
     * 
     * @response {
     *  "message": "All",
     *  "error": false,
     *  "code": 200,
     *  "results": [
     *    {
     *        "id": 1,
     *        "name": "YT",
     *        "verify_code": "hje1tu",
     *        "verify_code_due_time": "2021-12-09 11:14:59",
     *        "email": "yuteng@gmail.com",
     *        "email_verified_at": "2021-12-07T04:19:12.000000Z",
     *        "deleted_at": null,
     *        "created_at": null,
     *        "updated_at": "2021-12-10T08:14:59.000000Z"
     *    }
     *  ]
     *}
     * @group User Management
     * @authenticated
     * @response 404 {"message": "No element with ID ","error": true,"code": 404}
     * @responseField message 提示訊息   
     * @responseField error bool 錯誤狀態 
     * @responseField code int HTTP Code  
     * @responseField results json 回傳結果 
     */
    public function show_delete_user($id)
    {
        Log::info('show specific delete user with id by user: ' . Auth::user()->name);
        return $this->userRepository->find_delete($id);
    }

    /**
     * update_or_create_user
     *
     * @bodyParam unique_attribute string required 主鍵，Example: 1
     * @bodyParam update_attribute string[] required 要更新的資料(必須JSON Stringify)，Example: [["name","manager"],["email","manager@gmail.com"]]
     * @authenticated
     * @group User Management
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     * @responseField message 提示訊息   
     * @responseField error bool 錯誤狀態 
     * @responseField code int HTTP Code  
     * @responseField results json 回傳結果  
     */
    public function update_or_create_user(Request $request)
    {
        Log::info('create or update user by user: ' . Auth::user()->name);
        return $this->userRepository->create_or_update($request);
    }

       /**
     * update_user
     *
     * @bodyParam where_attribute string required 主鍵，Example: 1
     * @bodyParam update_attribute string[] required 要更新的資料(必須JSON Stringify)，Example: [["name","manager"],["email","manager@gmail.com"]]
     * @authenticated
     * @group User Management
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     * @responseField message 提示訊息   
     * @responseField error bool 錯誤狀態 
     * @responseField code int HTTP Code  
     * @responseField results json 回傳結果 
     */
    public function update_user(Request $request)
    {
        // Log::info('create or update user by user: ' . Auth::user()->name);
        return $this->userRepository->update($request);
    }

    /**
     * destroy_user
     *
     *
     * @authenticated
     * @group User Management
     * @response 404 {"message": "No element with ID ","error": true,"code": 404}
     * @responseField message 提示訊息   
     * @responseField error bool 錯誤狀態 
     * @responseField code int HTTP Code  
     * @responseField results json 回傳結果 
     */
    public function destroy_user($id)
    {
        Log::info('delete user by user: ' . Auth::user()->name);
        return $this->userRepository->delete_data($id);
    }

    /**
     * force_destroy_user
     *
     *
     * @authenticated
     * @group User Management
     * @response 404 {"message": "No element with ID ","error": true,"code": 404}
     * @responseField message 提示訊息   
     * @responseField error bool 錯誤狀態 
     * @responseField code int HTTP Code  
     * @responseField results json 回傳結果 
     */
    public function force_destroy_user($id)
    {
        Log::info('force delete user by user: ' . Auth::user()->name);
        return $this->userRepository->force_delete_data($id);
    }
}
