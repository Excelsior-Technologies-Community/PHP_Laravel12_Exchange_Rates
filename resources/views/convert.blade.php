<!DOCTYPE html>
<html>

<head>

    <title>Advanced Currency Converter</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Segoe UI',sans-serif;
        }

        body{
            background:linear-gradient(135deg,#141e30,#243b55);
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:30px;
        }

        .container{
            width:100%;
            max-width:850px;
            background:white;
            border-radius:20px;
            padding:35px;
            box-shadow:0 15px 40px rgba(0,0,0,0.3);
        }

        .heading{
            text-align:center;
            margin-bottom:30px;
        }

        .heading h1{
            color:#243b55;
            font-size:34px;
        }

        .heading p{
            color:#777;
            margin-top:8px;
        }

        .nav{
            text-align:center;
            margin-bottom:30px;
        }

        .nav a{
            text-decoration:none;
            background:#243b55;
            color:white;
            padding:10px 18px;
            border-radius:8px;
            margin:5px;
            display:inline-block;
            transition:0.3s;
        }

        .nav a:hover{
            background:#141e30;
        }

        form{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:20px;
        }

        .full{
            grid-column:1 / 3;
        }

        label{
            font-weight:600;
            display:block;
            margin-bottom:8px;
            color:#333;
        }

        input,select{
            width:100%;
            padding:14px;
            border:1px solid #ddd;
            border-radius:10px;
            font-size:15px;
            outline:none;
        }

        input:focus,
        select:focus{
            border-color:#243b55;
        }

        button{
            width:100%;
            padding:15px;
            border:none;
            background:#243b55;
            color:white;
            border-radius:10px;
            font-size:16px;
            cursor:pointer;
            transition:0.3s;
        }

        button:hover{
            background:#141e30;
        }

        .result-box{
            margin-top:35px;
            background:#f5f7fb;
            padding:25px;
            border-radius:15px;
            text-align:center;
        }

        .result-title{
            color:#666;
            margin-bottom:10px;
        }

        .result{
            font-size:35px;
            color:#28a745;
            font-weight:bold;
        }

        .history{
            margin-top:40px;
        }

        .history h2{
            margin-bottom:20px;
            color:#243b55;
        }

        table{
            width:100%;
            border-collapse:collapse;
            overflow:hidden;
            border-radius:12px;
        }

        table thead{
            background:#243b55;
            color:white;
        }

        table th,
        table td{
            padding:15px;
            text-align:center;
            border-bottom:1px solid #eee;
        }

        table tbody tr:hover{
            background:#f9f9f9;
        }

        .badge{
            padding:5px 10px;
            border-radius:20px;
            background:#e8f5e9;
            color:#28a745;
            font-size:13px;
            font-weight:600;
        }

        @media(max-width:768px){

            form{
                grid-template-columns:1fr;
            }

            .full{
                grid-column:auto;
            }

            table{
                font-size:13px;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <div class="heading">

        <h1>💱 Currency Exchange Dashboard</h1>

        <p>Real-time currency conversion with history tracking</p>

    </div>



    <div class="nav">

        <a href="/currencies">Currencies</a>

        <a href="/rate">USD → INR Rate</a>

        <a href="/convert">Converter</a>

    </div>



    <form method="POST" action="/convert">

        @csrf

        <div class="full">

            <label>Enter Amount</label>

            <input
                type="number"
                step="0.01"
                name="amount"
                required
                value="{{ $amount ?? '' }}"
                placeholder="Enter amount">

        </div>



        <div>

            <label>From Currency</label>

            <select name="from">

                @foreach($currencies as $currency)

                    <option value="{{ $currency }}"
                        {{ ($from ?? '') == $currency ? 'selected' : '' }}>

                        {{ $currency }}

                    </option>

                @endforeach

            </select>

        </div>



        <div>

            <label>To Currency</label>

            <select name="to">

                @foreach($currencies as $currency)

                    <option value="{{ $currency }}"
                        {{ ($to ?? '') == $currency ? 'selected' : '' }}>

                        {{ $currency }}

                    </option>

                @endforeach

            </select>

        </div>



        <div class="full">

            <button type="submit">

                Convert Currency

            </button>

        </div>

    </form>



    @isset($result)

    <div class="result-box">

        <div class="result-title">

            Converted Amount

        </div>

        <div class="result">

            {{ number_format($amount,2) }}

            {{ $from }}

            =

            {{ number_format($result,2) }}

            {{ $to }}

        </div>

    </div>

    @endisset




    <div class="history">

        <h2>📜 Recent Conversion History</h2>

        <table>

            <thead>

                <tr>

                    <th>From</th>

                    <th>To</th>

                    <th>Amount</th>

                    <th>Converted</th>

                    <th>Status</th>

                    <th>Date</th>

                </tr>

            </thead>

            <tbody>

                @forelse($histories as $history)

                <tr>

                    <td>{{ $history->from_currency }}</td>

                    <td>{{ $history->to_currency }}</td>

                    <td>{{ number_format($history->amount,2) }}</td>

                    <td>{{ number_format($history->converted_amount,2) }}</td>

                    <td>

                        <span class="badge">

                            Success

                        </span>

                    </td>

                    <td>

                        {{ $history->created_at->format('d M Y') }}

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6">

                        No conversion history found

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</body>
</html>