<div class="row justify-content-center min-vh-100 align-items-center py-5">
    <div class="col-md-6 col-lg-5">
        <div class="card card-stripe shadow">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h2 class="h4 fw-bold text-dark"><i class="bi bi-egg-fried text-primary me-2"></i> Restaurant CRM</h2>
                    <p class="text-muted mb-0">Sistema de Gestión</p>
                </div>
                <p class="text-center small text-muted mb-4">
                    <strong>Credenciales Demo:</strong> admin@restaurant.crm / admin123
                </p>

                <form method="POST" action="<?= \App\Core\View::url('/login') ?>">
                    <?= \App\Core\Csrf::input() ?>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control form-control-lg" id="email" name="email"
                               placeholder="admin@restaurant.crm" required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control form-control-lg" id="password" name="password"
                               placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Acceder
                    </button>
                </form>

                <p class="text-center text-muted small mt-4 mb-0">Restaurant CRM v1.0</p>
            </div>
        </div>
    </div>
</div>

<style>
.navbar { display: none !important; }
footer { display: none !important; }
body { min-height: 100vh; background: linear-gradient(135deg, #f9fafb 0%, #e5e7eb 50%) !important; }
.content-area { padding: 0 !important; }
</style>
