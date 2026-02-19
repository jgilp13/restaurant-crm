<div class="row mb-4">
    <div class="col-md-12">
        <h1>
            <i class="bi bi-plus-lg"></i> Nuevo Restaurante
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

                <form method="POST" action="<?= \App\Core\View::url('/restaurants/store') ?>">
                    <?= \App\Core\Csrf::input() ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Restaurante *</label>
                        <input 
                            type="text" 
                            class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                            id="name" 
                            name="name"
                            placeholder="Ej: La Bella Italia"
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
                                    placeholder="contacto@restaurante.com"
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
                        <label for="address" class="form-label">Dirección *</label>
                        <input 
                            type="text" 
                            class="form-control <?= isset($errors['address']) ? 'is-invalid' : '' ?>" 
                            id="address" 
                            name="address"
                            placeholder="Calle Principal 123, Apto 456"
                            value="<?= $_POST['address'] ?? '' ?>"
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
                            value="<?= $_POST['city'] ?? '' ?>"
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
                        ><?= $_POST['description'] ?? '' ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-lg"></i> Guardar
                        </button>
                        <a href="<?= \App\Core\View::url('/restaurants') ?>" class="btn btn-secondary btn-lg">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
