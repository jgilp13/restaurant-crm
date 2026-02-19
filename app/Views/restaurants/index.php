<!-- Restaurants - Estilo Stripe -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark"><i class="bi bi-shop me-2"></i> Restaurantes</h1>
        <p class="text-muted mb-0">Gestiona tus restaurantes</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRestaurantModal">
        <i class="bi bi-plus-lg me-1"></i> Nuevo Restaurant
    </button>
</div>

<div class="card card-stripe">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-stripe table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Ciudad</th>
                        <th>Fecha</th>
                        <th class="text-end" style="width: 120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($restaurants)): ?>
                        <?php foreach ($restaurants as $restaurant): ?>
                            <tr>
                                <td><strong><?= \App\Core\View::escape($restaurant['name']) ?></strong></td>
                                <td><small class="text-muted"><?= \App\Core\View::escape($restaurant['email']) ?></small></td>
                                <td><small><?= \App\Core\View::escape($restaurant['phone']) ?></small></td>
                                <td><?= \App\Core\View::escape($restaurant['city']) ?></td>
                                <td><small class="text-muted"><?= date('d/m/Y', strtotime($restaurant['created_at'])) ?></small></td>
                                <td class="text-end">
                                    <a href="<?= \App\Core\View::url('/menu/' . $restaurant['id']) ?>" class="btn btn-sm btn-outline-secondary" title="Menú">
                                        <i class="bi bi-utensils"></i>
                                    </a>
                                    <a href="<?= \App\Core\View::url('/categories/' . $restaurant['id']) ?>" class="btn btn-sm btn-outline-secondary" title="Categorías">
                                        <i class="bi bi-tags"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-edit-restaurant"
                                            data-id="<?= (int)$restaurant['id'] ?>"
                                            data-name="<?= \App\Core\View::escape($restaurant['name']) ?>"
                                            data-email="<?= \App\Core\View::escape($restaurant['email']) ?>"
                                            data-phone="<?= \App\Core\View::escape($restaurant['phone']) ?>"
                                            data-address="<?= \App\Core\View::escape($restaurant['address'] ?? '') ?>"
                                            data-city="<?= \App\Core\View::escape($restaurant['city']) ?>"
                                            data-description="<?= \App\Core\View::escape($restaurant['description'] ?? '') ?>"
                                            title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete-restaurant"
                                            data-id="<?= (int)$restaurant['id'] ?>" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <p class="text-muted mb-3">No hay restaurantes registrados</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRestaurantModal">
                                    <i class="bi bi-plus-lg me-1"></i> Crear restaurant
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
<div class="modal fade" id="createRestaurantModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i> Nuevo Restaurant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createRestaurantForm">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
                    <div class="mb-3">
                        <label for="create_name" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="create_name" name="name" required>
                        <div class="invalid-feedback" id="error_create_name"></div>
                    </div>
                    <div class="mb-3">
                        <label for="create_email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="create_email" name="email" required>
                        <div class="invalid-feedback" id="error_create_email"></div>
                    </div>
                    <div class="mb-3">
                        <label for="create_phone" class="form-label">Teléfono *</label>
                        <input type="tel" class="form-control" id="create_phone" name="phone" required>
                        <div class="invalid-feedback" id="error_create_phone"></div>
                    </div>
                    <div class="mb-3">
                        <label for="create_address" class="form-label">Dirección *</label>
                        <input type="text" class="form-control" id="create_address" name="address" required>
                        <div class="invalid-feedback" id="error_create_address"></div>
                    </div>
                    <div class="mb-3">
                        <label for="create_city" class="form-label">Ciudad *</label>
                        <input type="text" class="form-control" id="create_city" name="city" required>
                        <div class="invalid-feedback" id="error_create_city"></div>
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
<div class="modal fade" id="editRestaurantModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i> Editar Restaurant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRestaurantForm">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                        <div class="invalid-feedback" id="error_edit_name"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                        <div class="invalid-feedback" id="error_edit_email"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Teléfono *</label>
                        <input type="tel" class="form-control" id="edit_phone" name="phone" required>
                        <div class="invalid-feedback" id="error_edit_phone"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_address" class="form-label">Dirección *</label>
                        <input type="text" class="form-control" id="edit_address" name="address" required>
                        <div class="invalid-feedback" id="error_edit_address"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_city" class="form-label">Ciudad *</label>
                        <input type="text" class="form-control" id="edit_city" name="city" required>
                        <div class="invalid-feedback" id="error_edit_city"></div>
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
<div class="modal fade" id="deleteRestaurantModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle me-2"></i> Eliminar Restaurant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">¿Estás seguro de que deseas eliminar este restaurante? Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteRestaurantBtn">
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

    document.querySelectorAll('.btn-edit-restaurant').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const d = this.dataset;
            document.getElementById('edit_id').value = d.id;
            document.getElementById('edit_name').value = d.name || '';
            document.getElementById('edit_email').value = d.email || '';
            document.getElementById('edit_phone').value = d.phone || '';
            document.getElementById('edit_address').value = d.address || '';
            document.getElementById('edit_city').value = d.city || '';
            document.getElementById('edit_description').value = d.description || '';
            new bootstrap.Modal(document.getElementById('editRestaurantModal')).show();
        });
    });

    document.querySelectorAll('.btn-delete-restaurant').forEach(function(btn) {
        btn.addEventListener('click', function() {
            deleteId = this.dataset.id;
            new bootstrap.Modal(document.getElementById('deleteRestaurantModal')).show();
        });
    });

    document.getElementById('createRestaurantForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        try {
            const res = await fetch('<?= \App\Core\View::url('/restaurants/store') ?>', { method: 'POST', body: formData });
            const data = await res.json();
            if (!res.ok) { showErrors(data.errors || {}, 'create_'); return; }
            location.reload();
        } catch (err) { alert('Error: ' + err.message); }
    });

    document.getElementById('editRestaurantForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('edit_id').value;
        const formData = new FormData(e.target);
        try {
            const res = await fetch('<?= \App\Core\View::url('/restaurants/update/') ?>' + id, { method: 'POST', body: formData });
            const data = await res.json();
            if (!res.ok) { showErrors(data.errors || {}, 'edit_'); return; }
            location.reload();
        } catch (err) { alert('Error: ' + err.message); }
    });

    document.getElementById('confirmDeleteRestaurantBtn').addEventListener('click', async function() {
        const formData = new FormData();
        formData.append('csrf_token', csrfToken);
        try {
            const res = await fetch('<?= \App\Core\View::url('/restaurants/delete/') ?>' + deleteId, { method: 'POST', body: formData });
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
