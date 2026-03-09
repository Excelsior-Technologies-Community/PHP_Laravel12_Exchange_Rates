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