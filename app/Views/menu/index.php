<div class="row mb-4">
    <div class="col-md-8">
        <h1>
            <i class="bi bi-utensils"></i> Menú
            <small class="text-muted"><?= \App\Core\View::escape($restaurant['name']) ?></small>
        </h1>
        <small class="text-muted">Total: <?= $total ?? 0 ?> platillos</small>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="<?= \App\Core\View::url('/menu/' . $restaurant['id'] . '/create') ?>" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-lg"></i> Nuevo Platillo
        </a>
    </div>
</div>

<!-- Filtro por Categoría -->
<?php if (!empty($categories)): ?>
    <div class="card mb-3">
        <div class="card-body">
            <a href="<?= \App\Core\View::url('/menu/' . $restaurant['id']) ?>" class="btn btn-outline-primary <?= empty($currentCategory) ? 'active' : '' ?>">
                Todos
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?= \App\Core\View::url('/menu/' . $restaurant['id'] . '?category=' . $cat['id']) ?>" 
                   class="btn btn-outline-secondary <?= $currentCategory == $cat['id'] ? 'active' : '' ?>">
                    <?= \App\Core\View::escape($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <strong><?= \App\Core\View::escape($item['name']) ?></strong>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    <?php 
                                    $category = \App\Models\Category::findById($item['category_id']);
                                    echo \App\Core\View::escape($category['name'] ?? 'Sin categoría');
                                    ?>
                                </span>
                            </td>
                            <td>
                                <small><?= substr(\App\Core\View::escape($item['description'] ?? ''), 0, 50) ?>...</small>
                            </td>
                            <td>
                                <strong>$<?= number_format($item['price'], 2) ?></strong>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="<?= \App\Core\View::url('/menu/' . $restaurant['id'] . '/edit/' . $item['id']) ?>" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="<?= \App\Core\View::url('/menu/' . $restaurant['id'] . '/delete/' . $item['id']) ?>" style="display:inline;">
                                        <?= \App\Core\Csrf::input() ?>
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar platillo?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <p class="text-muted mb-2">No hay platillos en este menú</p>
                            <a href="<?= \App\Core\View::url('/menu/' . $restaurant['id'] . '/create') ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-lg"></i> Agregar platillo
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Paginación">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=1<?= !empty($currentCategory) ? '&category=' . $currentCategory : '' ?>"><i class="bi bi-chevron-double-left"></i></a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?><?= !empty($currentCategory) ? '&category=' . $currentCategory : '' ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $totalPages ?><?= !empty($currentCategory) ? '&category=' . $currentCategory : '' ?>"><i class="bi bi-chevron-double-right"></i></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<div class="mt-3">
    <a href="<?= \App\Core\View::url('/restaurants') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver a Restaurantes
    </a>
</div>
