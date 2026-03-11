<nav>
    <i class='bx bx-menu'></i>
    <a href="#" class="nav-link">Gestion-IT</a>
    {{-- Recherche --}}
    <form action="#">
        <div class="form-input">
            <input type="search" placeholder="Search...">
            <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
        </div>
    </form>

    <input type="checkbox" id="switch-mode" hidden>
    <label for="switch-mode" class="switch-mode"></label>

    {{-- 🔔 Notifications --}}
    <div class="notification-wrapper">
        <a href="{{route('bondelivraison.index')}}" class="notification" id="notificationToggle">
            <i class='bx bxs-bell'></i>
            @php
                $unread = auth()->user()->unreadNotifications;
            @endphp
            @if($unread->count() > 0)
                <span class="num">{{ $unread->count() }}</span>
            @endif
        </a>
        <div class="notification-menu" id="notificationMenu">
            <ul>
                <li>
                    <h3 style="color: black">Notification</h3>
                    @forelse($unread as $notification)
                        @php
                            $url = "#";
                            if(isset($notification->id)) {
                                $url = route('user.bondelivraison.asRead', $notification->id);
                            } elseif(isset($notification->data['repartition_id'])) {
                                $url = route('user.bondelivraison.asRead', $notification->data['repartition_id']);
                            }
                        @endphp
                         <a href="{{ route('user.bondelivraison.index') }}"
                         onclick="event.preventDefault(); markAsRead('{{ $notification->id }}', this)"
                         style="text-decoration:none;color:#333;">
                          <small>{{ $notification->data['message'] ?? '' }}</small>
                      </a>
                        @empty
                        <li style="text-align: center; color: black;">Aucune notification</li>
                        @endforelse
                    </li>
            </ul>
        </div>
    </div>

    {{-- Profil --}}
    <a href="#" class="profile">
        <i class="fa-solid fa-user"></i>
    </a>
</nav>
<script>
    const toggle = document.getElementById('notificationToggle');
    const menu = document.getElementById('notificationMenu');

    // Ouvrir / fermer menu
    toggle.addEventListener('click', function (e) {
        e.preventDefault();
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    });

    // Fermer si clic extérieur
    document.addEventListener('click', function (e) {
        if (!toggle.contains(e.target) && !menu.contains(e.target)) {
            menu.style.display = 'none';
        }
    });

    // Marquer notification comme lue
    function markAsRead(notificationId, link) {
        fetch(`/user/bondelivraison/asRead/${notificationId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(() => {
            // Mise à jour compteur
            const numSpan = document.querySelector('.notification .num');
            if (numSpan) {
                let count = parseInt(numSpan.innerText) - 1;
                if (count <= 0) numSpan.remove();
                else numSpan.innerText = count;
            }

            // Redirection GET normale
            window.location.href = link.href;
        });
    }
    </script>

<style>
    nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 20px;
    background-color: #1e1e2f;
    color: #fff;
    position: relative;
}

nav .form-input {
    display: flex;
    align-items: center;
}

nav .form-input input {
    padding: 5px 10px;
    border-radius: 5px 0 0 5px;
    border: none;
    outline: none;
}

nav .form-input button {
    padding: 5px 10px;
    border: none;
    background-color: #4caf50;
    color: #fff;
    border-radius: 0 5px 5px 0;
    cursor: pointer;
}

nav .form-input button:hover {
    background-color: #45a049;
}

.notification-wrapper {
    position: relative;
    margin-right: 20px;
}

.notification {
    position: relative;
    cursor: pointer;
    color: #fff;
}

.notification .num {
    position: absolute;
    top: -5px;
    right: -5px;
    background-color: #ff3b3b;
    color: #fff;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
    font-weight: bold;
}

.notification-menu {
    display: none;
    position: absolute;
    right: 0;
    background: #fff;
    border: 1px solid #ddd;
    width: 300px;
    max-height: 400px;
    overflow-y: auto;
    z-index: 100;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.notification-menu ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

.notification-menu ul li {
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.notification-menu ul li a:hover {
    background-color: #f5f5f5;
    display: block;
}

.profile img {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
    cursor: pointer;
    border: 2px solid #fff;
}

.switch-mode {
    cursor: pointer;
}

</style>
