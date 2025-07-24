<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin Masjid Al-Ikhlas</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="icon" type="image/x-icon" href="/logo.png">
</head>
<body class="login-page">

  <div class="login-container">
    <h2>Login Admin Masjid</h2>

    @if(session('error'))
      <div class="alert-error">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="form-group">
        <label for="username">Username</label>
        <input type="username" name="username" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" required>
      </div>

      <button type="submit" class="btn-login">Login</button>
    </form>
  </div>

</body>
</html>
