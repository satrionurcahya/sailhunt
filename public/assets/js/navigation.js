/**
 * ============================================================
 * SAIL & HUNT CHAPTER I
 * Navigation / Navbar JavaScript
 * ============================================================
 *
 * Fungsi:
 * 1. Mobile / tablet hamburger menu
 * 2. Membuka dan menutup menu navigasi
 * 3. Dropdown Download
 * 4. Menutup menu ketika klik di luar
 * 5. Menutup menu ketika memilih menu
 * 6. Menutup menu ketika menekan Escape
 * 7. Reset keadaan ketika kembali ke desktop
 * 8. Mengatur aria-expanded untuk accessibility
 *
 * Tidak ada JavaScript navbar di Blade.
 * Semua fungsi navbar dipusatkan di file ini.
 * ============================================================
 */

(function () {

    'use strict';


    /* ========================================================
       DOM READY
       ======================================================== */

    document.addEventListener('DOMContentLoaded', function () {

        /*
         * Ambil elemen navbar.
         */
        const navbar = document.getElementById('navbar');

        /*
         * Ambil tombol hamburger.
         */
        const navToggle = document.getElementById('navToggle');

        /*
         * Ambil menu utama.
         */
        const navMenu = document.getElementById('mainNavMenu');


        /*
         * Jika navbar atau menu tidak ditemukan,
         * hentikan script.
         *
         * Ini membuat file aman digunakan
         * pada halaman yang tidak mempunyai navbar.
         */
        if (!navbar || !navToggle || !navMenu) {
            return;
        }


        /* ====================================================
           KONSTANTA
           ==================================================== */

        /*
         * Breakpoint harus sama dengan CSS responsive.
         *
         * Pada <= 1100px:
         * hamburger aktif.
         *
         * Pada > 1100px:
         * navbar desktop.
         */
        const MOBILE_BREAKPOINT = 1100;


        /* ====================================================
           ELEMEN TAMBAHAN
           ==================================================== */

        /*
         * Semua dropdown di navbar.
         */
        const dropdowns = navbar.querySelectorAll('.nav-dropdown');


        /* ====================================================
           FUNGSI: BUKA MENU MOBILE
           ==================================================== */

        function openMobileMenu() {

            navMenu.classList.add('show');

            navToggle.classList.add('active');

            navToggle.setAttribute(
                'aria-expanded',
                'true'
            );


            /*
             * Ubah icon hamburger menjadi X.
             */
            const icon = navToggle.querySelector('i');

            if (icon) {

                icon.classList.remove('fa-bars');

                icon.classList.add('fa-times');

            }

        }


        /* ====================================================
           FUNGSI: TUTUP MENU MOBILE
           ==================================================== */

        function closeMobileMenu() {

            navMenu.classList.remove('show');

            navToggle.classList.remove('active');

            navToggle.setAttribute(
                'aria-expanded',
                'false'
            );


            /*
             * Kembalikan icon X menjadi hamburger.
             */
            const icon = navToggle.querySelector('i');

            if (icon) {

                icon.classList.remove('fa-times');

                icon.classList.add('fa-bars');

            }

        }


        /* ====================================================
           FUNGSI: TOGGLE MENU MOBILE
           ==================================================== */

        function toggleMobileMenu() {

            const isOpen =
                navMenu.classList.contains('show');


            if (isOpen) {

                closeMobileMenu();

            } else {

                openMobileMenu();

            }

        }


        /* ====================================================
           FUNGSI: TUTUP SEMUA DROPDOWN
           ==================================================== */

        function closeAllDropdowns() {

            dropdowns.forEach(function (dropdown) {

                dropdown.classList.remove('open');


                /*
                 * Cari tombol dropdown.
                 */
                const toggle =
                    dropdown.querySelector(
                        '.nav-dropdown-toggle'
                    );


                if (toggle) {

                    toggle.setAttribute(
                        'aria-expanded',
                        'false'
                    );

                }

            });

        }


        /* ====================================================
           FUNGSI: BUKA DROPDOWN
           ==================================================== */

        function openDropdown(dropdown) {

            /*
             * Tutup dropdown lainnya.
             */
            dropdowns.forEach(function (item) {

                if (item !== dropdown) {

                    item.classList.remove('open');

                    const itemToggle =
                        item.querySelector(
                            '.nav-dropdown-toggle'
                        );

                    if (itemToggle) {

                        itemToggle.setAttribute(
                            'aria-expanded',
                            'false'
                        );

                    }

                }

            });


            /*
             * Buka dropdown yang dipilih.
             */
            dropdown.classList.add('open');


            const toggle =
                dropdown.querySelector(
                    '.nav-dropdown-toggle'
                );


            if (toggle) {

                toggle.setAttribute(
                    'aria-expanded',
                    'true'
                );

            }

        }


        /* ====================================================
           FUNGSI: TOGGLE DROPDOWN
           ==================================================== */

        function toggleDropdown(dropdown) {

            const isOpen =
                dropdown.classList.contains('open');


            if (isOpen) {

                dropdown.classList.remove('open');


                const toggle =
                    dropdown.querySelector(
                        '.nav-dropdown-toggle'
                    );


                if (toggle) {

                    toggle.setAttribute(
                        'aria-expanded',
                        'false'
                    );

                }

            } else {

                openDropdown(dropdown);

            }

        }


        /* ====================================================
           HAMBURGER CLICK
           ==================================================== */

        navToggle.addEventListener(
            'click',
            function (event) {

                /*
                 * Jangan biarkan click menyebar
                 * ke document.
                 */
                event.stopPropagation();

                toggleMobileMenu();

            }
        );


        /* ====================================================
           DROPDOWN CLICK
           ==================================================== */

        dropdowns.forEach(function (dropdown) {

            const toggle =
                dropdown.querySelector(
                    '.nav-dropdown-toggle'
                );


            if (!toggle) {
                return;
            }


            toggle.addEventListener(
                'click',
                function (event) {

                    /*
                     * Link dropdown menggunakan href="#",
                     * jadi cegah browser berpindah
                     * ke bagian atas halaman.
                     */
                    event.preventDefault();

                    event.stopPropagation();


                    toggleDropdown(dropdown);

                }
            );


            /*
             * Mencegah klik di dalam dropdown
             * menutup dropdown secara tidak sengaja.
             */
            const dropdownMenu =
                dropdown.querySelector(
                    '.nav-dropdown-menu'
                );


            if (dropdownMenu) {

                dropdownMenu.addEventListener(
                    'click',
                    function (event) {

                        event.stopPropagation();

                    }
                );

            }

        });


        /* ====================================================
           LINK MENU
           ==================================================== */

        navMenu
            .querySelectorAll(
                'a:not(.nav-dropdown-toggle)'
            )
            .forEach(function (link) {

                link.addEventListener(
                    'click',
                    function () {

                        /*
                         * Tutup dropdown.
                         */
                        closeAllDropdowns();


                        /*
                         * Jika sedang mobile/tablet,
                         * tutup hamburger menu.
                         */
                        if (
                            window.innerWidth <=
                            MOBILE_BREAKPOINT
                        ) {

                            closeMobileMenu();

                        }

                    }
                );

            });


        /* ====================================================
           CLICK DI LUAR NAVBAR
           ==================================================== */

        document.addEventListener(
            'click',
            function (event) {

                /*
                 * Jika klik berada di luar navbar,
                 * tutup dropdown.
                 */
                if (!navbar.contains(event.target)) {

                    closeAllDropdowns();


                    /*
                     * Pada mobile/tablet,
                     * tutup menu utama juga.
                     */
                    if (
                        window.innerWidth <=
                        MOBILE_BREAKPOINT
                    ) {

                        closeMobileMenu();

                    }

                }

            }
        );


        /* ====================================================
           ESCAPE KEY
           ==================================================== */

        document.addEventListener(
            'keydown',
            function (event) {

                if (event.key === 'Escape') {

                    closeAllDropdowns();

                    closeMobileMenu();

                }

            }
        );


        /* ====================================================
           RESIZE WINDOW
           ==================================================== */

        let resizeTimer = null;


        window.addEventListener(
            'resize',
            function () {

                /*
                 * Debounce resize supaya tidak
                 * menjalankan fungsi terlalu sering.
                 */
                clearTimeout(resizeTimer);


                resizeTimer = setTimeout(
                    function () {

                        /*
                         * Jika kembali ke desktop,
                         * reset menu mobile.
                         */
                        if (
                            window.innerWidth >
                            MOBILE_BREAKPOINT
                        ) {

                            closeMobileMenu();

                            closeAllDropdowns();

                        }

                    },
                    100
                );

            }
        );


        /* ====================================================
           INITIAL STATE
           ==================================================== */

        /*
         * Pastikan kondisi awal konsisten.
         */
        navToggle.setAttribute(
            'aria-expanded',
            'false'
        );


        /*
         * Pastikan dropdown awal tertutup.
         */
        closeAllDropdowns();


        /*
         * Jika halaman pertama kali dibuka
         * dalam mode desktop, pastikan menu
         * mobile tidak terbuka.
         */
        if (
            window.innerWidth >
            MOBILE_BREAKPOINT
        ) {

            closeMobileMenu();

        }

    });

})();