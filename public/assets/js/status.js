/**
 * =========================================================
 * STATUS PENDAFTARAN
 * Page-specific JavaScript
 * =========================================================
 */


/* =========================================================
   COPY NOMOR REKENING
   ========================================================= */

function copyRekening(event) {
    const rekening = document
        .getElementById('rekeningText')
        .innerText
        .trim();

    const btn = event.currentTarget;
    const originalHtml = btn.innerHTML;

    const showSuccess = function () {
        btn.innerHTML = '<i class="fas fa-check mr-1"></i> Tersalin';

        btn.classList.remove('btn-outline-primary');
        btn.classList.add('btn-success');

        setTimeout(function () {
            btn.innerHTML = originalHtml;

            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-primary');
        }, 1500);
    };

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard
            .writeText(rekening)
            .then(showSuccess)
            .catch(function () {
                fallbackCopyRekening(rekening, showSuccess);
            });
    } else {
        fallbackCopyRekening(rekening, showSuccess);
    }
}


/* =========================================================
   FALLBACK COPY NOMOR REKENING
   ========================================================= */

function fallbackCopyRekening(rekening, callback) {
    const textArea = document.createElement('textarea');

    textArea.value = rekening;

    textArea.style.position = 'fixed';
    textArea.style.left = '-9999px';
    textArea.style.top = '0';

    document.body.appendChild(textArea);

    textArea.focus();
    textArea.select();

    try {
        document.execCommand('copy');
        callback();
    } catch (error) {
        alert('Nomor rekening: ' + rekening);
    }

    document.body.removeChild(textArea);
}


/* =========================================================
   PREVIEW FILE PEMBAYARAN
   ========================================================= */

function previewFile(input) {
    const previewDiv = document.getElementById('filePreview');
    const previewImg = document.getElementById('previewImage');
    const previewName = document.getElementById('previewFileName');
    const label = input.nextElementSibling;

    /*
     * Tidak ada file yang dipilih.
     */
    if (!input.files || !input.files[0]) {
        previewDiv.classList.add('d-none');

        label.innerText = 'Pilih file...';

        previewImg.removeAttribute('src');
        previewName.innerText = '';

        return;
    }

    const file = input.files[0];
    const maxSize = 2 * 1024 * 1024;

    /*
     * Tampilkan nama file.
     */
    label.innerText = file.name;

    previewName.innerText =
        file.name +
        ' (' +
        (file.size / 1024 / 1024).toFixed(2) +
        ' MB)';

    /*
     * Validasi ukuran file.
     */
    if (file.size > maxSize) {
        previewDiv.classList.add('d-none');

        alert('Ukuran file maksimal 2 MB.');

        input.value = '';
        label.innerText = 'Pilih file...';
        previewName.innerText = '';

        return;
    }

    /*
     * Tampilkan container preview.
     */
    previewDiv.classList.remove('d-none');

    /*
     * Preview hanya untuk file gambar.
     */
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();

        reader.onload = function (e) {
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
        };

        reader.readAsDataURL(file);
    } else {
        /*
         * Untuk PDF, sembunyikan preview gambar.
         */
        previewImg.removeAttribute('src');
        previewImg.style.display = 'none';
    }
}


/* =========================================================
   DOCUMENT READY
   ========================================================= */

$(function () {

    /*
     * Reset form ketika modal pembayaran ditutup.
     */
    $('#batchPaymentModal').on('hidden.bs.modal', function () {
        const form = document.getElementById('paymentForm');
        const preview = document.getElementById('filePreview');
        const previewImg = document.getElementById('previewImage');
        const previewName = document.getElementById('previewFileName');
        const fileLabel = document.querySelector(
            '#paymentFile + .custom-file-label'
        );

        form.reset();

        preview.classList.add('d-none');

        previewImg.removeAttribute('src');
        previewImg.style.display = 'block';

        previewName.innerText = '';

        if (fileLabel) {
            fileLabel.innerText = 'Pilih file...';
        }
    });


    /*
     * Fokus ke tombol close ketika modal QRIS tampil.
     */
    $('#qrisModal').on('shown.bs.modal', function () {
        $('#qrisModal .close').trigger('focus');
    });


    /*
     * Fokus ke jenis pembayaran ketika modal pembayaran tampil.
     */
    $('#batchPaymentModal').on('shown.bs.modal', function () {
        $('#payment_type').trigger('focus');
    });

});