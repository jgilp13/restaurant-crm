<div class="row mb-4">
    <div class="col-md-12">
        <h1>
            <i class="fas fa-edit"></i> Editar Lead
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-form"></i> Formulario
            </div>
            <div class="card-body">
                <div id="updateError" class="alert alert-danger d-none" role="alert"></div>

                <form id="editLeadForm">
                    <?= \App\Core\Csrf::input() ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Contacto *</label>
                        <input 
                            type="text" 
                            class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                            id="name" 
                            name="name"
                            placeholder="Ej: Juan García"
                            value="<?= $_POST['name'] ?? $lead['name'] ?>"
                            required
                        >
                        <?php if (isset($errors['name'])): ?>
                            <div class="invalid-feedback"><?= $errors['name'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input 
                                    type="email" 
                                    class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                    id="email" 
                                    name="email"
                                    placeholder="juan@example.com"
                                    value="<?= $_POST['email'] ?? $lead['email'] ?>"
                                    required
                                >
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Teléfono *</label>
                                <input 
                                    type="tel" 
                                    class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" 
                                    id="phone" 
                                    name="phone"
                                    placeholder="+1 (555) 123-4567"
                                    value="<?= $_POST['phone'] ?? $lead['phone'] ?>"
                                    required
                                >
                                <?php if (isset($errors['phone'])): ?>
                                    <div class="invalid-feedback"><?= $errors['phone'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="restaurant_name" class="form-label">Nombre del Restaurante *</label>
                        <input 
                            type="text" 
                            class="form-control <?= isset($errors['restaurant_name']) ? 'is-invalid' : '' ?>" 
                            id="restaurant_name" 
                            name="restaurant_name"
                            placeholder="Ej: Restaurante La Paz"
                            value="<?= $_POST['restaurant_name'] ?? $lead['restaurant_name'] ?>"
                            required
                        >
                        <?php if (isset($errors['restaurant_name'])): ?>
                            <div class="invalid-feedback"><?= $errors['restaurant_name'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Estado</label>
                        <select class="form-select" id="status" name="status">
                            <option value="new" <?= ($lead['status'] === 'new') ? 'selected' : '' ?>>Nuevo</option>
                            <option value="contacted" <?= ($lead['status'] === 'contacted') ? 'selected' : '' ?>>Contactado</option>
                            <option value="interested" <?= ($lead['status'] === 'interested') ? 'selected' : '' ?>>Interesado</option>
                            <option value="negotiating" <?= ($lead['status'] === 'negotiating') ? 'selected' : '' ?>>Negociando</option>
                            <option value="closed" <?= ($lead['status'] === 'closed') ? 'selected' : '' ?>>Cerrado</option>
                            <option value="rejected" <?= ($lead['status'] === 'rejected') ? 'selected' : '' ?>>Rechazado</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas</label>
                        <textarea 
                            class="form-control" 
                            id="notes" 
                            name="notes"
                            rows="4"
                            placeholder="Notas adicionales sobre el lead..."
                        ><?= $_POST['notes'] ?? $lead['notes'] ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary btn-lg" onclick="submitEditLead()">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                        <a href="<?= \App\Core\View::url('/leads') ?>" class="btn btn-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // @noinspection JSUnresolvedVariable
    function submitEditLead() {
        const form = document.getElementById('editLeadForm');
        const formData = new FormData(form);
    const leadId = <?= $lead['id'] ?>;

    fetch('<?= \App\Core\View::url('/leads/update/') ?>' + leadId, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '<?= \App\Core\View::url('/leads') ?>';
        } else {
            showErrors(data.errors || {data: [data.error]});
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrors({data: [error.message]});
    });
}

function showErrors(errors) {
    const errorDiv = document.getElementById('updateError');
    const messages = [];
    for (const field in errors) {
        if (Array.isArray(errors[field])) {
            messages.push(...errors[field]);
        }
    }
    errorDiv.innerHTML = messages.join('<br>');
    errorDiv.classList.remove('d-none');
}
</script>
            </div>
        </div>
    </div>
</div>
