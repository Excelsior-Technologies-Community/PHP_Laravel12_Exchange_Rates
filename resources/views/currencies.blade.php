<!DOCTYPE html>
<html>
<head>
    <title>Available Currencies</title>
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
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            padding: 30px;
        }

        .card {
            width: 100%;
            max-width: 800px;
            background: white;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }

        .heading {
            text-align: center;
            margin-bottom: 25px;
        }

        .heading h2 {
            font-size: 32px;
            color: #1e3c72;
        }

        .nav {
            text-align: center;
            margin-bottom: 25px;
        }

        .nav a {
            text-decoration: none;
            padding: 10px 18px;
            background: #2a5298;
            color: white;
            border-radius: 8px;
            margin: 5px;
            display: inline-block;
        }

        .favorites-section {
            background: #f0f4ff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .favorites-section h3 {
            margin-bottom: 15px;
            color: #1e3c72;
        }

        .fav-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .fav-item {
            background: white;
            padding: 8px 15px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .remove-fav {
            background: #f44336;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            cursor: pointer;
            font-size: 12px;
        }

        .search-box {
            margin-bottom: 20px;
        }

        .search-box input {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .currency-list {
            max-height: 400px;
            overflow-y: auto;
            border-radius: 12px;
            border: 1px solid #eee;
        }

        .currency-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .currency-item:hover {
            background: #f5f8ff;
        }

        .currency-code {
            font-weight: bold;
            color: #1e3c72;
        }

        .add-fav {
            background: #4caf50;
            border: none;
            padding: 5px 12px;
            border-radius: 15px;
            color: white;
            cursor: pointer;
        }

        .badge {
            background: #e8f0ff;
            color: #2a5298;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .alert {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="heading">
        <h2> Available Currencies</h2>
        <p>Browse and manage your favorite currencies</p>
    </div>

    <div class="nav">
        <a href="{{ url('/dashboard') }}">Dashboard</a>
        <a href="{{ url('/currencies') }}">Currencies</a>
        <a href="{{ url('/rate') }}">USD → INR Rate</a>
        <a href="{{ url('/convert') }}">Converter</a>
        <a href="{{ url('/compare') }}">Compare</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Favorites Section -->
    @if($favorites->count() > 0)
    <div class="favorites-section">
        <h3> Your Favorites</h3>
        <div class="fav-list">
            @foreach($favorites as $fav)
            <div class="fav-item">
                <span>{{ $fav->currency_code }}</span>
                <form method="POST" action="{{ url('/favorite/remove/' . $fav->currency_code) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="remove-fav" onclick="return confirm('Remove from favorites?')">×</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="search-box">
        <input type="text" id="search" placeholder=" Search Currency Code...">
    </div>

    <div class="currency-list">
        @forelse($currencies as $code => $currency)
            <div class="currency-item">
                <div class="currency-code">{{ $code }}</div>
                <div>
                    @if(!$favorites->contains('currency_code', $code))
                    <form method="POST" action="{{ url('/favorite/add') }}" style="display: inline;">
                        @csrf
                        <input type="hidden" name="currency_code" value="{{ $code }}">
                        <button type="submit" class="add-fav">+ Add Favorite</button>
                    </form>
                    @else
                    <span class="badge">Favorite</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty">No currencies found</div>
        @endforelse
    </div>
</div>

<script>
document.getElementById('search').addEventListener('keyup', function() {
    let value = this.value.toLowerCase();
    let items = document.querySelectorAll('.currency-item');
    items.forEach(item => {
        item.style.display = item.innerText.toLowerCase().includes(value) ? 'flex' : 'none';
    });
});
</script>
</body>
</html>