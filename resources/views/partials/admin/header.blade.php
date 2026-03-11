<nav>
    <i class='bx bx-menu'></i>

    <a href="#" class="nav-link">Gestion-IT</a>

    {{-- Recherche --}}
    <form action="#">
        <div class="form-input">
            <input type="search" placeholder="Search...">
            <button type="submit" class="search-btn">
                <i class='bx bx-search'></i>
            </button>
        </div>
    </form>

    {{-- Switch mode --}}
    <input type="checkbox" id="switch-mode" hidden>
    <label for="switch-mode" class="switch-mode"></label>

    {{-- 🔔 Notifications --}}
    @php
        $unread = auth()->user()->unreadNotifications;
    @endphp

    <div class="notification-wrapper">
        <a href="javascript:void(0)" class="notification" id="notificationToggle">
            <i class='bx bxs-bell'></i>
            @if ($unread->count() > 0)
                <span class="num">{{ $unread->count() }}</span>
            @endif
        </a>

        <div class="notification-menu" id="notificationMenu">
            <ul>
                <li>
                    <h3 style="color:black">Notifications</h3>
                </li>

                @forelse ($unread as $notification)
                    <li>
                        <a href="{{ route('repartition.index') }}"
                            onclick="event.preventDefault(); markAsRead('{{ $notification->id }}', this)"
                            style="text-decoration:none;color:#333;display:block;">
                            <small>{{ $notification->data['message'] }}</small>
                        </a>
                    </li>
                @empty
                    <li style="text-align:center;">Aucune notification</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Profil --}}
    <a href="#" class="profile">
        <i class="fa-solid fa-user"></i>
    </a>
</nav>
<script>
    document.getElementById('notificationToggle').addEventListener('click', function() {
        document.getElementById('notificationMenu').classList.toggle('show');
    });

    function markAsRead(notificationId, link) {
        fetch(`{{ route('admin.repartition.asRead', ':id') }}`.replace(':id', notificationId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(() => {
                window.location.href = link.href;
            });
    }
</script>
<style>
    .notification-menu {
        display: none;
        position: absolute;
        right: 0;
        background: #fff;
        width: 300px;
        max-height: 400px;
        overflow-y: auto;
        z-index: 1000;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .2);
    }

    .notification-menu.show {
        display: block;
    }
</style>
