<footer class="py-4 mt-auto" style="background: var(--stripe-gray-100, #f3f4f6); border-top: 1px solid var(--stripe-gray-200, #e5e7eb);">
    <div class="container-fluid px-4">
        <p class="text-center text-muted mb-0 small">&copy; <?= date('Y') ?> Restaurant CRM</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.flash-toast .alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) bsAlert.close();
        }, 5000);
    });
});
</script>
</body>
</html>
