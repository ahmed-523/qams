<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QAMS - @yield('title', 'Quiz & Assignment Management System')</title>
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Modern Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            background-color: #f4f7f6; 
            font-family: 'Inter', sans-serif;
            color: #334155;
        }
        
        /* Modern Sidebar */
        .sidebar { 
            min-height: 100vh; 
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); 
            color: #fff; 
            width: 260px; 
            position: fixed; 
            top: 0; 
            left: 0; 
            padding: 20px 15px; 
            z-index: 100;
            box-shadow: 4px 0 15px rgba(0,0,0,0.05);
        }
        .sidebar .sidebar-brand { 
            font-size: 1.5rem; 
            font-weight: 700; 
            padding: 10px 15px 25px; 
            color: #fff; 
            border-bottom: 1px solid rgba(255, 255, 255, 0.1); 
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }
        .sidebar a { 
            color: #94a3b8; 
            text-decoration: none; 
            display: block; 
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .sidebar a:hover, .sidebar a.active { 
            background: rgba(255, 255, 255, 0.1); 
            color: #fff; 
            transform: translateX(5px);
        }
        
        /* Main Content & Top Bar */
        .main-content { 
            margin-left: 260px; 
            padding: 30px; 
        }
        .top-bar { 
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(10px);
            padding: 15px 25px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 30px; 
            border-radius: 12px; 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
        }
        .top-bar strong {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
        }
        
        /* Cards & Badges */
        .card { 
            border: none; 
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -2px rgba(0,0,0,0.025); 
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
        }
        .badge-late { background: #ef4444; }
        
        /* Logout Button Customization */
        .logout-btn {
            color: #f87171 !important;
            border-radius: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            padding: 12px 20px;
        }
        .logout-btn:hover {
            background: rgba(248, 113, 113, 0.1);
            color: #ef4444 !important;
        }

        /* Alerts Styling */
        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        /* ── Custom Tooltip ── */
        .tooltip .tooltip-inner {
            background-color: #1a1a2e;
            color: aliceblue;
            font-family: 'Inter', sans-serif;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 7px 14px;
            border-radius: 20px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.4);
            letter-spacing: 0.4px;
        }
        .tooltip.bs-tooltip-top .tooltip-arrow::before    { border-top-color:    #1a1a2e; }
        .tooltip.bs-tooltip-bottom .tooltip-arrow::before { border-bottom-color: #1a1a2e; }
        .tooltip.bs-tooltip-start .tooltip-arrow::before  { border-left-color:   #1a1a2e; }
        .tooltip.bs-tooltip-end .tooltip-arrow::before    { border-right-color:  #1a1a2e; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-mortarboard-fill me-2 text-primary"></i>QAMS
        </div>
        
        @yield('sidebar')
        
        <hr style="border-color: rgba(255, 255, 255, 0.1); margin: 20px 0;">
        
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-link logout-btn w-100 text-start">
                <i class="bi bi-box-arrow-left me-2"></i>Logout
            </button>
        </form>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <strong>@yield('page-title')</strong>
            
            <div class="d-flex align-items-center gap-4">
                <a href="{{ route('password.change') }}"
                    class="btn btn-sm btn-light border"
                    data-bs-toggle="tooltip" title="Change Password"
                   style="border-radius: 8px; font-weight: 500;">
                    <i class="bi bi-lock me-1"></i>Password
                </a>
                
                <div class="d-flex align-items-center bg-light rounded-pill px-3 py-1 border">
                    @if(auth()->user()->student && auth()->user()->student->picture)
    <img src="{{ asset('storage/' . auth()->user()->student->picture) }}" 
         class="rounded-circle me-2" 
         style="width:32px; height:32px; object-fit:cover;">
@else
    <i class="bi bi-person-circle text-primary fs-5 me-2"></i>
@endif
                    <span class="fw-semibold me-2">{{ auth()->user()->name }}</span>
                    <span class="badge bg-primary rounded-pill">{{ ucfirst(auth()->user()->role) }}</span>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger d-flex align-items-start">
                <i class="bi bi-x-circle-fill me-2 fs-5 mt-1"></i>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover',
                delay: { show: 200, hide: 100 }
            });
        });
    });
    </script>
    @yield('scripts')
</body>
</html>