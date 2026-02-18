<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="fas fa-handshake"></i> Leads</h1>
            <p class="text-muted">Gestión de prospectos y contactos</p>
        </div>
        <div class="col-md-4 text-md-end">
            <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#createLeadModal">
                <i class="fas fa-plus"></i> Nuevo Lead
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3" id="filterForm">
                <div class="col-md-6">
                    <label for="searchInput" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="searchInput" name="search" 
                           value="<?= htmlspecialchars($filters['search'] ?? '') ?>" 
                           placeholder="Nombre, email o restaurante...">
                </div>

                <div class="col-md-6">
                    <label for="statusFilter" class="form-label">Estado</label>
                    <select class="form-select" id="statusFilter" name="status" onchange="this.form.submit()">
                        <option value="">Todos los estados</option>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?= htmlspecialchars($status) ?>" 
                                    <?= ($filters['status'] === $status) ? 'selected' : '' ?>>
                                <?php 
                                $labels = [
                                    'new' => 'Nuevo',
                                    'contacted' => 'Contactado',
                                    'interested' => 'Interesado',
                                    'negotiating' => 'Negociando',
                                    'closed' => 'Cerrado',
                                    'rejected' => 'Rechazado'
                                ];
                                echo $labels[$status] ?? $status;
                                ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    <a href="/leads" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Restaurante</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th width="150">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($leads)): ?>
                            <?php foreach ($leads as $lead): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($lead['name']) ?></strong>
                                    </td>
                                    <td>
                                        <i class="fas fa-envelope text-muted"></i> 
                                        <small><?= htmlspecialchars($lead['email']) ?></small>
                                    </td>
                                    <td>
                                        <i class="fas fa-phone text-muted"></i> 
                                        <small><?= htmlspecialchars($lead['phone']) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($lead['restaurant_name']) ?></td>
                                    <td>
                                        <?php
                                        $statusColors = [
                                            'new' => 'info',
                                            'contacted' => 'warning',
                                            'interested' => 'primary',
                                            'negotiating' => 'secondary',
                                            'closed' => 'success',
                                            'rejected' => 'danger'
                                        ];
                                        $statusLabels = [
                                            'new' => 'Nuevo',
                                            'contacted' => 'Contactado',
                                            'interested' => 'Interesado',
                                            'negotiating' => 'Negociando',
                                            'closed' => 'Cerrado',
                                            'rejected' => 'Rechazado'
                                        ];
                                        $color = $statusColors[$lead['status']] ?? 'secondary';
                                        $label = $statusLabels[$lead['status']] ?? $lead['status'];
                                        ?>
                                        <span class="badge bg-<?= $color ?>"><?= $label ?></span>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= date('d/m/Y', strtotime($lead['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="editLead(<?= $lead['id'] ?>, '<?= htmlspecialchars($lead['name']) ?>', '<?= htmlspecialchars($lead['email']) ?>', '<?= htmlspecialchars($lead['phone']) ?>', '<?= htmlspecialchars($lead['restaurant_name']) ?>', '<?= htmlspecialchars($lead['status']) ?>', '<?= htmlspecialchars($lead['notes'] ?? '') ?>')" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteLead(<?= $lead['id'] ?>)" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <p class="text-muted mb-3">No hay leads registrados</p>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLeadModal">
                                        <i class="fas fa-plus"></i> Crear lead
                                    </button>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Crear Lead -->
<div class="modal fade" id="createLeadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus"></i> Nuevo Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createLeadForm">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    
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
                            <option value="new">Nuevo</option>
                            <option value="contacted">Contactado</option>
                            <option value="interested">Interesado</option>
                            <option value="negotiating">Negociando</option>
                            <option value="closed">Cerrado</option>
                            <option value="rejected">Rechazado</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="create_notes" class="form-label">Notas</label>
                        <textarea class="form-control" id="create_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Editar Lead -->
<div class="modal fade" id="editLeadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editLeadForm">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    <input type="hidden" id="edit_id">
                    
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
                            <option value="new">Nuevo</option>
                            <option value="contacted">Contactado</option>
                            <option value="interested">Interesado</option>
                            <option value="negotiating">Negociando</option>
                            <option value="closed">Cerrado</option>
                            <option value="rejected">Rechazado</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_notes" class="form-label">Notas</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Confirmar Eliminación -->
<div class="modal fade" id="deleteLeadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white"><i class="fas fa-trash"></i> Eliminar Lead</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar este lead? Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash"></i> Sí, eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const csrfToken = '<?= htmlspecialchars($csrf_token) ?>';
let deleteLeadId = null;

// Cargar restaurantes al iniciar la página
document.addEventListener('DOMContentLoaded', () => {
    loadRestaurantsList();
});

// Cargar restaurantes disponibles en el datalist
async function loadRestaurantsList() {
    try {
        const formData = new FormData();
        formData.append('q', '');
        formData.append('csrf_token', csrfToken);
        
        const response = await fetch('/leads/search-restaurants', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        if (data.restaurants) {
            const datalist = document.getElementById('restaurants_list');
            datalist.innerHTML = '';
            
            data.restaurants.forEach(restaurant => {
                const option = document.createElement('option');
                option.value = restaurant.name;
                datalist.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error loading restaurants:', error);
    }
}

// Filtrar restaurantes mientras se escribe
document.getElementById('create_restaurant_name').addEventListener('input', async (e) => {
    const query = e.target.value;
    if (query.length > 0) {
        try {
            const formData = new FormData();
            formData.append('q', query);
            formData.append('csrf_token', csrfToken);
            
            const response = await fetch('/leads/search-restaurants', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            if (data.restaurants) {
                const datalist = document.getElementById('restaurants_list');
                datalist.innerHTML = '';
                
                data.restaurants.forEach(restaurant => {
                    const option = document.createElement('option');
                    option.value = restaurant.name;
                    datalist.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error searching restaurants:', error);
        }
    }
});

// Crear Lead
document.getElementById('createLeadForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    // Validar que los campos requeridos no estén vacíos
    const name = document.getElementById('create_name').value.trim();
    const email = document.getElementById('create_email').value.trim();
    const phone = document.getElementById('create_phone').value.trim();
    const restaurantName = document.getElementById('create_restaurant_name').value.trim();
    
    const errors = {};
    if (!name) errors.name = 'El nombre es requerido';
    if (!email) errors.email = 'El email es requerido';
    if (!phone) errors.phone = 'El teléfono es requerido';
    if (!restaurantName) errors.restaurant_name = 'El nombre del restaurante es requerido';
    
    if (Object.keys(errors).length > 0) {
        showErrors(errors, 'create_');
        return;
    }
    
    try {
        const formData = new FormData(e.target);
        const response = await fetch('/leads/store', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (!response.ok) {
            showErrors(data.errors, 'create_');
            return;
        }

        location.reload();
    } catch (error) {
        alert('Error: ' + error.message);
    }
});

// Editar Lead
document.getElementById('editLeadForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('edit_id').value;
    
    // Validar que los campos requeridos no estén vacíos
    const name = document.getElementById('edit_name').value.trim();
    const email = document.getElementById('edit_email').value.trim();
    const phone = document.getElementById('edit_phone').value.trim();
    const restaurantName = document.getElementById('edit_restaurant_name').value.trim();
    
    const errors = {};
    if (!name) errors.name = 'El nombre es requerido';
    if (!email) errors.email = 'El email es requerido';
    if (!phone) errors.phone = 'El teléfono es requerido';
    if (!restaurantName) errors.restaurant_name = 'El nombre del restaurante es requerido';
    
    if (Object.keys(errors).length > 0) {
        showErrors(errors, 'edit_');
        return;
    }
    
    try {
        const formData = new FormData(e.target);
        const response = await fetch(`/leads/update/${id}`, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (!response.ok) {
            showErrors(data.errors, 'edit_');
            return;
        }

        location.reload();
    } catch (error) {
        alert('Error: ' + error.message);
    }
});

function editLead(id, name, email, phone, restaurant_name, status, notes) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_phone').value = phone;
    document.getElementById('edit_restaurant_name').value = restaurant_name;
    document.getElementById('edit_status').value = status;
    document.getElementById('edit_notes').value = notes;
    
    new bootstrap.Modal(document.getElementById('editLeadModal')).show();
}

function deleteLead(id) {
    deleteLeadId = id;
    new bootstrap.Modal(document.getElementById('deleteLeadModal')).show();
}

document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
    try {
        const formData = new FormData();
        formData.append('csrf_token', csrfToken);
        
        const response = await fetch(`/leads/delete/${deleteLeadId}`, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (!response.ok) {
            alert('Error: ' + (data.error || 'No se pudo eliminar'));
            return;
        }

        location.reload();
    } catch (error) {
        alert('Error: ' + error.message);
    }
});

function showErrors(errors, prefix) {
    document.querySelectorAll('.invalid-feedback').forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });
    document.querySelectorAll('.form-control').forEach(el => {
        el.classList.remove('is-invalid');
    });

    for (const [field, message] of Object.entries(errors)) {
        const input = document.getElementById(prefix + field);
        const feedback = document.getElementById('error_' + prefix + field);
        
        if (input && feedback) {
            input.classList.add('is-invalid');
            feedback.textContent = message;
            feedback.style.display = 'block';
        }
    }
}
</script>

