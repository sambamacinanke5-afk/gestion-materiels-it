@php
    use App\Models\Menu;
    use Illuminate\Support\Facades\Route;

    $user = auth()->user();

    $allMenus = Menu::query()
        ->whereNull('parent_id')
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $menus = $allMenus->filter(function ($menu) use ($user) {
        return $user && filled($menu->permission_name) && $user->can($menu->permission_name);
    });
@endphp

<aside class="sidebar">
    <style>
        .sidebar {
            width: 210px;
            background: #f8fafc;
            padding: 10px;
            border-right: 1px solid #e5e7eb;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-logo {
            text-align: center;
            margin-bottom: 14px;
        }

        .sidebar-logo img {
            max-width: 140px;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 20px;
            text-decoration: none;
            color: #4b5563;
            font-size: 13px;
            font-weight: 500;
            transition: 0.2s;
        }

        .menu-item:hover {
            background: #eef2ff;
            color: #2563eb;
            transform: translateX(2px);
        }

        .menu-item.active {
            background: #e6edff;
            color: #2563eb;
            font-weight: 600;
        }

        .menu-icon {
            font-size: 14px;
            width: 18px;
            text-align: center;
        }

        .logout {
            margin-top: 10px;
            color: #ef4444;
        }

        .logout:hover {
            background: #fee2e2;
            color: #dc2626;
        }

        .menu-item i {
            font-size: 14px;
        }
    </style>

    <div class="sidebar-logo">
        <img src="{{ asset('assets/img/bms.jpg') }}" alt="BMS">
    </div>

    <nav class="sidebar-menu">
        @foreach ($menus as $menu)
            @php
                $menuUrl = '#';

                if ($menu->route && Route::has($menu->route)) {
                    $menuUrl = route($menu->route);
                }
            @endphp

            <a href="{{ $menuUrl }}"
               class="menu-item {{ $menu->route && request()->routeIs($menu->route, str_replace('.index', '.*', $menu->route)) ? 'active' : '' }}">
                <span class="menu-icon"><i class="{{ $menu->icon }}"></i></span>
                {{ $menu->title }}
            </a>
        @endforeach

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="menu-item logout" style="border:none;background:none;width:100%;text-align:left;">
                <span class="menu-icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                Déconnexion
            </button>
        </form>
    </nav>
</aside>
