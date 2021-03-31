<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RateServices\RateService;
use App\Services\RateServices\RateStore;
use Illuminate\Support\Facades\Log;

class RateController extends Controller
{
    public function index()
    {
        return view('components.request-for-quotation');
    }

    public function getRate(Request $request)
    {
        $rateService = new RateService($request->all());
        $response = $rateService->getRate();

        if(!$response['inBase']){
            RateStore::rateStore($response);
        }

        return response()->json($response['payload']);
    }
}
