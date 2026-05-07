<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ConversionHistory;

class ExchangeRateController extends Controller
{

    // ==============================
    // Show All Available Currencies
    // ==============================

    public function currencies()
    {
        $response = Http::get('https://open.er-api.com/v6/latest/USD');

        $data = $response->json();

        $rates = $data['rates'] ?? [];

        $currencies = [];

        foreach ($rates as $code => $value) {

            $currencies[$code] = $code;
        }

        return view('currencies', compact('currencies'));
    }



    // ==============================
    // Current USD -> INR Rate
    // ==============================

    public function rate()
    {
        $response = Http::get('https://open.er-api.com/v6/latest/USD');

        $data = $response->json();

        $rate = $data['rates']['INR'] ?? 0;

        return view('rate', compact('rate'));
    }



    // ==============================
    // Advanced Currency Converter
    // ==============================

    public function convert(Request $request)
    {

        $from = $request->from ?? 'USD';

        $to = $request->to ?? 'INR';

        $amount = $request->amount ?? 1;


        // API Request
        $response = Http::get("https://open.er-api.com/v6/latest/$from");

        $data = $response->json();


        // Get all currencies
        $currencies = array_keys($data['rates']);


        // Exchange Rate
        $rate = $data['rates'][$to] ?? 0;


        // Final Result
        $result = $amount * $rate;


        // Save History
        ConversionHistory::create([

            'from_currency' => $from,

            'to_currency' => $to,

            'amount' => $amount,

            'converted_amount' => $result

        ]);


        // Latest History
        $histories = ConversionHistory::latest()->take(10)->get();


        return view('convert', compact(

            'result',
            'amount',
            'from',
            'to',
            'currencies',
            'histories'

        ));
    }
}