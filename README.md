# PHP_Laravel12_Exchange_Rates


## Description

PHP_Laravel12_Exchange_Rates is a simple Laravel 12 application that fetches real-time currency exchange rates from a free API. Users can view available currencies, check the USD → INR rate, and convert amounts from USD to INR.

#### This project allows users to:

1. View a list of all supported currencies with their codes

2. See the current exchange rate from USD to INR

3. Convert any amount in USD to INR

4. Easily extend the application to include more currencies or historical exchange rates

5. It is ideal for beginners learning API integration in Laravel, Blade templating, and HTTP requests.



## Features

- Display all available currencies with code and description

- Show current exchange rate for USD → INR

- Convert any amount in USD to INR

- Beautiful responsive UI using simple HTML + CSS

- Uses free exchange rate API (Open Exchange Rates / APILayer compatible)

- Easy to extend to more currencies or historical data



## Technologies

- Laravel 12.53.0

- PHP 8.2.12

- MySQL (optional, only if you want to store logs)

- ashallendesign/laravel-exchange-rates package

- HTTP Client (Laravel) for API requests

- Simple Blade templates for front-end


---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Exchange_Rates "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Exchange_Rates

```

#### Explanation:

This command installs a fresh Laravel 12 application and creates the project folder.

The cd command moves into the newly created project directory.




## STEP 2: Database Setup (Optional)

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_Exchange_Rates
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_Exchange_Rates

```

### Then Run:

```
php artisan migrate

```


#### Explanation:

This step connects Laravel with the MySQL database.

The migration command creates Laravel’s default tables such as users, password resets, and sessions.



## STEP 3: Install Exchange Rates Package

### Run command:

```
composer require ashallendesign/laravel-exchange-rates

```

#### Explanation:

This command installs the ashallendesign/laravel-exchange-rates package which provides helper methods to retrieve currency exchange rates easily in Laravel.




## STEP 4: Publish Configuration File

### Run:

```
php artisan vendor:publish --provider="AshAllenDesign\LaravelExchangeRates\Providers\ExchangeRatesProvider"

```

### This will create config file:

```
config/laravel-exchange-rates.php

```

#### Explanation:

Publishing the package configuration copies the default settings file into your Laravel project so you can customize API keys, drivers, and caching options.





## STEP 5: Add API Key in Laravel .env

### Add:

```
EXCHANGE_RATES_API_KEY=your_api_key_here

```

#### Explanation:

The API key allows Laravel to authenticate requests to the exchange rate service and retrieve real-time currency data.




## STEP 6: Configure Driver

### Open: config/laravel-exchange-rates.php

#### Set driver:

```
'driver' => 'exchange-rate-host',

```

#### Explanation

The driver defines which service Laravel will use to fetch exchange rate data.

Here we specify the exchange-rate-host driver to retrieve currency rates.





## STEP 7: Create Controller

### Run:

```
php artisan make:controller ExchangeRateController

```

### File: app/Http/Controllers/ExchangeRateController.php

```
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

```

#### Explanation:

This command creates a controller that handles application logic such as retrieving currency data, displaying exchange rates, and converting currency values.





## STEP 8: Create Routes

### Open: routes/web.php

```
use App\Http\Controllers\ExchangeRateController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/currencies', [ExchangeRateController::class,'currencies']);

Route::get('/rate', [ExchangeRateController::class,'rate']);

Route::get('/convert', [ExchangeRateController::class,'convert']);

```

#### Explanation:

Routes define the URLs of your application and map them to controller methods so users can access pages like currencies, rate, and conversion.




## STEP 9: Create Views

### resources/views/currencies.blade.php

```
<!DOCTYPE html>
<html>

<head>
    <title>Currencies</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Segoe UI, sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
        }

        .card {
            width: 600px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            padding: 30px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .currency-list {
            max-height: 350px;
            overflow-y: auto;
            text-align: left;
        }

        .currency-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 15px;
        }

        .currency-item:hover {
            background: #f5f5f5;
        }

        .nav {
            margin-bottom: 20px;
        }

        .nav a {
            text-decoration: none;
            padding: 8px 15px;
            background: #2a5298;
            color: white;
            border-radius: 5px;
            margin: 5px;
            font-size: 14px;
        }

        .nav a:hover {
            background: #1e3c72;
        }
    </style>

</head>

<body>

    <div class="card">

        <div class="nav">
            <a href="/currencies">Currencies</a>
            <a href="/rate">Rate</a>
            <a href="/convert">Convert</a>
        </div>

        <h2>🌍 Available Currencies</h2>

        <div class="currency-list">

            @foreach($currencies as $code => $currency)

                <div class="currency-item">
                    <strong>{{ $code }}</strong> - {{ $currency['description'] ?? $currency }}
                </div>

            @endforeach

        </div>

    </div>

</body>

</html>

```


### resources/views/rate.blade.php

```
<!DOCTYPE html>
<html>

<head>
    <title>Exchange Rate</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Segoe UI;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #11998e, #38ef7d);
        }

        .card {
            background: white;
            padding: 40px;
            width: 400px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .rate {
            font-size: 28px;
            font-weight: bold;
            color: #11998e;
        }

        .nav {
            margin-bottom: 20px;
        }

        .nav a {
            text-decoration: none;
            padding: 8px 15px;
            background: #11998e;
            color: white;
            border-radius: 5px;
            margin: 5px;
            font-size: 14px;
        }

        .nav a:hover {
            background: #0d7a71;
        }
    </style>

</head>

<body>

    <div class="card">

        <div class="nav">
            <a href="/currencies">Currencies</a>
            <a href="/rate">Rate</a>
            <a href="/convert">Convert</a>
        </div>

        <h2>💱 Exchange Rate</h2>

        <div class="rate">
            1 USD = {{ $rate }} INR
        </div>

    </div>

</body>

</html>

```



### resources/views/convert.blade.php

```
<!DOCTYPE html>
<html>

<head>
    <title>Currency Converter</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Segoe UI;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .card {
            width: 400px;
            background: white;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25);
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .amount {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .result {
            font-size: 30px;
            font-weight: bold;
            color: #28a745;
            margin-top: 10px;
        }

        button {
            margin-top: 25px;
            padding: 10px 20px;
            border: none;
            background: #667eea;
            color: white;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #5566d4;
        }

        .nav {
            margin-bottom: 20px;
        }

        .nav a {
            text-decoration: none;
            padding: 8px 15px;
            background: #667eea;
            color: white;
            border-radius: 5px;
            margin: 5px;
            font-size: 14px;
        }

        .nav a:hover {
            background: #4d5bd1;
        }
    </style>

</head>

<body>

    <div class="card">

        <div class="nav">
            <a href="/currencies">Currencies</a>
            <a href="/rate">Rate</a>
            <a href="/convert">Convert</a>
        </div>

        <h2>💰 Currency Converter</h2>

        <div class="amount">
            {{ $amount ?? 100 }} USD
        </div>

        <div class="result">
            = {{ $result }} INR
        </div>

        <button onclick="location.reload()">Convert Again</button>

    </div>

</body>

</html>

```



## STEP 10:  Run Project

### Start server

```
php artisan serve

```

### Open in browser

```
http://127.0.0.1:8000/currencies

http://127.0.0.1:8000/rate

http://127.0.0.1:8000/convert

```

#### Explanation:

This command starts Laravel’s built-in development server so the application can run locally in your browser.



## Expected Output:

### List of Available Currencies:


<img width="1919" height="957" alt="Screenshot 2026-03-09 132233" src="https://github.com/user-attachments/assets/6e2dfaa2-4650-4902-a5cf-f30199d69384" />


### Current USD → INR Exchange Rate:


<img width="1919" height="946" alt="Screenshot 2026-03-09 132241" src="https://github.com/user-attachments/assets/a416ff75-d44f-4c80-829b-b14a3973cb4c" />


### USD to INR Conversion:


<img width="1918" height="942" alt="Screenshot 2026-03-09 132257" src="https://github.com/user-attachments/assets/a62368db-078c-4e54-84f2-11f5d0e8e349" />



---

# Project Folder Structure:

```
PHP_Laravel12_Exchange_Rates
│
├── app
│   └── Http
│       └── Controllers
│           └── ExchangeRateController.php
│
├── config
│   └── laravel-exchange-rates.php
│
├── resources
│   └── views
│       ├── currencies.blade.php
│       ├── rate.blade.php
│       └── convert.blade.php
│
├── routes
│   └── web.php
│
└── composer.json

```
