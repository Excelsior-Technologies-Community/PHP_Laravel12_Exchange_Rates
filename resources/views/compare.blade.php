<!DOCTYPE html>
<html>
<head>
    <title>Compare Currencies</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            min-height: 100vh;
            padding: 30px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .navbar {
            background: white;
            border-radius: 15px;
            padding: 15px 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .logo h2 {
            color: #1a1a2e;
        }

        .nav-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .nav-links a {
            text-decoration: none;
            padding: 8px 18px;
            background: #1a1a2e;
            color: white;
            border-radius: 8px;
            transition: 0.3s;
        }

        .nav-links a:hover {
            background: #16213e;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .card h2 {
            margin-bottom: 20px;
            color: #1a1a2e;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        select, input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        .currency-selector {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .currency-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 8px;
        }

        .currency-checkbox input {
            width: auto;
        }

        button {
            background: #1a1a2e;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        button:hover {
            background: #16213e;
            transform: translateY(-2px);
        }

        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .comparison-table th,
        .comparison-table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        .comparison-table th {
            background: #1a1a2e;
            color: white;
        }

        .comparison-table tr:hover {
            background: #f8f9ff;
        }

        .rate-highlight {
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
        }

        .update-time {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .currency-selector {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="navbar">
            <div class="logo">
                <h2> Currency Comparison</h2>
            </div>
            <div class="nav-links">
                <a href="{{ url('/dashboard') }}">Dashboard</a>
                <a href="{{ url('/currencies') }}">Currencies</a>
                <a href="{{ url('/rate') }}">USD/INR</a>
                <a href="{{ url('/convert') }}">Converter</a>
                <a href="{{ url('/compare') }}">Compare</a>
            </div>
        </div>

        <div class="card">
            <h2> Compare Exchange Rates</h2>
            <form method="GET" action="{{ url('/compare') }}" id="compareForm">
                <div class="form-group">
                    <label>Base Currency</label>
                    <select name="base" id="baseCurrency">
                        @foreach($allCurrencies as $currency)
                            <option value="{{ $currency }}" {{ $base == $currency ? 'selected' : '' }}>
                                {{ $currency }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Select Currencies to Compare</label>
                    <div class="currency-selector" id="currencySelector">
                        @foreach(array_slice($allCurrencies, 0, 30) as $currency)
                            <label class="currency-checkbox">
                                <input type="checkbox" name="currencies[]" value="{{ $currency }}"
                                    {{ in_array($currency, $currencies) ? 'checked' : '' }}>
                                <span>{{ $currency }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit">Compare Rates</button>
            </form>
        </div>

        @if(count($comparison) > 0)
        <div class="card">
            <h2> Comparison Results</h2>
            <p style="margin-bottom: 15px;">Base Currency: <strong>{{ $base }}</strong> = 1</p>
            <table class="comparison-table">
                <thead>
                    <tr>
                        <th>Currency</th>
                        <th>Exchange Rate</th>
                        <th>Convert 100 {{ $base }}</th>
                        <th>Convert 1000 {{ $base }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comparison as $currency => $rate)
                    <tr>
                        <td><strong>{{ $currency }}</strong></td>
                        <td class="rate-highlight">{{ number_format($rate, 6) }}</td>
                        <td>{{ number_format(100 * $rate, 2) }} {{ $currency }}</td>
                        <td>{{ number_format(1000 * $rate, 2) }} {{ $currency }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="update-time">
                 Rates updated: {{ now()->format('d M Y, H:i:s') }}
            </div>
        </div>
        @endif
    </div>

    <script>
        // Auto-submit on base currency change
        document.getElementById('baseCurrency').addEventListener('change', function() {
            document.getElementById('compareForm').submit();
        });
    </script>
</body>
</html>