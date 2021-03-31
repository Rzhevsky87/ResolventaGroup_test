<?php

namespace App\Services\RateServices;

use App\Models\Rate\Rate;
use Illuminate\Support\Facades\Log;

trait RateStore
{
    public static function rateStore($data)
    {
        foreach($data['rate'] as $key => $value) {
            // Log::debug($data['rate']);
            $rate = new Rate();
            $rate->from = $data['from'];
            $rate->to = $data['to'];
            Log::debug([$key, $value]);
            $rate->date = $value['date'];
            $rate->rate = $value['rate'];
            $rate->save();
        }
    }
}
