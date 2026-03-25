<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <title>{{ $pageTitle ?? 'ITMAT' }}</title>

<style>
    * {
        box-sizing: border-box;
    }

    html, body {
        height: 100%;
        margin: 0;
        overflow: hidden;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
        background: #f3f4f7;
        color: #334155;
    }

    .app-layout {
        display: flex;
        height: 100vh;
        overflow: hidden;
    }

    .sidebar {
        width: 210px;
        background: #f3f4f7;
        border-right: 1px solid #eceff3;
        height: 100vh;
        overflow: hidden;
    }

    .main-content {
        flex: 1;
        height: 100vh;
        padding: 10px 14px;
    }

    /* ===== TOAST ===== */

    .toast-clean {
        position: fixed;
        bottom: 20px;
        left: 20px;
        background: #111827;
        color: #fff;
        padding: 14px 18px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        z-index: 9999;

        opacity: 0;
        transform: translateY(20px);
        animation: slideIn 0.4s ease forwards;
    }

    .toast-clean i {
        color: #22c55e;
    }

    @keyframes slideIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .toast-hide {
        animation: fadeOut 0.4s ease forwards;
    }

    @keyframes fadeOut {
        to {
            opacity: 0;
            transform: translateY(20px);
        }
    }

    @media (max-width: 992px) {
        .app-layout {
            flex-direction: column;
            height: auto;
        }

        .sidebar,
        .main-content {
            width: 100%;
            height: auto;
        }
    }
</style>
</head>

<body>

<div class="app-layout">
    @include('partials.sidebar')

    <main class="main-content">
        @yield('content')
    </main>
</div>

{{-- ===== TOAST SUCCESS ===== --}}
@if(session('success'))
    <div id="toast-success" class="toast-clean">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

{{-- ===== SCRIPT ===== --}}
<script>
    setTimeout(() => {
        const toast = document.getElementById('toast-success');

        if (toast) {
            toast.classList.add('toast-hide');

            setTimeout(() => {
                toast.remove();
            }, 400);
        }
    }, 3000);
</script>

</body>
</html>