<?php

use App\Http\Controllers\Auth\LogoutUserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\ThirdPartyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// Route::get('/', function () {
//     return view('welcome');
// });
Route::redirect('/', '/en/');


Route::get('/third_party_callback', [ThirdPartyController::class, 'callback'])->middleware('auth')->name('third_party_callback');
Route::group(['prefix' => '{language?}'], function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::middleware(['auth', 'verified', 'auth.timeout', 'prevent-back-history'])->group(function () {
        Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
        Route::get('/details_table_practicce', function () {return view('basic.details_table_practicce');})->name('details_table_practicce');
        Route::get('/human_resource_info', function () {return view('basic.human_resource_info');})->name('human_resource_info');
        Route::get('/vue_practice',function () {return view('basic.vue_practice')->with('isUsingVue','true');})->name('vue_practice');
        
        //第三方認證轉址
        Route::get('/third_party_login_redirect', [ThirdPartyController::class, 'third_party_login_redirect'])->name('third_party_login_redirect');
    });
    Route::middleware(['auth'])->group(function () {
        Route::get('/logout', [LogoutUserController::class, 'logout'])->name('logout:get');
    });
    require __DIR__ . '/auth.php';
});




