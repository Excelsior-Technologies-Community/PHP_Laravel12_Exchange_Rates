<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\ConversionHistory;
use App\Models\FavoriteCurrency;
use App\Models\RateAlert;

class ExchangeRateController extends Controller
{
    private $cacheTime = 300; // 5 minutes cache
    
    // ==============================
    // Get Exchange Rates with Cache
    // ==============================
    
    private function getRates($base = 'USD')
    {
        $cacheKey = "exchange_rates_{$base}";
        
        return Cache::remember($cacheKey, $this->cacheTime, function () use ($base) {
            $response = Http::get("https://open.er-api.com/v6/latest/{$base}");
            return $response->json();
        });
    }

    // ==============================
    // Show All Available Currencies
    // ==============================

    public function currencies()
    {
        $data = $this->getRates('USD');
        $rates = $data['rates'] ?? [];
        
        $currencies = [];
        foreach ($rates as $code => $value) {
            $currencies[$code] = $code;
        }
        
        $favorites = FavoriteCurrency::orderBy('sort_order')->get();
        
        return view('currencies', compact('currencies', 'favorites'));
    }
    
    // ==============================
    // Add Favorite Currency
    // ==============================
    
    public function addFavorite(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|string|max:10'
        ]);
        
        FavoriteCurrency::updateOrCreate(
            ['currency_code' => strtoupper($request->currency_code)],
            ['currency_name' => $request->currency_name ?? null]
        );
        
        return redirect()->back()->with('success', 'Currency added to favorites!');
    }
    
    // ==============================
    // Remove Favorite Currency
    // ==============================
    
    public function removeFavorite($code)
    {
        FavoriteCurrency::where('currency_code', $code)->delete();
        return redirect()->back()->with('success', 'Currency removed from favorites');
    }
    
    // ==============================
    // Reorder Favorites
    // ==============================
    
    public function reorderFavorites(Request $request)
    {
        foreach ($request->orders as $order) {
            FavoriteCurrency::where('currency_code', $order['code'])
                ->update(['sort_order' => $order['position']]);
        }
        
        return response()->json(['success' => true]);
    }

    // ==============================
    // Current USD -> INR Rate
    // ==============================

    public function rate()
    {
        $data = $this->getRates('USD');
        $rate = $data['rates']['INR'] ?? 0;
        
        return view('rate', compact('rate'));
    }
    
    // ==============================
    // Multi-Currency Comparison
    // ==============================
    
    public function compare(Request $request)
    {
        $base = $request->base ?? 'USD';
        $currencies = $request->currencies ?? ['EUR', 'GBP', 'INR', 'JPY', 'CAD'];
        
        $data = $this->getRates($base);
        $rates = $data['rates'] ?? [];
        
        $comparison = [];
        foreach ($currencies as $currency) {
            if (isset($rates[$currency])) {
                $comparison[$currency] = $rates[$currency];
            }
        }
        
        $allCurrencies = array_keys($rates);
        
        return view('compare', compact('comparison', 'base', 'allCurrencies', 'currencies'));
    }
    
    // ==============================
    // Create Rate Alert
    // ==============================
    
    public function createAlert(Request $request)
    {
        $request->validate([
            'from_currency' => 'required|string|size:3',
            'to_currency' => 'required|string|size:3',
            'target_rate' => 'required|numeric|min:0',
            'email' => 'nullable|email'
        ]);
        
        RateAlert::create([
            'from_currency' => strtoupper($request->from_currency),
            'to_currency' => strtoupper($request->to_currency),
            'target_rate' => $request->target_rate,
            'email' => $request->email,
            'is_triggered' => false
        ]);
        
        return redirect()->back()->with('alert_success', 'Rate alert created! We\'ll notify you when the target rate is reached.');
    }
    
    // ==============================
    // Delete Rate Alert
    // ==============================
    
    public function deleteAlert($id)
    {
        RateAlert::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Alert deleted');
    }
    
    // ==============================
    // Get Active Alerts
    // ==============================
    
    public function getAlerts()
    {
        $alerts = RateAlert::where('is_triggered', false)->get();
        return response()->json($alerts);
    }
    
    // ==============================
    // Export Conversion History
    // ==============================
    
    public function exportHistory(Request $request)
    {
        $format = $request->format ?? 'csv';
        $histories = ConversionHistory::latest()->take(100)->get();
        
        if ($format === 'csv') {
            $filename = 'conversion_history_' . date('Y-m-d') . '.csv';
            $handle = fopen('php://temp', 'w');
            
            // Add headers
            fputcsv($handle, ['ID', 'From', 'To', 'Amount', 'Converted Amount', 'Date']);
            
            // Add data
            foreach ($histories as $history) {
                fputcsv($handle, [
                    $history->id,
                    $history->from_currency,
                    $history->to_currency,
                    $history->amount,
                    $history->converted_amount,
                    $history->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            rewind($handle);
            $csvContent = stream_get_contents($handle);
            fclose($handle);
            
            return response($csvContent)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', "attachment; filename={$filename}");
        }
        
        return redirect()->back()->with('error', 'Export format not supported');
    }
    
    // ==============================
    // Clear All History
    // ==============================
    
    public function clearHistory()
    {
        ConversionHistory::truncate();
        return redirect()->back()->with('success', 'Conversion history cleared!');
    }
    
    // ==============================
    // Get Live Rate (AJAX)
    // ==============================
    
    public function liveRate(Request $request)
    {
        $from = $request->from ?? 'USD';
        $to = $request->to ?? 'INR';
        
        $data = $this->getRates($from);
        $rate = $data['rates'][$to] ?? 0;
        
        return response()->json([
            'from' => $from,
            'to' => $to,
            'rate' => $rate,
            'timestamp' => now()->toIso8601String()
        ]);
    }
    
    // ==============================
    // Get Multiple Live Rates (AJAX)
    // ==============================
    
    public function liveRatesMultiple(Request $request)
    {
        $from = $request->from ?? 'USD';
        $toCurrencies = $request->to ?? ['INR', 'EUR', 'GBP'];
        
        $data = $this->getRates($from);
        $rates = [];
        
        foreach ($toCurrencies as $currency) {
            $rates[$currency] = $data['rates'][$currency] ?? 0;
        }
        
        return response()->json([
            'from' => $from,
            'rates' => $rates,
            'timestamp' => now()->toIso8601String()
        ]);
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
        $data = $this->getRates($from);
        
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
        
        // Favorites for quick select
        $favorites = FavoriteCurrency::orderBy('sort_order')->pluck('currency_code')->toArray();
        
        return view('convert', compact(
            'result',
            'amount',
            'from',
            'to',
            'currencies',
            'histories',
            'favorites'
        ));
    }
    
    // ==============================
    // Dashboard with Overview
    // ==============================
    
    public function dashboard()
    {
        // Get rates for major currencies
        $data = $this->getRates('USD');
        $rates = $data['rates'] ?? [];
        
        $majorCurrencies = ['EUR', 'GBP', 'JPY', 'INR', 'CAD', 'AUD', 'CHF', 'CNY'];
        $exchangeRates = [];
        
        foreach ($majorCurrencies as $currency) {
            $exchangeRates[$currency] = $rates[$currency] ?? 0;
        }
        
        // Statistics
        $totalConversions = ConversionHistory::count();
        $todayConversions = ConversionHistory::whereDate('created_at', today())->count();
        $mostUsedFrom = ConversionHistory::select('from_currency')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('from_currency')
            ->orderBy('count', 'desc')
            ->first();
        $mostUsedTo = ConversionHistory::select('to_currency')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('to_currency')
            ->orderBy('count', 'desc')
            ->first();
        
        $recentHistory = ConversionHistory::latest()->take(5)->get();
        
        return view('dashboard', compact(
            'exchangeRates',
            'totalConversions',
            'todayConversions',
            'mostUsedFrom',
            'mostUsedTo',
            'recentHistory'
        ));
    }
}