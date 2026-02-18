<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?>Restaurant CRM</title>
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #e74c3c;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #34495e 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .nav-link {
            margin-left: 10px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--secondary-color) !important;
        }

        .sidebar {
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .sidebar-item {
            padding: 12px 15px;
            border-left: 3px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
        }

        .sidebar-item:hover {
            background-color: #f0f0f0;
            border-left-color: var(--secondary-color);
            text-decoration: none;
        }

        .sidebar-item.active {
            background-color: #e8f4f8;
            border-left-color: var(--secondary-color);
            color: var(--primary-color);
            font-weight: bold;
        }

        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #34495e 100%);
            color: white;
            font-weight: bold;
            border: none;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #34495e;
            border-color: #34495e;
        }

        .btn-danger {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }

        .badge {
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
        }

        .pagination {
            margin-top: 20px;
        }

        .pagination .page-link {
            color: var(--primary-color);
        }

        .pagination .page-link:hover {
            color: white;
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .alert {
            border: none;
            border-radius: 5px;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .flash-message {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 1000;
            max-width: 400px;
            animation: slideIn 0.3s ease-out;
        }

        .content {
            padding: 30px 0;
            min-height: 600px;
        }

        footer {
            background: var(--primary-color);
            color: white;
            padding: 30px 0;
            margin-top: 50px;
            text-align: center;
        }

        .stat-card {
            text-align: center;
            padding: 20px;
        }

        .stat-card h3 {
            color: var(--primary-color);
            font-weight: bold;
            font-size: 2.5rem;
        }

        .stat-card p {
            color: #666;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= \App\Core\View::url('/dashboard') ?>">
                <i class="fas fa-utensils"></i> Restaurant CRM
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= \App\Core\View::url('/dashboard') ?>">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= \App\Core\View::url('/restaurants') ?>">
                            <i class="fas fa-building"></i> Restaurantes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= \App\Core\View::url('/leads') ?>">
                            <i class="fas fa-handshake"></i> Leads
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> Cuenta
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?= \App\Core\View::url('/logout') ?>">
                                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (isset($flash) && $flash): ?>
        <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : $flash['type'] ?> flash-message alert-dismissible fade show">
            <strong><?= ucfirst($flash['type']) ?>:</strong> <?= $flash['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="container content">
        <?= $content ?>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2026 Restaurant CRM. Desarrollado con <i class="fas fa-heart" style="color: #e74c3c;"></i> por tu equipo</p>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Auto-dismiss alerts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>
</html>
