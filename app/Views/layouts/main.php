<?php
/**
 * Layout principal - Restaurant CRM (Estilo Stripe)
 * Incluye: header (con nav), flash, contenido, footer
 */
require __DIR__ . '/header.php';
?>

<?php if (isset($flash) && $flash): ?>
<div class="flash-toast alert alert-<?= $flash['type'] === 'error' ? 'danger' : $flash['type'] ?> alert-dismissible fade show">
    <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle' : ($flash['type'] === 'error' ? 'exclamation-triangle' : 'info-circle') ?> me-2"></i>
    <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
</div>
<?php endif; ?>

<div class="container-fluid px-4 content-area">
    <?= $content ?? '' ?>
</div>

<?php require __DIR__ . '/footer.php'; ?>
