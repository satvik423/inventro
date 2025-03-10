<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        /* Global Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

/* Page Styling */
body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: #f4f4f4;
}

/* Welcome Container */
.welcome-container {
    text-align: center;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 90%;
}

h1 {
    font-size: 26px;
    margin-bottom: 10px;
    color: #333;
}

p {
    font-size: 16px;
    color: #666;
    margin-bottom: 20px;
}

/* Buttons */
.button-container {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.btn {
    text-decoration: none;
    padding: 12px 20px;
    font-size: 16px;
    border-radius: 5px;
    transition: 0.3s ease;
    font-weight: bold;
}

/* Admin Button */
.btn-admin {
    background: #007bff;
    color: white;
}

.btn-admin:hover {
    background: #0056b3;
}

/* User Button */
.btn-user {
    background: #28a745;
    color: white;
}

.btn-user:hover {
    background: #1e7e34;
}

/* Responsive */
@media (max-width: 500px) {
    .button-container {
        flex-direction: column;
    }
}

    </style>
</head>
<body>
    <div class="welcome-container">
        <h1>Welcome to the Inventro</h1>
        <p>A inventry management project</p>
        <div class="button-container">
            <a href="{{ route('show.login') }}" class="btn btn-admin">Login</a>
            <a href="{{ route('show.register') }}" class="btn btn-user">Register</a>
        </div>
    </div>
</body>
</html>
