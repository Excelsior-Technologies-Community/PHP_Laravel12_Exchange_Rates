<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Currency Exchange</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 30px;
        }

        .container {
            max-width: 1300px;
            margin: 0 auto;
        }

        /* Navbar */
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
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .logo h2 {
            color: #667eea;
        }

        .nav-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .nav-links a {
            text-decoration: none;
            padding: 8px 18px;
            background: #667eea;
            color: white;
            border-radius: 8px;
            transition: 0.3s;
        }

        .nav-links a:hover {
            background: #764ba2;
            transform: translateY(-2px);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
        }

        .stat-label {
            color: #666;
            margin-top: 5px;
        }

        .stat-note {
            font-size: 12px;
            color: #999;
            margin-top: 10px;
        }

        /* Exchange Rates Section */
        .section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .section h3 {
            margin-bottom: 20px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .rates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .rate-item {
            background: #f8f9ff;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            transition: 0.3s;
        }

        .rate-item:hover {
            background: #e8ebff;
            transform: scale(1.02);
        }

        .rate-currency {
            font-weight: bold;
            font-size: 18px;
            color: #667eea;
        }

        .rate-value {
            font-size: 20px;
            font-weight: bold;
            margin-top: 8px;
        }

        .rate-base {
            font-size: 11px;
            color: #999;
            margin-top: 5px;
        }

        /* Recent History Table */
        .history-table {
            width: 100%;
            border-collapse: collapse;
        }

        .history-table th,
        .history-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .history-table th {
            background: #667eea;
            color: white;
        }

        .history-table tr:hover {
            background: #f8f9ff;
        }

        .badge {
            background: #e8f5e9;
            color: #4caf50;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .navbar {
                flex-direction: column;
                text-align: center;
            }
            
            .history-table {
                font-size: 12px;
            }
            
            .history-table th,
            .history-table td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Navbar -->
        <div class="navbar">
            <div class="logo">
                <h2> Currency Exchange</h2>
            </div>
            <div class="nav-links">
                <a href="{{ url('/dashboard') }}">Dashboard</a>
                <a href="{{ url('/currencies') }}">Currencies</a>
                <a href="{{ url('/rate') }}">USD/INR</a>
                <a href="{{ url('/convert') }}">Converter</a>
                <a href="{{ url('/compare') }}">Compare</a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"></div>
                <div class="stat-value">{{ number_format($totalConversions) }}</div>
                <div class="stat-label">Total Conversions</div>
                <div class="stat-note">All time</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"></div>
                <div class="stat-value">{{ number_format($todayConversions) }}</div>
                <div class="stat-label">Today's Conversions</div>
                <div class="stat-note">{{ now()->format('d M Y') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"></div>
                <div class="stat-value">{{ $mostUsedFrom->from_currency ?? 'N/A' }}</div>
                <div class="stat-label">Most Converted From</div>
                <div class="stat-note">{{ $mostUsedFrom->count ?? 0 }} conversions</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"></div>
                <div class="stat-value">{{ $mostUsedTo->to_currency ?? 'N/A' }}</div>
                <div class="stat-label">Most Converted To</div>
                <div class="stat-note">{{ $mostUsedTo->count ?? 0 }} conversions</div>
            </div>
        </div>

        <!-- Live Exchange Rates -->
        <div class="section">
            <h3> Live Exchange Rates <span style="font-size: 12px; color: #4caf50;">(1 USD = ?)</span></h3>
            <div class="rates-grid">
                @foreach($exchangeRates as $currency => $rate)
                <div class="rate-item">
                    <div class="rate-currency">{{ $currency }}</div>
                    <div class="rate-value">{{ number_format($rate, 4) }}</div>
                    <div class="rate-base">USD → {{ $currency }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Conversions -->
        <div class="section">
            <h3> Recent Conversions</h3>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>To</th>
                        <th>Amount</th>
                        <th>Converted</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentHistory as $history)
                    <tr>
                        <td><strong>{{ $history->from_currency }}</strong></td>
                        <td>{{ $history->to_currency }}</td>
                        <td>{{ number_format($history->amount, 2) }}</td>
                        <td>{{ number_format($history->converted_amount, 2) }}</td>
                        <td>{{ $history->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">No conversions yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>