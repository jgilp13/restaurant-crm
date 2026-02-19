<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) . ' - ' : '' ?>Restaurant CRM</title>
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts - Inter (estilo Stripe) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --stripe-blue: #635bff;
            --stripe-blue-hover: #0a2540;
            --stripe-violet: #7c3aed;
            --stripe-gray-50: #f9fafb;
            --stripe-gray-100: #f3f4f6;
            --stripe-gray-200: #e5e7eb;
            --stripe-gray-500: #6b7280;
            --stripe-gray-700: #374151;
            --stripe-gray-900: #111827;
            --stripe-shadow: 0 1px 3px 0 rgba(0,0,0,0.1), 0 1px 2px -1px rgba(0,0,0,0.1);
            --stripe-shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.1);
            --stripe-shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(180deg, var(--stripe-gray-50) 0%, var(--stripe-gray-100) 50%);
            color: var(--stripe-gray-900);
            min-height: 100vh;
        }

        .app-wrapper {
            min-height: 100vh;
        }

        /* Navbar estilo Stripe */
        .navbar-stripe {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: saturate(180%) blur(20px);
            -webkit-backdrop-filter: saturate(180%) blur(20px);
            border-bottom: 1px solid var(--stripe-gray-200);
        }

        .navbar-brand-stripe {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--stripe-gray-900) !important;
        }

        .nav-link-stripe {
            font-weight: 500;
            color: var(--stripe-gray-600, #4b5563) !important;
            padding: 0.5rem 0.75rem !important;
            border-radius: 8px;
            transition: color 0.2s, background 0.2s;
        }

        .nav-link-stripe:hover {
            color: var(--stripe-blue) !important;
            background: rgba(99, 91, 255, 0.06);
        }

        .btn-stripe-primary {
            background: var(--stripe-blue);
            color: white !important;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: none;
            transition: all 0.2s;
        }

        .btn-stripe-primary:hover {
            background: #5145cd;
            color: white !important;
        }

        /* Cards */
        .card-stripe {
            background: white;
            border: 1px solid var(--stripe-gray-200);
            border-radius: 12px;
            box-shadow: var(--stripe-shadow);
            overflow: hidden;
        }

        .card-stripe .card-header {
            background: transparent;
            border-bottom: 1px solid var(--stripe-gray-200);
            font-weight: 600;
            padding: 1rem 1.25rem;
        }

        /* Tables */
        .table-stripe {
            --bs-table-hover-bg: rgba(99, 91, 255, 0.04);
        }

        .table-stripe thead th {
            font-weight: 600;
            color: var(--stripe-gray-700);
            border-bottom: 1px solid var(--stripe-gray-200);
            padding: 0.875rem 1rem;
        }

        .table-stripe tbody td {
            padding: 0.875rem 1rem;
        }

        /* Badges */
        .badge-new { background: #dbeafe; color: #1d4ed8; }
        .badge-contacted { background: #fef3c7; color: #b45309; }
        .badge-interested { background: #d1fae5; color: #047857; }
        .badge-negotiating { background: #e0e7ff; color: #3730a3; }
        .badge-closed { background: #dcfce7; color: #166534; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }

        /* Modals */
        .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        }

        .modal-header {
            border-bottom: 1px solid var(--stripe-gray-200);
            padding: 1.25rem 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid var(--stripe-gray-200);
            padding: 1rem 1.5rem;
        }

        /* Flash */
        .flash-toast {
            position: fixed;
            top: 80px;
            right: 24px;
            z-index: 9999;
            min-width: 320px;
            border-radius: 12px;
            box-shadow: var(--stripe-shadow-lg);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .content-area {
            padding: 2rem 0 3rem;
        }

        .btn-primary {
            background-color: var(--stripe-blue);
            border-color: var(--stripe-blue);
        }
        .btn-primary:hover {
            background-color: #5145cd;
            border-color: #5145cd;
        }
    </style>
</head>
<body class="app-wrapper">
<?php require __DIR__ . '/nav.php'; ?>
