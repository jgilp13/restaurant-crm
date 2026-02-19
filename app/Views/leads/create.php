<div class="row mb-4">
    <div class="col-md-12">
        <h1>
            <i class="bi bi-plus-lg"></i> Nuevo Lead
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil-square"></i> Formulario
            </div>
            <div class="card-body">
                <?php 
                $errors = $_SESSION['validation_errors'] ?? [];
                unset($_SESSION['validation_errors']);
                ?>

                <form method="POST" action="<?= \App\Core\View::url('/leads/store') ?>">
                    <?= \App\Core\Csrf::input() ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Contacto *</label>
                        <input 
                            type="text" 
                            class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                            id="name" 
                            name="name"
                            placeholder="Ej: Juan García"
                            value="<?= $_POST['name'] ?? '' ?>"
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
                                    value="<?= $_POST['email'] ?? '' ?>"
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
                                    value="<?= $_POST['phone'] ?? '' ?>"
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
                            value="<?= $_POST['restaurant_name'] ?? '' ?>"
                            required
                        >
                        <?php if (isset($errors['restaurant_name'])): ?>
                            <div class="invalid-feedback"><?= $errors['restaurant_name'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Estado</label>
                        <select class="form-select" id="status" name="status">
                            <option value="new">Nuevo</option>
                            <option value="contacted">Contactado</option>
                            <option value="interested">Interesado</option>
                            <option value="negotiating">Negociando</option>
                            <option value="closed">Cerrado</option>
                            <option value="rejected">Rechazado</option>
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
                        ><?= $_POST['notes'] ?? '' ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-lg"></i> Guardar
                        </button>
                        <a href="<?= \App\Core\View::url('/leads') ?>" class="btn btn-secondary btn-lg">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
