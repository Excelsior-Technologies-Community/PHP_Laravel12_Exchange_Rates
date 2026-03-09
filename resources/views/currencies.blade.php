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