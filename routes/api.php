<?php

use App\Http\Controllers\Api\AccountApiController;
use App\Http\Controllers\Api\FileUploadApiController;
use App\Http\Controllers\Api\TestApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\CurrenciesApiController;
use Illuminate\Support\Facades\Route;

Route::post('/customlogin', [AccountApiController::class, 'customlogin']);

Route::middleware('auth:api', 'scope:read', 'tokenAllowRead')->group(function () {
    /* 擁有Token和Read權限 即可執行讀取 */
});

Route::middleware('auth:api', 'scope:write', 'tokenAllowWrite')->group(function () {
/* 擁有Token和Write權限 即可執行新增 */
});

Route::middleware('auth:api', 'scope:edit', 'tokenAllowEdit')->group(function () {
/* 擁有Token和Edit權限 即可執行修改 */
});

Route::middleware('auth:api', 'scope:remove', 'tokenAllowRemove')->group(function () {
/* 擁有Token和Remove權限 即可執行刪除 */
});
Route::middleware('auth:api')->group(function () {
    // Route::post('/query_pages', [AccountApiController::class, 'query_pages']);
});
Route::middleware(['Cors'])->group(function () {
});

Route::post('/test_language', [TestApiController::class, 'test_language']);
// Route::resource('users', UserApiController::class);

Route::post('/update_user', [UserApiController::class, 'update_user']);
Route::middleware('auth:api')->group(function () {
    // Test API
    Route::post('/test_email', [TestApiController::class, 'test_email']);
    Route::post('/send_sms_notificaition', [TestApiController::class, 'send_sms_notificaition']);

    // User API
    Route::post('/query_pages', [AccountApiController::class, 'query_pages']);
    Route::post('/all_user', [UserApiController::class, 'all_user']);
    Route::post('/all_delete_user', [UserApiController::class, 'all_delete_user']);
    Route::post('/query_user', [UserApiController::class, 'query_user']);
    Route::post('/query_delete_user', [UserApiController::class, 'query_delete_user']);
    Route::post('/store_user', [UserApiController::class, 'store_user']);
    Route::get('/show_user/{id}', [UserApiController::class, 'show_user']);
    Route::get('/show_delete_user/{id}', [UserApiController::class, 'show_delete_user']);
    Route::post('/update_or_create_user', [UserApiController::class, 'update_or_create_user']);
    Route::delete('/destroy_user/{id}', [UserApiController::class, 'destroy_user']);
    Route::delete('/force_destroy_user/{id}', [UserApiController::class, 'force_destroy_user']);

    Route::post('/action_record', [AccountApiController::class, 'action_record']);

    //檔案管理
    Route::get('show_user_file/{slug}', [FileUploadApiController::class, 'show_user_file']);
    Route::post('upload_file', [FileUploadApiController::class, 'upload_file']);
    Route::post('upload_multiple_file', [FileUploadApiController::class, 'upload_multiple_file']);

    //換匯
    Route::post('currency_exchange', [CurrenciesApiController::class, 'currency_exchange']);
});
Route::post('verify_code', [AccountApiController::class, 'verify_code']);

//Api 檢測IP 白名單
Route::middleware(['checkIp','Cors'])->group(function () {
    
});


/**
 * API 簡易描述
 * GET
 * POST (傳入參數必須包含全部Entity 的屬性 並 新增)
 * PUT  (傳入參數必須包含全部Entity 的屬性 且 若有相同資源存在就更新)
 * PATCH(傳入參數所需更新的Entity 欄位即可 並 更新)
 * DELETE
 */
