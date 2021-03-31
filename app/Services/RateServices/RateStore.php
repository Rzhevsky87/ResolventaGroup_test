<?php

namespace App\Services\RateServices;

use App\Models\Rate\Rate;
use Illuminate\Support\Facades\Log;

trait RateStore
{
    public static function rateStore($data)
    {
        if(is_array($data['payload']['rate'])) {
            foreach($data['payload']['rate'] as $key => $value) {
                $rate = new Rate();
                $rate->from = $data['payload']['from'];
                $rate->to = $data['payload']['to'];
                Log::debug([$key, $value]);
                $rate->date = $value['date'];
                $rate->rate = $value['rate'];
                $rate->save();
            }
        }
        if(is_float($data['payload']['rate'])) {
            $rate = new Rate();
            $rate->from = $data['payload']['from'];
            $rate->to = $data['payload']['to'];
            $rate->date = $data['payload']['date'];
            $rate->rate = $data['payload']['rate'];
            $rate->save();
        }
    }
}
