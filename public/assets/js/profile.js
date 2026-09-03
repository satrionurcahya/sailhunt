/**
 * =========================================================
 * PROFIL UNIT
 * Page-specific JavaScript
 * ========================================================= */

(function ($) {
    'use strict';


    /* =====================================================
       CUSTOM FILE INPUT
       ===================================================== */

    $(document).on('change', '.custom-file-input', function () {

        const fileName = this.files && this.files.length
            ? this.files[0].name
            : 'Pilih file...';


        $(this)
            .next('.custom-file-label')
            .text(fileName);

    });


})(jQuery);