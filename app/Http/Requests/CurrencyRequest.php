<?php

namespace App\Http\Requests;

use DB;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Traits\ResponseAPI;
use Illuminate\Support\Facades\Log;
class CurrencyRequest extends FormRequest
{
    use ResponseAPI;
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
            'source_currency' => ['required', 'string', 'in:TWD,JPY,USD'],
            'target_currency' => ['required', 'string', 'in:TWD,JPY,USD'],
            'price' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Attempt to Exchange Rate
     *
     * @return float
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function exchange_rate()
    {
        try {
            $exchange_rate = DB::table('currencies')
                ->where('source_currency', $this->source_currency)
                ->where('target_currency', $this->target_currency)
                ->first()->exchange_rate;
            $result=number_format((float) $this->price * (float) $exchange_rate, 2, '.', ',');
            Log::info('Request exchange rate by user: ' . Auth::user()->name);
            return $this->success("Target price", $result);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
