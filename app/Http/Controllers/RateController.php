<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RateServices\RateService;
use Illuminate\Support\Facades\Log;

class RateController extends Controller
{
    public function index()
    {
        return view('components.request-for-quotation');
    }

    public function getRate(Request $request)
    {
        // Log::debug([__METHOD__, $request->all()]);

        $rateService = new RateService($request->all());
        $response = $rateService->getRate();

        // dd($response);
        // Log::debug([__METHOD__, $response]);

        return response()->json($response);
    }
}
