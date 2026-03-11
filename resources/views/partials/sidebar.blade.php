<section id="sidebar">
    <a href="#" class="brand">
        <img src="{{ asset('assets/img/bms.jpg') }}" alt="Logo BMS" width="120" height="120"
            style="border-radius: 7px;">
        <span class="text" style="font-size: 18px; font-weight: bold; margin-left: 8px;">Gestion-IT</span>
    </a>
    <ul class="side-menu top">
        <li class="active">
            <a href="">
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
            <a href="{{route('bondelivraison.index')}}">
                <i class='bx bxs-receipt'></i>
                <span class="text">Bon de Livraison</span>
            </a>
        </li>

        <li>
            <a href="{{route('gestiondeploiement.index')}}">
                <i class='bx bxs-package'></i>
                <span class="text">Matériel</span>
            </a>
        </li>

        <li>
            <a href="{{route("gestionmateriel.index")}}">
                <i class='bx bxs-cabinet'></i>
                <span class="text">Gestion Matériels</span>
            </a>
        </li>

        <li>
            <a href="{{route('marque.index')}}">
                <i class='bx bxs-purchase-tag'></i>
                <span class="text">Gestion Marques</span>
            </a>
        </li>

        <li>
            <a href="{{route('deploiement.index')}}">
                <i class='bx bxs-map-pin'></i>
                <span class="text">Déploiement</span>
            </a>
        </li>
        <li>
            <a href="{{route('service.index')}}">
                <i class='bx bxs-map-pin'></i>
                <span class="text">Services</span>
            </a>
        </li>
        <li>
            <a href="{{route('repartition.index')}}">
                <i class='bx bxs-map-pin'></i>
                <span class="text">Repartition</span>
            </a>
        </li>
    </ul>

    <ul class="side-menu">
        {{-- <li>
            <a href="#">
                <i class='bx bxs-cog'></i>
                <span class="text">Paramètres</span>
            </a>
        </li> --}}
        <li>
            <a href="#" class="logout">
                <i class='bx bxs-log-out-circle'></i>
                <span class="text">Déconnexion</span>
            </a>
        </li>
    </ul>
</section>
