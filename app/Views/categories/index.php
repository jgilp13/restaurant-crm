<!-- Categories - Estilo Stripe -->
<?php if (isset($flash) && $flash): ?>
    <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : $flash['type'] ?> alert-dismissible fade show mb-4">
        <?= htmlspecialchars($flash['message'] ?? '') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark"><i class="bi bi-tags me-2"></i> Categorías</h1>
        <p class="text-muted mb-0">Restaurante: <strong><?= \App\Core\View::escape($restaurant['name']) ?></strong></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= \App\Core\View::url('/restaurants') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
            <i class="bi bi-plus-lg me-1"></i> Nueva Categoría
        </button>
    </div>
</div>

<div class="card card-stripe">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-stripe table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th class="text-end" style="width: 120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><strong><?= \App\Core\View::escape($category['name']) ?></strong></td>
                                <td><small class="text-muted"><?= \App\Core\View::escape(substr($category['description'] ?? '', 0, 60)) ?><?= strlen($category['description'] ?? '') > 60 ? '...' : '' ?></small></td>
                                <td><small class="text-muted"><?= date('d/m/Y', strtotime($category['created_at'])) ?></small></td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-edit-category"
                                            data-id="<?= (int)$category['id'] ?>"
                                            data-name="<?= \App\Core\View::escape($category['name']) ?>"
                                            data-description="<?= \App\Core\View::escape($category['description'] ?? '') ?>"
                                            title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete-category"
                                            data-id="<?= (int)$category['id'] ?>" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <p class="text-muted mb-3">No hay categorías registradas</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                                    <i class="bi bi-plus-lg me-1"></i> Crear categoría
                                </button>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Crear -->
<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i> Nueva Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createCategoryForm">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
                    <input type="hidden" name="restaurant_id" value="<?= (int)($restaurant['id'] ?? 0) ?>">
                    <div class="mb-3">
                        <label for="create_name" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="create_name" name="name" required>
                        <div class="invalid-feedback" id="error_create_name"></div>
                    </div>
                    <div class="mb-0">
                        <label for="create_description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="create_description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i> Editar Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCategoryForm">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                        <div class="invalid-feedback" id="error_edit_name"></div>
                    </div>
                    <div class="mb-0">
                        <label for="edit_description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle me-2"></i> Eliminar Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">¿Estás seguro de que deseas eliminar esta categoría? Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteCategoryBtn">
                    <i class="bi bi-trash me-1"></i> Sí, eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const csrfToken = '<?= htmlspecialchars($csrf_token ?? '', ENT_QUOTES) ?>';
    let deleteId = null;

    document.querySelectorAll('.btn-edit-category').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const d = this.dataset;
            document.getElementById('edit_id').value = d.id;
            document.getElementById('edit_name').value = d.name || '';
            document.getElementById('edit_description').value = d.description || '';
            new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
        });
    });

    document.querySelectorAll('.btn-delete-category').forEach(function(btn) {
        btn.addEventListener('click', function() {
            deleteId = this.dataset.id;
            new bootstrap.Modal(document.getElementById('deleteCategoryModal')).show();
        });
    });

    document.getElementById('createCategoryForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        try {
            const res = await fetch('<?= \App\Core\View::url('/categories/store') ?>', { method: 'POST', body: formData });
            const data = await res.json();
            if (!res.ok) { showErrors(data.errors || {}, 'create_'); return; }
            location.reload();
        } catch (err) { alert('Error: ' + err.message); }
    });

    document.getElementById('editCategoryForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('edit_id').value;
        const formData = new FormData(e.target);
        try {
            const res = await fetch('<?= \App\Core\View::url('/categories/update/') ?>' + id, { method: 'POST', body: formData });
            const data = await res.json();
            if (!res.ok) { showErrors(data.errors || {}, 'edit_'); return; }
            location.reload();
        } catch (err) { alert('Error: ' + err.message); }
    });

    document.getElementById('confirmDeleteCategoryBtn').addEventListener('click', async function() {
        const formData = new FormData();
        formData.append('csrf_token', csrfToken);
        try {
            const res = await fetch('<?= \App\Core\View::url('/categories/delete/') ?>' + deleteId, { method: 'POST', body: formData });
            const data = await res.json();
            if (!res.ok) { alert(data.error || 'No se pudo eliminar'); return; }
            location.reload();
        } catch (err) { alert('Error: ' + err.message); }
    });

    function showErrors(errors, prefix) {
        document.querySelectorAll('.form-control.is-invalid').forEach(function(el) { el.classList.remove('is-invalid'); });
        document.querySelectorAll('[id^="error_"]').forEach(function(el) { el.textContent = ''; });
        for (const [field, msg] of Object.entries(errors)) {
            const inp = document.getElementById(prefix + field);
            const fb = document.getElementById('error_' + prefix + field);
            if (inp && fb) { inp.classList.add('is-invalid'); fb.textContent = msg; }
        }
    }
})();
</script>
