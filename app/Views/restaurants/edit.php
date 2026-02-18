<div class="row mb-4">
    <div class="col-md-12">
        <h1>
            <i class="fas fa-edit"></i> Editar Restaurante
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

                <form id="editRestaurantForm">
                    <?= \App\Core\Csrf::input() ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Restaurante *</label>
                        <input 
                            type="text" 
                            class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                            id="name" 
                            name="name"
                            placeholder="Ej: La Bella Italia"
                            value="<?= $_POST['name'] ?? $restaurant['name'] ?>"
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
                                    placeholder="contacto@restaurante.com"
                                    value="<?= $_POST['email'] ?? $restaurant['email'] ?>"
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
                                    value="<?= $_POST['phone'] ?? $restaurant['phone'] ?>"
                                    required
                                >
                                <?php if (isset($errors['phone'])): ?>
                                    <div class="invalid-feedback"><?= $errors['phone'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Dirección *</label>
                        <input 
                            type="text" 
                            class="form-control <?= isset($errors['address']) ? 'is-invalid' : '' ?>" 
                            id="address" 
                            name="address"
                            placeholder="Calle Principal 123, Apto 456"
                            value="<?= $_POST['address'] ?? $restaurant['address'] ?>"
                            required
                        >
                        <?php if (isset($errors['address'])): ?>
                            <div class="invalid-feedback"><?= $errors['address'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">Ciudad *</label>
                        <input 
                            type="text" 
                            class="form-control <?= isset($errors['city']) ? 'is-invalid' : '' ?>" 
                            id="city" 
                            name="city"
                            placeholder="Ciudad"
                            value="<?= $_POST['city'] ?? $restaurant['city'] ?>"
                            required
                        >
                        <?php if (isset($errors['city'])): ?>
                            <div class="invalid-feedback"><?= $errors['city'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea 
                            class="form-control" 
                            id="description" 
                            name="description"
                            rows="4"
                            placeholder="Describe el restaurante..."
                        ><?= $_POST['description'] ?? $restaurant['description'] ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary btn-lg" onclick="submitEditRestaurant()">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                        <a href="<?= \App\Core\View::url('/restaurants') ?>" class="btn btn-secondary btn-lg">
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
    function submitEditRestaurant() {
        const form = document.getElementById('editRestaurantForm');
        const formData = new FormData(form);
    const restaurantId = <?= $restaurant['id'] ?>;

    fetch('<?= \App\Core\View::url('/restaurants/update/') ?>' + restaurantId, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '<?= \App\Core\View::url('/restaurants') ?>';
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
