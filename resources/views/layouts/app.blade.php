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
        height: 100vh;   /* important */
        overflow: hidden;
    }

    .sidebar {
        width: 210px;
        background: #f3f4f7;
        border-right: 1px solid #eceff3;
        height: 100vh;
        overflow: hidden; /* pas de scroll sidebar */
    }

    .main-content {
        flex: 1;
        height: 100vh;
        overflow: hidden; /* pas de scroll contenu */
        padding: 10px 14px;
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
</body>
</html>