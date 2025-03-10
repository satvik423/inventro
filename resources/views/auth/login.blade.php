<head>
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<form action="{{ route('login') }}" method="POST">
    @csrf

    <h2>Log In to Your Account</h2>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="{{ old('email') }}" required>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>
    <button type="submit" class="btn">Log in</button>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</form>