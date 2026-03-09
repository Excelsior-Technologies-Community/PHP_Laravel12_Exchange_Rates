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