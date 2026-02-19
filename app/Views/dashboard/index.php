<!-- Dashboard - Estilo Stripe -->
<div class="mb-4">
    <h1 class="h3 fw-bold text-dark">Dashboard</h1>
    <p class="text-muted mb-0">Resumen de tu operación</p>
</div>

<!-- KPI Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-stripe h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 bg-primary bg-opacity-10 p-3 me-3">
                        <i class="bi bi-shop text-primary fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Restaurantes</p>
                        <h3 class="fw-bold mb-0"><?= (int)($restaurantCount ?? 0) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-stripe h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 bg-success bg-opacity-10 p-3 me-3">
                        <i class="bi bi-people text-success fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Leads</p>
                        <h3 class="fw-bold mb-0"><?= (int)($leadCount ?? 0) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-stripe h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 bg-info bg-opacity-10 p-3 me-3">
                        <i class="bi bi-utensils text-info fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Platillos</p>
                        <h3 class="fw-bold mb-0"><?= (int)($menuItemCount ?? 0) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-stripe h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 bg-warning bg-opacity-10 p-3 me-3">
                        <i class="bi bi-tags text-warning fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Categorías</p>
                        <h3 class="fw-bold mb-0"><?= (int)(\App\Models\Category::count()) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leads por estado -->
<?php
$statusLabels = [
    'new' => 'Nuevo',
    'contacted' => 'Contactado',
    'interested' => 'Interesado',
    'negotiating' => 'Negociando',
    'closed' => 'Cerrado',
    'rejected' => 'Rechazado'
];
$leadsByStatus = $leadsByStatus ?? [];
?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-stripe">
            <div class="card-header">
                <i class="bi bi-bar-chart me-2"></i> Leads por estado
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <?php foreach ($statusLabels as $key => $label): ?>
                        <?php $count = $leadsByStatus[$key] ?? 0; ?>
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="rounded-3 p-3 text-center" style="background: var(--stripe-gray-100, #f3f4f6);">
                                <span class="badge badge-<?= $key ?>"><?= htmlspecialchars($label) ?></span>
                                <p class="fw-bold mb-0 mt-1"><?= (int)$count ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Últimos Restaurantes y Leads -->
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card card-stripe">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-shop me-2"></i> Últimos Restaurantes</span>
                <a href="<?= \App\Core\View::url('/restaurants') ?>" class="btn btn-sm btn-outline-primary">Ver todos</a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($latestRestaurants)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($latestRestaurants as $r): ?>
                            <a href="<?= \App\Core\View::url('/restaurants/edit/' . $r['id']) ?>" class="list-group-item list-group-item-action border-0 py-3 px-4">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1 fw-semibold"><?= \App\Core\View::escape($r['name']) ?></h6>
                                    <small class="text-muted"><?= date('d/m/Y', strtotime($r['created_at'])) ?></small>
                                </div>
                                <p class="mb-1 small text-muted"><i class="bi bi-envelope me-1"></i> <?= \App\Core\View::escape($r['email']) ?></p>
                                <small class="text-muted"><i class="bi bi-geo-alt me-1"></i> <?= \App\Core\View::escape($r['city']) ?></small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="p-4 text-center text-muted">
                        No hay restaurantes. <a href="<?= \App\Core\View::url('/restaurants/create') ?>">Crear uno</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-stripe">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people me-2"></i> Últimos Leads</span>
                <a href="<?= \App\Core\View::url('/leads') ?>" class="btn btn-sm btn-outline-primary">Ver todos</a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($latestLeads)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($latestLeads as $lead): ?>
                            <?php
                            $statusClass = 'badge-' . ($lead['status'] ?? 'new');
                            $statusLabel = $statusLabels[$lead['status'] ?? 'new'] ?? ucfirst($lead['status'] ?? '');
                            ?>
                            <a href="<?= \App\Core\View::url('/leads/edit/' . $lead['id']) ?>" class="list-group-item list-group-item-action border-0 py-3 px-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-1 fw-semibold"><?= \App\Core\View::escape($lead['name']) ?></h6>
                                    <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($statusLabel) ?></span>
                                </div>
                                <p class="mb-1 small text-muted"><i class="bi bi-shop me-1"></i> <?= \App\Core\View::escape($lead['restaurant_name']) ?></p>
                                <small class="text-muted"><?= date('d/m/Y', strtotime($lead['created_at'])) ?></small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="p-4 text-center text-muted">
                        No hay leads. <a href="<?= \App\Core\View::url('/leads/create') ?>">Crear uno</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
