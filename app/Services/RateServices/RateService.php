<?php

namespace App\Services\RateServices;

use App\Models\Rate\Rate;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class RateService
{
    /** @var string|null from what we convert  */
    public ?string $from = 'RUB';

    /** @var string|null what we convert to  */
    public ?string $to = 'USD';

    /** @var string|null start date */
    public ?string $start = NULL;

    /** @var string|null end date  */
    public ?string $end = NULL;


    /**
     * Make new RateService instance
     *
     * @return void
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $val) {
            $this->$key = $val;
        }
    }

    /**
     * Get rate on date
     *
     * @return array
     */
    public function getRate() : array
    {
        /**
         * проверка на наличие в базе
         * если запрашивается массив катировок - задача усложняется
         * надо продумать как это реализовать
         */
        $rates = Rate::where('from', $this->from)->where('to', $this->to)
            ->whereBetween('date', [$this->start, $this->end])
            ->get();
        if($rates->count() > 0) {
            if($rates->count() > 1) {
                $rateItem = [];
                foreach($rates as $rate) {
                    array_push($rateItem, ['date' => $rate->date, 'rate' => $rate->rate]);
                }
                $resp =
                    [
                        'from' => $this->from,
                        'to' => $this->to,
                        'rate' => $rateItem,
                    ];
            }
            if($rates->count() === 1) {
                $rate = $rates->first();
                $resp =
                    [
                        'from' => $this->from,
                        'to' => $this->to,
                        'date' => $rate->date,
                        'rate' => $rate->rate
                    ];
            }

            return ['payload' => $resp, 'inBase' => true];
        }

        $baseUrl = 'https://api.exchangeratesapi.io/';
        $payload =
            'history?base='.$this->from
            .'&start_at='.$this->start
            .'&end_at='.$this->end
            .'&symbols='.$this->to;
        $client = new Client(['base_uri' => $baseUrl]);
        $guzzleResponse = json_decode((string) $client->request('GET', $payload)->getBody(),1);

        $response = $this->sortResp($guzzleResponse['rates']);

        if(count($response) === 1) {
            $resp =
            [
                'from' => $this->from,
                'to' => $this->to,
                'date' => $this->start,
                'rate' => $response[0]['rate']
            ];
        } else {
            $resp =
            [
                'from' => $this->from,
                'to' => $this->to,
                'rate' => $response
            ];
        }

        return ['payload' => $resp, 'inBase' => false];
    }

    /**
     * Sort https://api.exchangeratesapi.io/ response
     *
     * @param array $exchange
     * @param array $sorted
     * @param integer $i
     *
     * @return array
     */
    public function sortResp($exchange, $sorted = [], $i = 0) : array
    {
        foreach($exchange as $key => $val) {
            if(is_array($val)) {
                array_push($sorted, ['date' => $key]);
                foreach($val as $k => $v) {
                    if($k == $this->to) {
                        $sorted[$i++]['rate'] = $v;
                    }
                }
            }
        }
        return $sorted;
    }
}
