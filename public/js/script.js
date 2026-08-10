document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    var alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Confirm before delete
    document.querySelectorAll('[data-confirm]').forEach(function(el) {
        el.addEventListener('click', function(e) {
            if (!confirm(el.dataset.confirm || 'Apakah Anda yakin?')) {
                e.preventDefault();
            }
        });
    });

    // Preview image before upload
    document.querySelectorAll('input[type="file"]').forEach(function(input) {
        input.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                var maxSize = this.hasAttribute('data-max-size')
                    ? parseInt(this.dataset.maxSize) : 5 * 1024 * 1024;

                if (this.files[0].size > maxSize) {
                    alert('Ukuran file terlalu besar. Maksimal ' + (maxSize / 1024 / 1024) + 'MB.');
                    this.value = '';
                }
            }
        });
    });
});