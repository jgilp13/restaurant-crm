<div class="container-fluid py-4">
    <!-- Flash Messages -->
    <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>
                <i class="fas fa-list"></i> Categorías
            </h1>
            <p class="text-muted">Restaurante: <strong><?= htmlspecialchars($restaurant['name']) ?></strong></p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/restaurants" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#createCategoryModal" data-restaurant-id="<?= $restaurant['id'] ?>">
                <i class="fas fa-plus"></i> Nueva Categoría
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
                            <th>Descripción</th>
                            <th>Fecha</th>
                            <th width="120">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($category['name']) ?></strong>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= htmlspecialchars(substr($category['description'] ?? '', 0, 50)) ?></small>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= date('d/m/Y', strtotime($category['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="editCategory(<?= $category['id'] ?>, '<?= htmlspecialchars($category['name']) ?>', '<?= htmlspecialchars($category['description'] ?? '') ?>')" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteCategory(<?= $category['id'] ?>)" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <p class="text-muted mb-3">No hay categorías registradas</p>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal" data-restaurant-id="<?= $restaurant['id'] ?>">
                                        <i class="fas fa-plus"></i> Crear categoría
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

<!-- Modal: Crear Categoria -->
<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus"></i> Nueva Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createCategoryForm">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    <input type="hidden" name="restaurant_id" id="create_restaurant_id" value="<?= $restaurant['id'] ?>">
                    
                    <div class="mb-3">
                        <label for="create_name" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="create_name" name="name" required>
                        <div class="invalid-feedback" id="error_create_name"></div>
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

<!-- Modal: Editar Categoria -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCategoryForm">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    <input type="hidden" id="edit_id">
                    
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                        <div class="invalid-feedback" id="error_edit_name"></div>
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
<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white"><i class="fas fa-trash"></i> Eliminar Categoría</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar esta categoría? Esta acción no se puede deshacer.</p>
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
const restaurantId = <?= $restaurant['id'] ?>;
let deleteCategoryId = null;

// Crear Categoría
document.getElementById('createCategoryForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    try {
        const formData = new FormData(e.target);
        const response = await fetch('/categories/store', {
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

// Editar Categoría
document.getElementById('editCategoryForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('edit_id').value;
    
    try {
        const formData = new FormData(e.target);
        const response = await fetch(`/categories/update/${id}`, {
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

function editCategory(id, name, description) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description;
    
    new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
}

function deleteCategory(id) {
    deleteCategoryId = id;
    new bootstrap.Modal(document.getElementById('deleteCategoryModal')).show();
}

document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
    try {
        const formData = new FormData();
        formData.append('csrf_token', csrfToken);
        
        const response = await fetch(`/categories/delete/${deleteCategoryId}`, {
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
