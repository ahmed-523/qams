<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QAMS - @yield('title', 'Quiz & Assignment Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #1e3a5f; color: #fff; width: 240px; position: fixed; top: 0; left: 0; padding-top: 20px; z-index: 100; }
        .sidebar a { color: #cce0ff; text-decoration: none; display: block; padding: 10px 20px; }
        .sidebar a:hover, .sidebar a.active { background: #2d5491; color: #fff; }
        .sidebar .sidebar-brand { font-size: 1.3rem; font-weight: bold; padding: 10px 20px 20px; color: #fff; border-bottom: 1px solid #2d5491; }
        .main-content { margin-left: 240px; padding: 20px; }
        .top-bar { background: #fff; padding: 12px 20px; border-bottom: 1px solid #dee2e6; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-radius: 8px; }
        .badge-late { background: #dc3545; }
        .card { border: none; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand"><i class="bi bi-mortarboard-fill me-2"></i>QAMS</div>
        @yield('sidebar')
        <hr style="border-color:#2d5491;">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-link text-danger w-100 text-start ps-3"><i class="bi bi-box-arrow-left me-2"></i>Logout</button>
        </form>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <strong>@yield('page-title')</strong>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('password.change') }}"
                   class="btn btn-sm btn-outline-secondary"
                   data-bs-toggle="tooltip" title="Change Password">
                    <i class="bi bi-lock me-1"></i>Change Password
                </a>
                <span class="text-muted">
                    <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                    <span class="badge bg-primary">{{ ucfirst(auth()->user()->role) }}</span>
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-x-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
            new bootstrap.Tooltip(el);
        });
    });
    </script>
    @yield('scripts')
</body>
</html>