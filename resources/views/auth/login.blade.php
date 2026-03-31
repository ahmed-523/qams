<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QAMS - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1e3a5f, #2d5491); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: #fff; border-radius: 16px; padding: 40px; width: 100%; max-width: 420px; box-shadow: 0 20px 60px rgba(0,0,0,.3); }
        .login-card .brand { text-align: center; margin-bottom: 30px; }
        .login-card .brand h1 { color: #1e3a5f; font-size: 2rem; font-weight: bold; }
        .login-card .brand p { color: #6c757d; font-size: .9rem; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">
            <h1>📚 QAMS</h1>
            <p>Quiz & Assignment Management System</p>
        </div>

        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" name="username" class="form-control form-control-lg" value="{{ old('username') }}" required autofocus placeholder="Enter your username">
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control form-control-lg" required placeholder="Enter your password">
            </div>
            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">Sign In</button>
        </form>

        @if(!\App\Models\User::where('role', 'admin')->exists())
        <div class="text-center">
            <a href="{{ route('setup') }}" class="btn btn-outline-secondary btn-sm">Create Admin Account</a>
        </div>
        @endif
    </div>
</body>
</html>