<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QAMS - Create Admin Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1e3a5f, #2d5491); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .setup-card { background: #fff; border-radius: 16px; padding: 40px; width: 100%; max-width: 450px; box-shadow: 0 20px 60px rgba(0,0,0,.3); }
        .setup-card .brand { text-align: center; margin-bottom: 30px; }
        .setup-card .brand h1 { color: #1e3a5f; font-size: 2rem; font-weight: bold; }
    </style>
</head>
<body>
    <div class="setup-card">
        <div class="brand">
            <h1>📚 QAMS</h1>
            <p class="text-muted">Create Your Admin Account</p>
        </div>

        <div class="alert alert-info py-2"><small><strong>First-time setup:</strong> This page is only available once. After creating the admin account, all future users are registered from the admin panel.</small></div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('setup.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Full Name</label>
                <input type="text" name="name" class="form-control form-control-lg" value="{{ old('name') }}" required placeholder="e.g. Dr. Ahmad Khan">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" name="username" class="form-control form-control-lg" value="{{ old('username') }}" required placeholder="e.g. admin">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control form-control-lg" required placeholder="Min. 6 characters">
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control form-control-lg" required placeholder="Repeat password">
            </div>
            <button type="submit" class="btn btn-primary btn-lg w-100">Create Admin Account</button>
        </form>
    </div>
</body>
</html>
