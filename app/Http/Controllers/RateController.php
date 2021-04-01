<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RateServices\RateService;
use App\Services\RateServices\RateStore;
use Illuminate\Support\Facades\Log;

class RateController extends Controller
{
    /**
     * Show index page
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        return view('components.request-for-quotation');
    }

    /**
     * Retrun rate or list of rates
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function getRate(Request $request)
    {
        $rateService = new RateService($request->all());
        $response = $rateService->getRate();

        if(!$response['inBase']){
            RateStore::rateStore($response);
        }

        return response()->json(
            $response['payload']['rate'] ? $response['payload'] : ['error' => 'data is fail']
        );
    }
}
