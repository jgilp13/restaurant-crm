<?php
$statusLabels = [
    'new' => 'Nuevo',
    'contacted' => 'Contactado',
    'interested' => 'Interesado',
    'negotiating' => 'Negociando',
    'closed' => 'Cerrado',
    'rejected' => 'Rechazado'
];
?>
<!-- Leads - Estilo Stripe -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark"><i class="bi bi-people me-2"></i> Leads</h1>
        <p class="text-muted mb-0">Gestión de prospectos y contactos</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLeadModal">
        <i class="bi bi-plus-lg me-1"></i> Nuevo Lead
    </button>
</div>

<!-- Filtros -->
<div class="card card-stripe mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3" id="filterForm">
            <div class="col-md-4">
                <label for="searchInput" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="searchInput" name="search"
                       value="<?= htmlspecialchars($filters['search'] ?? '') ?>"
                       placeholder="Nombre, email o restaurante...">
            </div>
            <div class="col-md-4">
                <label for="statusFilter" class="form-label">Estado</label>
                <select class="form-select" id="statusFilter" name="status" onchange="this.form.submit()">
                    <option value="">Todos los estados</option>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= htmlspecialchars($status) ?>" <?= ($filters['status'] ?? '') === $status ? 'selected' : '' ?>>
                            <?= htmlspecialchars($statusLabels[$status] ?? $status) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i> Filtrar</button>
                <a href="<?= \App\Core\View::url('/leads') ?>" class="btn btn-outline-secondary">Limpiar</a>
            </div>
        </form>
    </div>
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
                        <th>Restaurante</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th class="text-end" style="width: 100px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($leads)): ?>
                        <?php foreach ($leads as $lead): ?>
                            <?php $statusClass = 'badge-' . ($lead['status'] ?? 'new'); ?>
                            <tr>
                                <td><strong><?= \App\Core\View::escape($lead['name']) ?></strong></td>
                                <td><small class="text-muted"><?= \App\Core\View::escape($lead['email']) ?></small></td>
                                <td><small><?= \App\Core\View::escape($lead['phone']) ?></small></td>
                                <td><?= \App\Core\View::escape($lead['restaurant_name']) ?></td>
                                <td><span class="badge <?= $statusClass ?>"><?= htmlspecialchars($statusLabels[$lead['status'] ?? 'new'] ?? $lead['status']) ?></span></td>
                                <td><small class="text-muted"><?= date('d/m/Y', strtotime($lead['created_at'])) ?></small></td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-edit-lead"
                                            data-id="<?= (int)$lead['id'] ?>"
                                            data-name="<?= \App\Core\View::escape($lead['name']) ?>"
                                            data-email="<?= \App\Core\View::escape($lead['email']) ?>"
                                            data-phone="<?= \App\Core\View::escape($lead['phone']) ?>"
                                            data-restaurant-name="<?= \App\Core\View::escape($lead['restaurant_name']) ?>"
                                            data-status="<?= \App\Core\View::escape($lead['status']) ?>"
                                            data-notes="<?= \App\Core\View::escape($lead['notes'] ?? '') ?>"
                                            title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete-lead"
                                            data-id="<?= (int)$lead['id'] ?>" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <p class="text-muted mb-3">No hay leads registrados</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLeadModal">
                                    <i class="bi bi-plus-lg me-1"></i> Crear lead
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
<div class="modal fade" id="createLeadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i> Nuevo Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createLeadForm">
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
                        <label for="create_restaurant_name" class="form-label">Nombre del Restaurante *</label>
                        <input type="text" class="form-control" id="create_restaurant_name" name="restaurant_name" list="restaurants_list" required>
                        <datalist id="restaurants_list"></datalist>
                        <div class="invalid-feedback" id="error_create_restaurant_name"></div>
                    </div>
                    <div class="mb-3">
                        <label for="create_status" class="form-label">Estado</label>
                        <select class="form-select" id="create_status" name="status">
                            <?php foreach ($statusLabels as $key => $label): ?>
                                <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label for="create_notes" class="form-label">Notas</label>
                        <textarea class="form-control" id="create_notes" name="notes" rows="3"></textarea>
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
<div class="modal fade" id="editLeadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i> Editar Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editLeadForm">
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
                        <label for="edit_restaurant_name" class="form-label">Nombre del Restaurante *</label>
                        <input type="text" class="form-control" id="edit_restaurant_name" name="restaurant_name" list="restaurants_list" required>
                        <div class="invalid-feedback" id="error_edit_restaurant_name"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Estado</label>
                        <select class="form-select" id="edit_status" name="status">
                            <?php foreach ($statusLabels as $key => $label): ?>
                                <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label for="edit_notes" class="form-label">Notas</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
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
<div class="modal fade" id="deleteLeadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle me-2"></i> Eliminar Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">¿Estás seguro de que deseas eliminar este lead? Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteLeadBtn">
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

    document.addEventListener('DOMContentLoaded', loadRestaurantsList);

    function updateDatalist(restaurants) {
        const dl = document.getElementById('restaurants_list');
        if (!dl || !restaurants) return;
        dl.innerHTML = '';
        restaurants.forEach(function(r) {
            const opt = document.createElement('option');
            opt.value = r.name || '';
            dl.appendChild(opt);
        });
    }

    function loadRestaurantsList() {
        const fd = new FormData();
        fd.append('q', '');
        fd.append('csrf_token', csrfToken);
        fetch('<?= \App\Core\View::url('/leads/search-restaurants') ?>', { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(data) { updateDatalist(data.restaurants); })
            .catch(function() {});
    }

    document.getElementById('create_restaurant_name')?.addEventListener('input', function() {
        const q = this.value;
        if (!q) return;
        const fd = new FormData();
        fd.append('q', q);
        fd.append('csrf_token', csrfToken);
        fetch('<?= \App\Core\View::url('/leads/search-restaurants') ?>', { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(data) { updateDatalist(data.restaurants); })
            .catch(function() {});
    });

    document.querySelectorAll('.btn-edit-lead').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const d = this.dataset;
            document.getElementById('edit_id').value = d.id;
            document.getElementById('edit_name').value = d.name || '';
            document.getElementById('edit_email').value = d.email || '';
            document.getElementById('edit_phone').value = d.phone || '';
            document.getElementById('edit_restaurant_name').value = d.restaurantName || '';
            document.getElementById('edit_status').value = d.status || 'new';
            document.getElementById('edit_notes').value = d.notes || '';
            new bootstrap.Modal(document.getElementById('editLeadModal')).show();
        });
    });

    document.querySelectorAll('.btn-delete-lead').forEach(function(btn) {
        btn.addEventListener('click', function() {
            deleteId = this.dataset.id;
            new bootstrap.Modal(document.getElementById('deleteLeadModal')).show();
        });
    });

    document.getElementById('createLeadForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        try {
            const res = await fetch('<?= \App\Core\View::url('/leads/store') ?>', { method: 'POST', body: formData });
            const data = await res.json();
            if (!res.ok) { showErrors(data.errors || {}, 'create_'); return; }
            location.reload();
        } catch (err) { alert('Error: ' + err.message); }
    });

    document.getElementById('editLeadForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('edit_id').value;
        const formData = new FormData(e.target);
        try {
            const res = await fetch('<?= \App\Core\View::url('/leads/update/') ?>' + id, { method: 'POST', body: formData });
            const data = await res.json();
            if (!res.ok) { showErrors(data.errors || {}, 'edit_'); return; }
            location.reload();
        } catch (err) { alert('Error: ' + err.message); }
    });

    document.getElementById('confirmDeleteLeadBtn').addEventListener('click', async function() {
        const formData = new FormData();
        formData.append('csrf_token', csrfToken);
        try {
            const res = await fetch('<?= \App\Core\View::url('/leads/delete/') ?>' + deleteId, { method: 'POST', body: formData });
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
