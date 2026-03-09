<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class ExchangeRateController extends Controller
{

    // Show currencies
    public function currencies()
    {
        $response = Http::get('https://open.er-api.com/v6/latest/USD');

        $data = $response->json();

        $rates = $data['rates'] ?? [];

        // Convert to code => name format (for your blade)
        $currencies = [];

        foreach ($rates as $code => $value) {
            $currencies[$code] = $code;
        }

        return view('currencies', compact('currencies'));
    }


    // Get exchange rate
    public function rate()
    {
        $response = Http::get('https://open.er-api.com/v6/latest/USD');

        $data = $response->json();

        $rate = $data['rates']['INR'] ?? 0;

        return view('rate', compact('rate'));
    }


    // Convert currency
    public function convert()
    {
        $amount = 100;

        $response = Http::get('https://open.er-api.com/v6/latest/USD');

        $data = $response->json();

        $rate = $data['rates']['INR'] ?? 0;

        $result = $amount * $rate;

        return view('convert', compact('result','amount'));
    }

}