<nav class="navbar navbar-expand-lg navbar-stripe sticky-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand navbar-brand-stripe" href="<?= \App\Core\View::url('/dashboard') ?>">
            <i class="bi bi-egg-fried text-primary me-1"></i> Restaurant CRM
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto align-items-center gap-1">
                <li class="nav-item">
                    <a class="nav-link nav-link-stripe" href="<?= \App\Core\View::url('/dashboard') ?>">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-stripe" href="<?= \App\Core\View::url('/restaurants') ?>">
                        <i class="bi bi-shop me-1"></i> Restaurantes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-stripe" href="<?= \App\Core\View::url('/leads') ?>">
                        <i class="bi bi-people me-1"></i> Leads
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link nav-link-stripe dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i> Cuenta
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="<?= \App\Core\View::url('/logout') ?>">
                                <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
