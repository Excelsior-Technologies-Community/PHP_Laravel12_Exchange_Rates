<!DOCTYPE html>
<html>

<head>

    <title>Available Currencies</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Segoe UI',sans-serif;
        }

        body{
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:linear-gradient(135deg,#1e3c72,#2a5298);
            padding:30px;
        }

        .card{
            width:100%;
            max-width:700px;
            background:white;
            border-radius:20px;
            padding:35px;
            box-shadow:0 15px 40px rgba(0,0,0,0.3);
        }

        .heading{
            text-align:center;
            margin-bottom:25px;
        }

        .heading h2{
            font-size:32px;
            color:#1e3c72;
            margin-bottom:10px;
        }

        .heading p{
            color:#777;
            font-size:15px;
        }

        .nav{
            text-align:center;
            margin-bottom:25px;
        }

        .nav a{
            text-decoration:none;
            padding:10px 18px;
            background:#2a5298;
            color:white;
            border-radius:8px;
            margin:5px;
            display:inline-block;
            transition:0.3s;
            font-size:14px;
        }

        .nav a:hover{
            background:#1e3c72;
        }

        .search-box{
            margin-bottom:20px;
        }

        .search-box input{
            width:100%;
            padding:14px;
            border:1px solid #ddd;
            border-radius:10px;
            font-size:15px;
            outline:none;
            transition:0.3s;
        }

        .search-box input:focus{
            border-color:#2a5298;
            box-shadow:0 0 10px rgba(42,82,152,0.2);
        }

        .currency-list{
            max-height:450px;
            overflow-y:auto;
            border-radius:12px;
            border:1px solid #eee;
        }

        .currency-item{
            padding:15px;
            border-bottom:1px solid #eee;
            font-size:15px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            transition:0.3s;
        }

        .currency-item:hover{
            background:#f5f8ff;
        }

        .currency-code{
            font-weight:bold;
            color:#1e3c72;
            font-size:16px;
        }

        .badge{
            background:#e8f0ff;
            color:#2a5298;
            padding:6px 12px;
            border-radius:20px;
            font-size:12px;
            font-weight:600;
        }

        .empty{
            text-align:center;
            padding:20px;
            color:#888;
        }

        @media(max-width:768px){

            .heading h2{
                font-size:25px;
            }

            .currency-item{
                flex-direction:column;
                align-items:flex-start;
                gap:10px;
            }

        }

    </style>

</head>

<body>

<div class="card">

    <div class="heading">

        <h2>🌍 Available Currencies</h2>

        <p>Browse and search all supported currencies</p>

    </div>



    <div class="nav">

        <a href="/currencies">Currencies</a>

        <a href="/rate">USD → INR Rate</a>

        <a href="/convert">Converter</a>

    </div>



    <!-- SEARCH BOX -->

    <div class="search-box">

        <input
            type="text"
            id="search"
            placeholder="🔍 Search Currency Code...">

    </div>



    <!-- CURRENCY LIST -->

    <div class="currency-list">

        @forelse($currencies as $code => $currency)

            <div class="currency-item">

                <div>

                    <div class="currency-code">

                        {{ $code }}

                    </div>

                </div>

                <span class="badge">

                    Supported

                </span>

            </div>

        @empty

            <div class="empty">

                No currencies found

            </div>

        @endforelse

    </div>

</div>



<!-- SEARCH SCRIPT -->

<script>

document.getElementById('search')
.addEventListener('keyup', function(){

    let value = this.value.toLowerCase();

    let items = document.querySelectorAll('.currency-item');

    items.forEach(item => {

        item.style.display =
        item.innerText.toLowerCase().includes(value)
        ? 'flex'
        : 'none';

    });

});

</script>

</body>

</html>