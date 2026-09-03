/* =========================================================
   Sail & Hunt Chapter I – Dashboard JS
   File : assets/js/dashboard.js
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    // Tutup alert
    document.querySelectorAll('.dashboard-alert-close').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var alert = this.closest('.dashboard-alert');
            if (alert) {
                alert.remove();
            }
        });
    });

    // (Tempat untuk menambahkan interaksi dashboard lainnya)
});