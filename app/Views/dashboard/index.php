<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">
            <i class="fas fa-chart-line"></i> Dashboard
        </h1>
    </div>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body stat-card">
                <h3><?= $restaurantCount ?? 0 ?></h3>
                <p><i class="fas fa-building"></i> Restaurantes</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body stat-card">
                <h3><?= $leadCount ?? 0 ?></h3>
                <p><i class="fas fa-handshake"></i> Leads</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body stat-card">
                <h3><?= $menuItemCount ?? 0 ?></h3>
                <p><i class="fas fa-utensils"></i> Platillos</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body stat-card">
                <h3><?= \App\Models\Category::count() ?? 0 ?></h3>
                <p><i class="fas fa-tags"></i> Categorías</p>
            </div>
        </div>
    </div>
</div>

<!-- Últimos Restaurantes -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-building"></i> Últimos Restaurantes
                <a href="<?= \App\Core\View::url('/restaurants') ?>" class="btn btn-sm btn-light float-end">Ver todos</a>
            </div>
            <div class="card-body">
                <?php if (!empty($latestRestaurants)): ?>
                    <div class="list-group">
                        <?php foreach ($latestRestaurants as $restaurant): ?>
                            <a href="<?= \App\Core\View::url('/restaurants/edit/' . $restaurant['id']) ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?= \App\Core\View::escape($restaurant['name']) ?></h6>
                                    <small class="text-muted"><?= date('d/m/Y', strtotime($restaurant['created_at'])) ?></small>
                                </div>
                                <p class="mb-1 small">
                                    <i class="fas fa-envelope"></i> <?= \App\Core\View::escape($restaurant['email']) ?>
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt"></i> <?= \App\Core\View::escape($restaurant['city']) ?>
                                </small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info m-0">
                        No hay restaurantes registrados. <a href="<?= \App\Core\View::url('/restaurants/create') ?>">Crear uno</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Últimos Leads -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-handshake"></i> Últimos Leads
                <a href="<?= \App\Core\View::url('/leads') ?>" class="btn btn-sm btn-light float-end">Ver todos</a>
            </div>
            <div class="card-body">
                <?php if (!empty($latestLeads)): ?>
                    <div class="list-group">
                        <?php foreach ($latestLeads as $lead): ?>
                            <a href="<?= \App\Core\View::url('/leads/edit/' . $lead['id']) ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?= \App\Core\View::escape($lead['name']) ?></h6>
                                    <span class="badge badge-<?= $lead['status'] === 'closed' ? 'success' : ($lead['status'] === 'rejected' ? 'danger' : 'warning') ?>">
                                        <?= ucfirst($lead['status']) ?>
                                    </span>
                                </div>
                                <p class="mb-1 small">
                                    <i class="fas fa-building"></i> <?= \App\Core\View::escape($lead['restaurant_name']) ?>
                                </p>
                                <small class="text-muted"><?= date('d/m/Y', strtotime($lead['created_at'])) ?></small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info m-0">
                        No hay leads registrados. <a href="<?= \App\Core\View::url('/leads/create') ?>">Crear uno</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
