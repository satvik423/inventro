<head>
    <title>Registeration</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">

</head>
<form action="{{ route('register') }}" method="POST">
    @csrf

    <h2>Create a new Login</h2>

    <label for="name">Name:</label>
    <input type="text" name="name" id="name" value="{{ old('name') }}" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="{{ old('email') }}" required>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>

    <label for="password_confirmation">Confirm Password:</label>
    <input type="password" name="password_confirmation" id="password_confirmation" required>

    <label for="role">Role</label>
    <select name="role" id="role">
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select>
    <button type="submit" class="btn btn-admin">Log in</button>
</form>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif