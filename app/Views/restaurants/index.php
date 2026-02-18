<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="fas fa-building"></i> Restaurantes</h1>
            <p class="text-muted">Gestiona tus restaurantes</p>
        </div>
        <div class="col-md-4 text-md-end">
            <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#createRestaurantModal">
                <i class="fas fa-plus"></i> Nuevo Restaurant
            </button>
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
                            <th>Ciudad</th>
                            <th>Fecha</th>
                            <th width="180">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($restaurants)): ?>
                            <?php foreach ($restaurants as $restaurant): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($restaurant['name']) ?></strong>
                                    </td>
                                    <td>
                                        <i class="fas fa-envelope text-muted"></i> 
                                        <small><?= htmlspecialchars($restaurant['email']) ?></small>
                                    </td>
                                    <td>
                                        <i class="fas fa-phone text-muted"></i> 
                                        <small><?= htmlspecialchars($restaurant['phone']) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($restaurant['city']) ?></td>
                                    <td>
                                        <small class="text-muted"><?= date('d/m/Y', strtotime($restaurant['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="editRestaurant(<?= $restaurant['id'] ?>, '<?= htmlspecialchars($restaurant['name']) ?>', '<?= htmlspecialchars($restaurant['email']) ?>', '<?= htmlspecialchars($restaurant['phone']) ?>', '<?= htmlspecialchars($restaurant['address'] ?? '') ?>', '<?= htmlspecialchars($restaurant['city']) ?>', '<?= htmlspecialchars($restaurant['description'] ?? '') ?>')" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteRestaurant(<?= $restaurant['id'] ?>)" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <p class="text-muted mb-3">No hay restaurantes registrados</p>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRestaurantModal">
                                        <i class="fas fa-plus"></i> Crear restaurant
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

<!-- Modal: Crear Restaurant -->
<div class="modal fade" id="createRestaurantModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus"></i> Nuevo Restaurant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createRestaurantForm">
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
                        <label for="create_address" class="form-label">Dirección *</label>
                        <input type="text" class="form-control" id="create_address" name="address" required>
                        <div class="invalid-feedback" id="error_create_address"></div>
                    </div>

                    <div class="mb-3">
                        <label for="create_city" class="form-label">Ciudad *</label>
                        <input type="text" class="form-control" id="create_city" name="city" required>
                        <div class="invalid-feedback" id="error_create_city"></div>
                    </div>

                    <div class="mb-3">
                        <label for="create_description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="create_description" name="description" rows="3"></textarea>
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

<!-- Modal: Editar Restaurant -->
<div class="modal fade" id="editRestaurantModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Restaurant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRestaurantForm">
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
                        <label for="edit_address" class="form-label">Dirección *</label>
                        <input type="text" class="form-control" id="edit_address" name="address" required>
                        <div class="invalid-feedback" id="error_edit_address"></div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_city" class="form-label">Ciudad *</label>
                        <input type="text" class="form-control" id="edit_city" name="city" required>
                        <div class="invalid-feedback" id="error_edit_city"></div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
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
<div class="modal fade" id="deleteRestaurantModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white"><i class="fas fa-trash"></i> Eliminar Restaurant</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar este restaurante? Esta acción no se puede deshacer.</p>
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
let deleteRestaurantId = null;

// Crear Restaurant
document.getElementById('createRestaurantForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    
    // Validar que los campos requeridos no estén vacíos
    const name = document.getElementById('create_name').value.trim();
    const email = document.getElementById('create_email').value.trim();
    const phone = document.getElementById('create_phone').value.trim();
    const address = document.getElementById('create_address').value.trim();
    const city = document.getElementById('create_city').value.trim();
    
    if (!name || !email || !phone || !address || !city) {
        const errors = {};
        if (!name) errors.name = 'El nombre es requerido';
        if (!email) errors.email = 'El email es requerido';
        if (!phone) errors.phone = 'El teléfono es requerido';
        if (!address) errors.address = 'La dirección es requerida';
        if (!city) errors.city = 'La ciudad es requerida';
        showErrors(errors, 'create_');
        return;
    }
    
    try {
        const formData = new FormData(form);
        const response = await fetch('/restaurants/store', {
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

// Editar Restaurant
document.getElementById('editRestaurantForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('edit_id').value;
    const form = e.target;
    
    // Validar que los campos requeridos no estén vacíos
    const name = document.getElementById('edit_name').value.trim();
    const email = document.getElementById('edit_email').value.trim();
    const phone = document.getElementById('edit_phone').value.trim();
    const address = document.getElementById('edit_address').value.trim();
    const city = document.getElementById('edit_city').value.trim();
    
    if (!name || !email || !phone || !address || !city) {
        const errors = {};
        if (!name) errors.name = 'El nombre es requerido';
        if (!email) errors.email = 'El email es requerido';
        if (!phone) errors.phone = 'El teléfono es requerido';
        if (!address) errors.address = 'La dirección es requerida';
        if (!city) errors.city = 'La ciudad es requerida';
        showErrors(errors, 'edit_');
        return;
    }
    
    try {
        const formData = new FormData(form);
        const response = await fetch(`/restaurants/update/${id}`, {
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

function editRestaurant(id, name, email, phone, address, city, description) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_phone').value = phone;
    document.getElementById('edit_address').value = address;
    document.getElementById('edit_city').value = city;
    document.getElementById('edit_description').value = description;
    
    new bootstrap.Modal(document.getElementById('editRestaurantModal')).show();
}

function deleteRestaurant(id) {
    deleteRestaurantId = id;
    new bootstrap.Modal(document.getElementById('deleteRestaurantModal')).show();
}

document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
    try {
        const formData = new FormData();
        formData.append('csrf_token', csrfToken);
        
        const response = await fetch(`/restaurants/delete/${deleteRestaurantId}`, {
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
    // Limpiar errores anteriores
    document.querySelectorAll('.invalid-feedback').forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });
    document.querySelectorAll('.form-control').forEach(el => {
        el.classList.remove('is-invalid');
    });

    // Mostrar nuevos errores
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

