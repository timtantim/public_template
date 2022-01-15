<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutUserController extends Controller
{
     /**
   * FUN logout
   * 登出使用者
   *
   * @return \Illuminate\Routing\Redirector
   */
  public function logout()
  {
    Auth::logout();
    Session::flush();

    return redirect(\App::getLocale().'login');
  }
}
