<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h1>Login</h1>
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div>
        <label for="phone">Phone</label>
        <input id="phone" type="text" name="phone" required>
    </div>
    <div>
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>
    </div>
    <div>
        <button type="submit">Login</button>
    </div>
</form>
</body>
</html>
