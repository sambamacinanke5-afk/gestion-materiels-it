<nav class="navbar">
    <i class='bx bx-menu'></i>
    <a href="#" class="nav-link">Gestion-IT</a>

    <form class="search-form">
        <input type="search" placeholder="Rechercher...">
        <button type="submit"><i class='bx bx-search'></i></button>
    </form>

    <input type="checkbox" id="switch-mode" hidden>
    <label for="switch-mode" class="switch-mode"></label>

    {{-- 🔔 Notifications --}}
    <div class="notification-wrapper">
        <a href="#" id="notificationToggle" class="notification-icon">
            <i class='bx bxs-bell'></i>
            @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
            @if ($unreadCount)
                <span class="badge" id="notificationBadge">{{ $unreadCount }}</span>
            @endif
        </a>

        <div class="notification-menu" id="notificationMenu">
            <button id="readAllBtn">Marquer toutes comme lues</button>
            <ul>
                @forelse(auth()->user()->unreadNotifications as $notification)
                    <li>
                        <a href="{{ route('bondelivraison.show', [
                                'id' => $notification->data['bon_id'],
                                'notification' => $notification->id
                            ]) }}"
                            data-id="{{ $notification->id }}"
                            class="notification-link">
                            <strong>{{ $notification->data['titre'] ?? 'Notification' }}</strong><br>
                            <small>{{ $notification->data['message'] }}</small>
                        </a>
                    </li>
                @empty
                    <li class="empty">Aucune notification</li>
                @endforelse
            </ul>
        </div>
    </div>

    <a href="#" class="profile">
        <img src="{{ asset('assets/img/people.png') }}" alt="Profil">
    </a>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('notificationToggle');
        const menu = document.getElementById('notificationMenu');
        const badge = document.getElementById('notificationBadge');

        // 🔔 Ouvrir / fermer le menu
        toggle.addEventListener('click', e => {
            e.preventDefault();
            menu.classList.toggle('show');
        });

        document.addEventListener('click', e => {
            if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.remove('show');
            }
        });

        // ✅ Marquer notification individuelle comme lue
        const markAsRead = id => fetch(`/user/notifications/read/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        }).then(res => res.json());

        document.querySelectorAll('.notification-link').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const id = link.dataset.id;
                const url = link.href;

                markAsRead(id).then(data => {
                    if (data.status === 'success') {
                        link.closest('li').remove();
                        if (badge) {
                            let count = parseInt(badge.innerText) - 1;
                            count <= 0 ? badge.remove() : badge.innerText = count;
                        }
                        window.location.href = url;
                    }
                });
            });
        });

        // ✅ Marquer toutes les notifications comme lues
        const readAllBtn = document.getElementById('readAllBtn');
        if (readAllBtn) {
            readAllBtn.addEventListener('click', () => {
                fetch(`/user/notifications/read-all`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        badge?.remove();
                        document.querySelector('#notificationMenu ul').innerHTML =
                            '<li class="empty">Aucune notification</li>';
                    }
                });
            });
        }
    });
    </script>
</nav>
