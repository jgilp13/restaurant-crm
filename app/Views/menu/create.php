<div class="row mb-4">
    <div class="col-md-12">
        <h1>
            <i class="fas fa-plus"></i> Nuevo Platillo
            <small class="text-muted"><?= \App\Core\View::escape($restaurant['name']) ?></small>
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
                <?php 
                $errors = $_SESSION['validation_errors'] ?? [];
                unset($_SESSION['validation_errors']);
                ?>

                <form method="POST" action="<?= \App\Core\View::url('/menu/' . $restaurant['id'] . '/store') ?>">
                    <?= \App\Core\Csrf::input() ?>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Categoría *</label>
                        <select class="form-select <?= isset($errors['category_id']) ? 'is-invalid' : '' ?>" id="category_id" name="category_id" required>
                            <option value="">Selecciona una categoría</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= ($_POST['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                    <?= \App\Core\View::escape($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['category_id'])): ?>
                            <div class="invalid-feedback"><?= $errors['category_id'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Platillo *</label>
                        <input 
                            type="text" 
                            class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                            id="name" 
                            name="name"
                            placeholder="Ej: Pasta a la Carbonara"
                            value="<?= $_POST['name'] ?? '' ?>"
                            required
                        >
                        <?php if (isset($errors['name'])): ?>
                            <div class="invalid-feedback"><?= $errors['name'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea 
                            class="form-control" 
                            id="description" 
                            name="description"
                            rows="3"
                            placeholder="Describe el platillo..."
                        ><?= $_POST['description'] ?? '' ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Precio *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input 
                                type="number" 
                                class="form-control <?= isset($errors['price']) ? 'is-invalid' : '' ?>" 
                                id="price" 
                                name="price"
                                placeholder="0.00"
                                step="0.01"
                                min="0"
                                value="<?= $_POST['price'] ?? '' ?>"
                                required
                            >
                            <?php if (isset($errors['price'])): ?>
                                <div class="invalid-feedback"><?= $errors['price'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <a href="<?= \App\Core\View::url('/menu/' . $restaurant['id']) ?>" class="btn btn-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
