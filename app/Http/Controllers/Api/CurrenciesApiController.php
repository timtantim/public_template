<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CurrencyRequest;
use App\Traits\ResponseAPI;
use Auth;

/**
 * @Currency Management
 *
 * APIs for managing currency
 */
class CurrenciesApiController extends Controller
{
    use ResponseAPI;
 /**
     * currency_exchange
     *
     * 換匯查詢 ，填入來源貨幣、目標貨幣和金額，系統會抓去匯率並回傳目標貨幣的金額 。目前系統來源貨幣與目標貨幣僅提供 TWD台幣、JPY日本和USD美金。
     *
     * @authenticated
     * @bodyParam source_currency string required 來源貨幣，請輸入TWD、JPY或USD 其中一個貨幣
     * @bodyParam target_currency string required 目標貨幣，請輸入TWD、JPY或USD 其中一個貨幣
     * @bodyParam price int required 金額，請輸入大於等於1的整數
     * @group Currency Management
     * @response {
     *    "message": "Target price",
     *    "error": false,
     *    "code": 200,
     *    "results": "4,527.55"
     * }
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     * @responseField message 提示訊息   
     * @responseField error bool 錯誤狀態 
     * @responseField code int HTTP Code  
     * @responseField results float 換匯後的金額  
     */
    public function currency_exchange(CurrencyRequest $request)
    {
        $target_price=$request->exchange_rate();
        return $target_price;
    }
}
