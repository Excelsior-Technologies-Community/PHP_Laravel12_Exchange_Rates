<!DOCTYPE html>
<html>
<head>
    <title>Advanced Currency Converter</title>
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
            background: linear-gradient(135deg, #141e30, #243b55);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        .container {
            width: 100%;
            max-width: 900px;
            background: white;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }

        .heading {
            text-align: center;
            margin-bottom: 30px;
        }

        .heading h1 {
            color: #243b55;
            font-size: 34px;
        }

        .heading p {
            color: #777;
            margin-top: 8px;
        }

        .nav {
            text-align: center;
            margin-bottom: 30px;
        }

        .nav a {
            text-decoration: none;
            background: #243b55;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            margin: 5px;
            display: inline-block;
            transition: 0.3s;
        }

        .nav a:hover {
            background: #141e30;
        }

        /* Favorites Section */
        .favorites {
            background: #f0f4ff;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 25px;
        }

        .favorites h4 {
            margin-bottom: 10px;
            color: #243b55;
        }

        .fav-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .fav-btn {
            background: white;
            border: 1px solid #243b55;
            padding: 6px 15px;
            border-radius: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .fav-btn:hover {
            background: #243b55;
            color: white;
        }

        /* Live Rate Badge */
        .live-rate {
            background: #e8f5e9;
            padding: 10px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }

        .live-rate span {
            font-weight: bold;
            color: #28a745;
        }

        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .full {
            grid-column: 1 / 3;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input, select {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
        }

        input:focus, select:focus {
            border-color: #243b55;
        }

        button {
            width: 100%;
            padding: 15px;
            border: none;
            background: #243b55;
            color: white;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #141e30;
        }

        .swap-btn {
            background: #ff9800;
            margin-top: 30px;
        }

        .swap-btn:hover {
            background: #f57c00;
        }

        .result-box {
            margin-top: 35px;
            background: #f5f7fb;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
        }

        .result {
            font-size: 35px;
            color: #28a745;
            font-weight: bold;
        }

        .history {
            margin-top: 40px;
        }

        .history h2 {
            margin-bottom: 20px;
            color: #243b55;
        }

        .export-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            justify-content: flex-end;
        }

        .export-btn {
            background: #4caf50;
            padding: 8px 15px;
            font-size: 12px;
            width: auto;
        }

        .clear-btn {
            background: #f44336;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 12px;
        }

        table thead {
            background: #243b55;
            color: white;
        }

        table th, table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        table tbody tr:hover {
            background: #f9f9f9;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            background: #e8f5e9;
            color: #28a745;
            font-size: 13px;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            form {
                grid-template-columns: 1fr;
            }
            .full {
                grid-column: auto;
            }
            table {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="heading">
        <h1> Currency Exchange Dashboard</h1>
        <p>Real-time currency conversion with history tracking</p>
    </div>

    <div class="nav">
        <a href="{{ url('/dashboard') }}">Dashboard</a>
        <a href="{{ url('/currencies') }}">Currencies</a>
        <a href="{{ url('/rate') }}">USD → INR Rate</a>
        <a href="{{ url('/convert') }}">Converter</a>
        <a href="{{ url('/compare') }}">Compare</a>
    </div>

    <!-- Favorites Section -->
    @if(!empty($favorites))
    <div class="favorites">
        <h4> Quick Select Favorites</h4>
        <div class="fav-buttons">
            @foreach($favorites as $fav)
                <button type="button" class="fav-btn" onclick="setCurrency('{{ $fav }}')">{{ $fav }}</button>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Live Rate Display -->
    <div class="live-rate" id="liveRateDisplay">
        Loading live rate...
    </div>

    <form method="POST" action="{{ url('/convert') }}" id="convertForm">
        @csrf
        <div class="full">
            <label>Enter Amount</label>
            <input type="number" step="0.01" name="amount" required value="{{ $amount ?? '' }}" placeholder="Enter amount" id="amount">
        </div>

        <div>
            <label>From Currency</label>
            <select name="from" id="fromCurrency">
                @foreach($currencies as $currency)
                    <option value="{{ $currency }}" {{ ($from ?? 'USD') == $currency ? 'selected' : '' }}>
                        {{ $currency }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>To Currency</label>
            <select name="to" id="toCurrency">
                @foreach($currencies as $currency)
                    <option value="{{ $currency }}" {{ ($to ?? 'INR') == $currency ? 'selected' : '' }}>
                        {{ $currency }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="full">
            <button type="submit">Convert Currency</button>
        </div>
    </form>

    <button class="swap-btn" onclick="swapCurrencies()"> Swap Currencies</button>

    @isset($result)
    <div class="result-box">
        <div class="result">
            {{ number_format($amount,2) }} {{ $from }} = {{ number_format($result,2) }} {{ $to }}
        </div>
    </div>
    @endisset

    <div class="history">
        <h2> Recent Conversion History</h2>
        <div class="export-buttons">
            <a href="{{ url('/export/history?format=csv') }}" class="export-btn" style="text-decoration: none; color: white;">📥 Export CSV</a>
            <button onclick="clearHistory()" class="export-btn clear-btn"> Clear History</button>
        </div>
        <table>
            <thead>
                <tr><th>From</th><th>To</th><th>Amount</th><th>Converted</th><th>Status</th><th>Date</th></tr>
            </thead>
            <tbody>
                @forelse($histories as $history)
                <tr>
                    <td>{{ $history->from_currency }}</td>
                    <td>{{ $history->to_currency }}</td>
                    <td>{{ number_format($history->amount,2) }}</td>
                    <td>{{ number_format($history->converted_amount,2) }}</td>
                    <td><span class="badge">Success</span></td>
                    <td>{{ $history->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="6">No conversion history found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    // Live rate update every 30 seconds
    function updateLiveRate() {
        const from = document.getElementById('fromCurrency').value;
        const to = document.getElementById('toCurrency').value;
        
        fetch(`{{ url('/api/live-rate') }}?from=${from}&to=${to}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('liveRateDisplay').innerHTML = 
                    ` Live Rate: 1 ${data.from} = <span>${data.rate.toFixed(6)}</span> ${data.to}`;
            })
            .catch(error => {
                document.getElementById('liveRateDisplay').innerHTML = ' Unable to fetch live rate';
            });
    }
    
    // Set favorite currency in both selects or just target
    function setCurrency(currency) {
        const toSelect = document.getElementById('toCurrency');
        toSelect.value = currency;
        updateLiveRate();
    }
    
    // Swap currencies
    function swapCurrencies() {
        const from = document.getElementById('fromCurrency');
        const to = document.getElementById('toCurrency');
        const temp = from.value;
        from.value = to.value;
        to.value = temp;
        updateLiveRate();
        document.getElementById('convertForm').submit();
    }
    
    // Clear history
    function clearHistory() {
        if(confirm('Are you sure you want to clear all conversion history?')) {
            window.location.href = '{{ url("/clear-history") }}';
        }
    }
    
    // Update live rate when currencies change
    document.getElementById('fromCurrency').addEventListener('change', updateLiveRate);
    document.getElementById('toCurrency').addEventListener('change', updateLiveRate);
    
    // Initial load
    updateLiveRate();
    
    // Auto refresh every 30 seconds
    setInterval(updateLiveRate, 30000);
</script>
</body>
</html>