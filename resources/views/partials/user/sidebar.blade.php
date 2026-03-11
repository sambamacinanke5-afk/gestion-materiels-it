<section id="sidebar">
    <!-- LOGO -->
    <a href="{{ route('user.dashboard') }}" class="brand">
        <img src="{{ asset('assets/img/bms.png') }}" alt="Logo BMS" class="brand-logo">
    </a>

    <!-- MENU PRINCIPAL -->
    <ul class="side-menu top">
        <li class="active">
            <a href="{{ route('user.dashboard') }}">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Tableau de Bord</span>
            </a>

        </li>

        <li>
            <a href="{{ route('user.bondelivraison.index') }}">
                <i class='bx bxs-receipt'></i>
                <span class="text">Bon de Livraison</span>
            </a>
        </li>

        {{-- <li>
            <a href="{{ route('user.deploiement.index') }}">
                <i class='bx bxs-map-pin'></i>
                <span class="text">Déploiement</span>
            </a>
        </li> --}}
        <li>
            <a href="{{ route('user.gestiondeploiement.index') }}">
                <i class='bx bxs-package'></i>
                <span class="text">Gestion Deploiement</span>
            </a>
        </li>
        <li>
            <a href="{{ route('user.repartition.index') }}">
                <i class='bx bxs-map-pin'></i>
                <span class="text">Répartition</span>
            </a>
        </li>
    </ul>

    <!-- PARAMÈTRES & DÉCONNEXION -->
    <ul class="side-menu bottom">
        {{-- <li>
            <a href="#">
                <i class='bx bxs-cog'></i>
                <span class="text">Paramètres</span>
            </a>
        </li> --}}

        <!-- 🔴 DÉCONNEXION FONCTIONNELLE -->
        <li>
            <a href="#" class="logout"
                onclick="event.preventDefault();
                        if(confirm('Voulez-vous vraiment vous déconnecter ?')) {
                            document.getElementById('logout-form').submit();
                        }">
                <i class='bx bxs-log-out-circle'></i>
                <span class="text">Déconnexion</span>
            </a>
        </li>
    </ul>

    <!-- FORMULAIRE DE DÉCONNEXION -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>

    <style>
        /* CENTRER LE LOGO ET AGRANDIR */
        .brand {
            display: flex;
            justify-content: center;
            /* centre horizontalement */
            align-items: center;
            /* centre verticalement */
            padding: 20px 0;
            /* plus d’espace autour */
        }

        .brand-logo {
            width: 150px;
            /* largeur plus grande */
            height: 150px;
            /* hauteur plus grande */
            border-radius: 12px;
            object-fit: cover;
        }

        .side-menu.bottom {
            position: absolute;
            bottom: 20px;
            width: calc(100% - 30px);
        }

        .logout {
            color: #f87171 !important;
        }
    </style>
</section>
