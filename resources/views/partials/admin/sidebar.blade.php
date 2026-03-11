<section id="sidebar">
    <!-- LOGO -->
    <!-- MENU PRINCIPAL -->
    <ul class="side-menu top">
        <a href="{{ route('admin.dashboard') }}" class="brand">
            <img src="{{ asset('assets/img/bms.jpg') }}" alt="Logo BMS" class="brand-logo">
        </a>
        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Tableau de Bord</span>
            </a>
        </li>
        <li>
            <a href="{{ route('fournisseurs.index') }}">
                <i class='bx bxs-truck'></i>
                <span class="text">Fournisseurs</span>
            </a>
        </li>
        <li>
            <a href="{{ route('bondelivraison.index') }}">
                <i class='bx bxs-receipt'></i>
                <span class="text">Bon de Livraison</span>
            </a>
        </li>
        <li>
            <a href="{{ route('gestiondeploiement.index') }}">
                <i class='bx bxs-package'></i>
                <span class="text">Gestion Deploiement</span>
            </a>
        </li>
        <li>
            <a href="{{ route('gestionmateriel.index') }}">
                <i class='bx bxs-cabinet'></i>
                <span class="text">Gestion Matériels</span>
            </a>
        </li>
        <li>
            <a href="{{ route('marque.index') }}">
                <i class='bx bxs-purchase-tag'></i>
                <span class="text">Gestion Marques</span>
            </a>
        </li>
        <li>
            <a href="{{ route('deploiement.index') }}">
                <i class='bx bxs-map-pin'></i>
                <span class="text">Déploiement</span>
            </a>
        </li>
        <li>
            <a href="{{ route('service.index') }}">
                <i class='bx bxs-briefcase'></i>
                <span class="text">Services</span>
            </a>
        </li>
        <li>
            <a href="{{ route('repartition.index') }}">
                <i class='bx bxs-share-alt'></i>
                <span class="text">Répartition</span>
            </a>
        </li>
    </ul>

    <!-- PARAMÈTRES & DÉCONNEXION -->
    <ul class="side-menu bottom">
        <li>
            <a href="{{route("user.index")}}">
                <i class='bx bxs-user'></i>
                <span class="text">Gestion de Profil</span>
            </a>
        </li>
        <li>
            <a href="#"
               class="logout"
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

</section>
