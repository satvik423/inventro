<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>Vertical Navbar</title> --}}
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/productPage.css') }}">
</head>
<body>

    <div class="sidebar">
        <h2>Dashboard</h2>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Products</a></li>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <li><button type="submit" class="btn btn-admin">Logout</button></li>
            </form>
        </ul>
    </div>

    <div class="content">
      {{$slot}}
    </div>

</body>
</html>
