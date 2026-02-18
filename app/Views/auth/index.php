<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-lg">
            <div class="card-header text-center py-4">
                <h2 class="m-0">
                    <i class="fas fa-lock"></i> Restaurant CRM
                </h2>
                <p class="text-white-50 mt-2 mb-0">Sistema de Gestión</p>
            </div>
            <div class="card-body p-5">
                <p class="text-center mb-4">
                    <strong>Credenciales de Demo:</strong><br>
                    Email: admin@restaurant.crm<br>
                    Contraseña: admin123
                </p>

                <form method="POST" action="<?= \App\Core\View::url('/login') ?>">
                    <?= \App\Core\Csrf::input() ?>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input 
                            type="email" 
                            class="form-control form-control-lg" 
                            id="email" 
                            name="email" 
                            placeholder="admin@restaurant.crm"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">
                            <i class="fas fa-key"></i> Contraseña
                        </label>
                        <input 
                            type="password" 
                            class="form-control form-control-lg" 
                            id="password" 
                            name="password" 
                            placeholder="••••••••"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-sign-in-alt"></i> Acceder
                    </button>
                </form>

                <hr class="my-4">

                <p class="text-center text-muted small">
                    Restaurant CRM v1.0 | Desarrollado con PHP 8.1+
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    }

    .navbar {
        display: none;
    }

    footer {
        display: none;
    }

    .content {
        min-height: auto;
        padding: 0;
    }
</style>
