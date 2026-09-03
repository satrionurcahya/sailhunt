/* =========================================================
   Sail & Hunt Chapter I
   Registration Wizard
   File: public/assets/js/register.js
   ========================================================= */

"use strict";


document.addEventListener("DOMContentLoaded", () => {


    /* =========================================================
       CONFIGURATION
    ========================================================= */

    const CONFIG = {
        TOTAL_STEP: 5,

        PASSWORD: {
            MIN_LENGTH: 8,

            REGEX:
                /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=\[\]{};:'",.<>/?\\|`~]).{8,}$/
        }
    };


    /* =========================================================
       HELPER
    ========================================================= */

    const $ = (selector) => {
        return document.querySelector(selector);
    };


    const $$ = (selector) => {
        return document.querySelectorAll(selector);
    };


    /* =========================================================
       FORM
    ========================================================= */

    const form = $("#registerForm");

    const steps = $$(".form-step");

    const wizardSteps = $$(".wizard-step");

    const progressBar = $("#wizardProgress");


    /* =========================================================
       NAVIGATION
    ========================================================= */

    const prevBtn = $("#prevStepBtn");

    const backBtn = $("#backHomeBtn");

    const nextBtn = $("#nextStepBtn");

    const submitBtn = $("#submitRegister");


    /* =========================================================
       STEP 1
    ========================================================= */

    const levelInputs = $$(
        "input[name='level']"
    );


    /* =========================================================
       STEP 2
    ========================================================= */

    const schoolName = $("#school_name");

    const address = $(
        "[name='address']"
    );

    const city = $(
        "[name='city']"
    );

    const postalCode = $(
        "[name='postal_code']"
    );


    /* =========================================================
       STEP 3
    ========================================================= */

    const coachName = $(
        "[name='coach_name']"
    );

    const trainerName = $(
        "[name='trainer_name']"
    );

    const commanderName = $(
        "[name='commander_name']"
    );


    /* =========================================================
       STEP 4
    ========================================================= */

    const email = $(
        "[name='email']"
    );

    const username = $(
        "[name='username']"
    );

    const password = $(
        "[name='password']"
    );

    const confirmPassword = $(
        "[name='password_confirmation']"
    );

    const agreement = $(
        "#agreement"
    );


    /* =========================================================
       REVIEW
    ========================================================= */

    const review = {

        level: $("#reviewLevel"),

        school: $("#reviewSchool"),

        address: $("#reviewAddress"),

        city: $("#reviewCity"),

        postal: $("#reviewPostal"),

        coach: $("#reviewCoach"),

        trainer: $("#reviewTrainer"),

        commander: $("#reviewCommander"),

        email: $("#reviewEmail"),

        username: $("#reviewUsername")
    };


    /* =========================================================
       STATE
    ========================================================= */

    const state = {

        currentStep: 1,

        submitting: false

    };


    /* =========================================================
       TOAST
    ========================================================= */

    function toast(
        message,
        type = "info"
    ) {

        /*
         * Jika project memiliki showToast(),
         * gunakan fungsi tersebut.
         */

        if (
            typeof window.showToast ===
            "function"
        ) {

            window.showToast(
                message,
                type
            );

            return;

        }


        /*
         * Fallback jika showToast tidak tersedia.
         */

        console.log(
            `[${type}] ${message}`
        );

    }


    /* =========================================================
       TEXT HELPER
    ========================================================= */

    function isEmpty(value) {

        return String(
            value || ""
        )
        .trim() === "";

    }


    function focusElement(element) {

        if (!element) {
            return;
        }

        element.focus();

    }


    /* =========================================================
       VALIDATION CLASS
    ========================================================= */

    function markInvalid(element) {

        if (!element) {
            return;
        }

        element.classList.add(
            "is-invalid"
        );

        element.classList.remove(
            "is-valid"
        );

    }


    function markValid(element) {

        if (!element) {
            return;
        }

        element.classList.add(
            "is-valid"
        );

        element.classList.remove(
            "is-invalid"
        );

    }


    function clearValidation(element) {

        if (!element) {
            return;
        }

        element.classList.remove(
            "is-invalid",
            "is-valid"
        );

    }


    /* =========================================================
       SCHOOL NAME
    =========================================================
    
    Nama sekolah:
    
    - Input biasa
    - Otomatis kapital
    - Tidak menggunakan dropdown
    - Tidak menggunakan Tom Select
    */

    if (schoolName) {

        schoolName.addEventListener(
            "input",
            function () {

                this.value =
                    this.value.toUpperCase();

            }
        );

    }


    /* =========================================================
       POSTAL CODE
    ========================================================= */

    if (postalCode) {

        postalCode.addEventListener(
            "input",
            function () {

                /*
                 * Hanya angka
                 */

                this.value =
                    this.value
                        .replace(/\D/g, "")
                        .substring(0, 5);

            }
        );

    }


    /* =========================================================
       GET SELECTED LEVEL
    ========================================================= */

    function getSelectedLevel() {

        const selected =
            document.querySelector(
                "input[name='level']:checked"
            );


        if (!selected) {

            return "";

        }


        return selected.value;

    }


    /* =========================================================
       EMAIL VALIDATION
    ========================================================= */

    function isValidEmail(value) {

        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/
            .test(value);

    }


    /* =========================================================
       PASSWORD VALIDATION
    ========================================================= */

    function isValidPassword(value) {

        return CONFIG.PASSWORD.REGEX
            .test(value);

    }


    /* =========================================================
       STEP 1 VALIDATION
    ========================================================= */

    function validateStep1() {

        const selectedLevel =
            getSelectedLevel();


        if (!selectedLevel) {

            toast(
                "Silakan pilih tingkat PMR.",
                "warning"
            );

            return false;

        }


        return true;

    }


    /* =========================================================
       STEP 2 VALIDATION
    ========================================================= */

    function validateStep2() {


        /*
         * Reset validation
         */

        clearValidation(
            schoolName
        );

        clearValidation(
            address
        );

        clearValidation(
            city
        );

        clearValidation(
            postalCode
        );


        /* -----------------------------------------------------
           NAMA SEKOLAH
        ----------------------------------------------------- */

        if (
            !schoolName ||
            isEmpty(
                schoolName.value
            )
        ) {

            markInvalid(
                schoolName
            );

            focusElement(
                schoolName
            );

            toast(
                "Nama sekolah wajib diisi.",
                "warning"
            );

            return false;

        }


        /*
         * Pastikan nama sekolah kapital
         */

        schoolName.value =
            schoolName.value
                .trim()
                .toUpperCase();


        markValid(
            schoolName
        );


        /* -----------------------------------------------------
           ALAMAT
        ----------------------------------------------------- */

        if (
            !address ||
            isEmpty(
                address.value
            )
        ) {

            markInvalid(
                address
            );

            focusElement(
                address
            );

            toast(
                "Alamat sekolah wajib diisi.",
                "warning"
            );

            return false;

        }


        markValid(
            address
        );


        /* -----------------------------------------------------
           KABUPATEN / KOTA
        ----------------------------------------------------- */

        if (
            !city ||
            isEmpty(
                city.value
            )
        ) {

            markInvalid(
                city
            );

            focusElement(
                city
            );

            toast(
                "Silakan pilih Kabupaten/Kota.",
                "warning"
            );

            return false;

        }


        markValid(
            city
        );


        /* -----------------------------------------------------
           KODE POS
        ----------------------------------------------------- */

        if (
            !postalCode ||
            !/^\d{5}$/.test(
                postalCode.value
            )
        ) {

            markInvalid(
                postalCode
            );

            focusElement(
                postalCode
            );

            toast(
                "Kode Pos harus terdiri dari 5 digit.",
                "warning"
            );

            return false;

        }


        markValid(
            postalCode
        );


        return true;

    }


    /* =========================================================
       STEP 3 VALIDATION
    ========================================================= */

    function validateStep3() {


        clearValidation(
            coachName
        );

        clearValidation(
            trainerName
        );

        clearValidation(
            commanderName
        );


        /* -----------------------------------------------------
           PEMBINA
        ----------------------------------------------------- */

        if (
            !coachName ||
            isEmpty(
                coachName.value
            )
        ) {

            markInvalid(
                coachName
            );

            focusElement(
                coachName
            );

            toast(
                "Nama pembina wajib diisi.",
                "warning"
            );

            return false;

        }


        markValid(
            coachName
        );


        /* -----------------------------------------------------
           PELATIH
        ----------------------------------------------------- */

        if (
            !trainerName ||
            isEmpty(
                trainerName.value
            )
        ) {

            markInvalid(
                trainerName
            );

            focusElement(
                trainerName
            );

            toast(
                "Nama pelatih / fasilitator wajib diisi.",
                "warning"
            );

            return false;

        }


        markValid(
            trainerName
        );


        /* -----------------------------------------------------
           KOMANDAN
        ----------------------------------------------------- */

        if (
            !commanderName ||
            isEmpty(
                commanderName.value
            )
        ) {

            markInvalid(
                commanderName
            );

            focusElement(
                commanderName
            );

            toast(
                "Nama komandan wajib diisi.",
                "warning"
            );

            return false;

        }


        markValid(
            commanderName
        );


        return true;

    }


    /* =========================================================
       STEP 4 VALIDATION
    ========================================================= */

    function validateStep4() {


        clearValidation(
            email
        );

        clearValidation(
            username
        );

        clearValidation(
            password
        );

        clearValidation(
            confirmPassword
        );


        /* -----------------------------------------------------
           EMAIL
        ----------------------------------------------------- */

        if (
            !email ||
            isEmpty(
                email.value
            )
        ) {

            markInvalid(
                email
            );

            focusElement(
                email
            );

            toast(
                "Email wajib diisi.",
                "warning"
            );

            return false;

        }


        if (
            !isValidEmail(
                email.value.trim()
            )
        ) {

            markInvalid(
                email
            );

            focusElement(
                email
            );

            toast(
                "Format email tidak valid.",
                "warning"
            );

            return false;

        }


        markValid(
            email
        );


        /* -----------------------------------------------------
           USERNAME
        ----------------------------------------------------- */

        if (
            !username ||
            username.value.trim().length < 4
        ) {

            markInvalid(
                username
            );

            focusElement(
                username
            );

            toast(
                "Username minimal 4 karakter.",
                "warning"
            );

            return false;

        }


        markValid(
            username
        );


        /* -----------------------------------------------------
           PASSWORD
        ----------------------------------------------------- */

        if (
            !password ||
            isEmpty(
                password.value
            )
        ) {

            markInvalid(
                password
            );

            focusElement(
                password
            );

            toast(
                "Password wajib diisi.",
                "warning"
            );

            return false;

        }


        if (
            !isValidPassword(
                password.value
            )
        ) {

            markInvalid(
                password
            );

            focusElement(
                password
            );

            toast(
                "Password minimal 8 karakter dan harus memiliki huruf besar, angka, serta simbol.",
                "warning"
            );

            return false;

        }


        markValid(
            password
        );


        /* -----------------------------------------------------
           KONFIRMASI PASSWORD
        ----------------------------------------------------- */

        if (
            !confirmPassword ||
            isEmpty(
                confirmPassword.value
            )
        ) {

            markInvalid(
                confirmPassword
            );

            focusElement(
                confirmPassword
            );

            toast(
                "Konfirmasi password wajib diisi.",
                "warning"
            );

            return false;

        }


        if (
            password.value !==
            confirmPassword.value
        ) {

            markInvalid(
                confirmPassword
            );

            focusElement(
                confirmPassword
            );

            toast(
                "Konfirmasi password tidak cocok.",
                "warning"
            );

            return false;

        }


        markValid(
            confirmPassword
        );


        /* -----------------------------------------------------
           AGREEMENT
        ----------------------------------------------------- */

        if (
            !agreement ||
            !agreement.checked
        ) {

            toast(
                "Anda harus menyetujui syarat dan ketentuan.",
                "warning"
            );

            return false;

        }


        return true;

    }


    /* =========================================================
       VALIDATE CURRENT STEP
    ========================================================= */

    function validateCurrentStep() {

        switch (
            state.currentStep
        ) {

            case 1:

                return validateStep1();


            case 2:

                return validateStep2();


            case 3:

                return validateStep3();


            case 4:

                return validateStep4();


            default:

                return true;

        }

    }


    /* =========================================================
       UPDATE PROGRESS
    ========================================================= */

    function updateProgress() {

        if (!progressBar) {

            return;

        }


        const percentage =
            (
                state.currentStep /
                CONFIG.TOTAL_STEP
            ) * 100;


        progressBar.style.width =
            `${percentage}%`;

    }


    /* =========================================================
       UPDATE WIZARD INDICATOR
    ========================================================= */

    function updateWizard() {

        wizardSteps.forEach(
            (step, index) => {

                const stepNumber =
                    index + 1;


                step.classList.remove(
                    "active",
                    "completed"
                );


                if (
                    stepNumber <
                    state.currentStep
                ) {

                    step.classList.add(
                        "completed"
                    );

                }


                if (
                    stepNumber ===
                    state.currentStep
                ) {

                    step.classList.add(
                        "active"
                    );

                }

            }
        );

    }


    /* =========================================================
       UPDATE NAVIGATION
    ========================================================= */

    function updateNavigation() {

        if (!prevBtn) {
            return;
        }

        if (!backBtn) {
            return;
        }

        if (!nextBtn) {
            return;
        }

        if (!submitBtn) {
            return;
        }


        /*
         * Sembunyikan semua terlebih dahulu
         */

        prevBtn.style.display =
            "none";

        backBtn.style.display =
            "none";

        nextBtn.style.display =
            "none";

        submitBtn.style.display =
            "none";


        /* -----------------------------------------------------
           STEP 1
        ----------------------------------------------------- */

        if (
            state.currentStep === 1
        ) {

            backBtn.style.display =
                "inline-flex";

            nextBtn.style.display =
                "inline-flex";

        }


        /* -----------------------------------------------------
           STEP 5
        ----------------------------------------------------- */

        else if (
            state.currentStep ===
            CONFIG.TOTAL_STEP
        ) {

            prevBtn.style.display =
                "inline-flex";

            submitBtn.style.display =
                "inline-flex";

        }


        /* -----------------------------------------------------
           STEP 2 - 4
        ----------------------------------------------------- */

        else {

            prevBtn.style.display =
                "inline-flex";

            nextBtn.style.display =
                "inline-flex";

        }

    }


    /* =========================================================
       UPDATE REVIEW
    ========================================================= */

    function updateReview() {


        /* -----------------------------------------------------
           LEVEL
        ----------------------------------------------------- */

        if (review.level) {

            review.level.textContent =
                getSelectedLevel() ||
                "-";

        }


        /* -----------------------------------------------------
           SEKOLAH
        ----------------------------------------------------- */

        if (review.school) {

            review.school.textContent =
                schoolName?.value ||
                "-";

        }


        /* -----------------------------------------------------
           ALAMAT
        ----------------------------------------------------- */

        if (review.address) {

            review.address.textContent =
                address?.value ||
                "-";

        }


        /* -----------------------------------------------------
           KOTA
        ----------------------------------------------------- */

        if (review.city) {

            if (
                city &&
                city.selectedIndex >= 0
            ) {

                review.city.textContent =
                    city.options[
                        city.selectedIndex
                    ]?.text ||
                    "-";

            } else {

                review.city.textContent =
                    "-";

            }

        }


        /* -----------------------------------------------------
           KODE POS
        ----------------------------------------------------- */

        if (review.postal) {

            review.postal.textContent =
                postalCode?.value ||
                "-";

        }


        /* -----------------------------------------------------
           PEMBINA
        ----------------------------------------------------- */

        if (review.coach) {

            review.coach.textContent =
                coachName?.value ||
                "-";

        }


        /* -----------------------------------------------------
           PELATIH
        ----------------------------------------------------- */

        if (review.trainer) {

            review.trainer.textContent =
                trainerName?.value ||
                "-";

        }


        /* -----------------------------------------------------
           KOMANDAN
        ----------------------------------------------------- */

        if (review.commander) {

            review.commander.textContent =
                commanderName?.value ||
                "-";

        }


        /* -----------------------------------------------------
           EMAIL
        ----------------------------------------------------- */

        if (review.email) {

            review.email.textContent =
                email?.value ||
                "-";

        }


        /* -----------------------------------------------------
           USERNAME
        ----------------------------------------------------- */

        if (review.username) {

            review.username.textContent =
                username?.value ||
                "-";

        }

    }


    /* =========================================================
       SHOW STEP
    ========================================================= */

    function showStep(stepNumber) {


        if (
            stepNumber < 1 ||
            stepNumber >
            CONFIG.TOTAL_STEP
        ) {

            return;

        }


        /*
         * Sembunyikan semua step
         */

        steps.forEach(
            step => {

                step.classList.remove(
                    "active"
                );

            }
        );


        /*
         * Tampilkan step tujuan
         */

        const targetStep =
            document.querySelector(
                `.form-step[data-step="${stepNumber}"]`
            );


        if (targetStep) {

            targetStep.classList.add(
                "active"
            );

        }


        /*
         * Update state
         */

        state.currentStep =
            stepNumber;


        /*
         * Update UI
         */

        updateWizard();

        updateProgress();

        updateNavigation();


        /*
         * Update review
         */

        if (
            stepNumber === 5
        ) {

            updateReview();

        }


        /*
         * Scroll ke atas
         */

        window.scrollTo({

            top: 0,

            behavior: "smooth"

        });

    }


    /* =========================================================
       NEXT BUTTON
    ========================================================= */

    if (nextBtn) {

        nextBtn.addEventListener(
            "click",
            () => {


                /*
                 * Validasi step sekarang
                 */

                if (
                    !validateCurrentStep()
                ) {

                    return;

                }


                /*
                 * Pindah ke step berikutnya
                 */

                showStep(
                    state.currentStep + 1
                );

            }
        );

    }


    /* =========================================================
       PREVIOUS BUTTON
    ========================================================= */

    if (prevBtn) {

        prevBtn.addEventListener(
            "click",
            () => {

                showStep(
                    state.currentStep - 1
                );

            }
        );

    }


    /* =========================================================
       RADIO CARD
    ========================================================= */

    $$(".radio-card").forEach(
        card => {

            card.addEventListener(
                "click",
                () => {


                    /*
                     * Hapus active dari card lain
                     */

                    $$(".radio-card")
                        .forEach(
                            otherCard => {

                                otherCard.classList.remove(
                                    "active"
                                );

                            }
                        );


                    /*
                     * Aktifkan card yang dipilih
                     */

                    card.classList.add(
                        "active"
                    );


                    /*
                     * Pastikan radio checked
                     */

                    const input =
                        card.querySelector(
                            "input[type='radio']"
                        );


                    if (input) {

                        input.checked =
                            true;

                    }

                }
            );

        }
    );


    /* =========================================================
       RADIO CHANGE
    ========================================================= */

    levelInputs.forEach(
        input => {

            input.addEventListener(
                "change",
                () => {


                    $$(".radio-card")
                        .forEach(
                            card => {

                                const radio =
                                    card.querySelector(
                                        "input[name='level']"
                                    );


                                if (
                                    radio &&
                                    radio.checked
                                ) {

                                    card.classList.add(
                                        "active"
                                    );

                                } else {

                                    card.classList.remove(
                                        "active"
                                    );

                                }

                            }
                        );

                }
            );

        }
    );


    /* =========================================================
       FORM SUBMIT
    ========================================================= */

    if (form) {

        form.addEventListener(
            "submit",
            function (event) {


                /*
                 * Jangan submit dua kali
                 */

                if (
                    state.submitting
                ) {

                    event.preventDefault();

                    return;

                }


                /*
                 * Pastikan Step 4 valid
                 */

                if (
                    !validateStep4()
                ) {

                    event.preventDefault();

                    return;

                }


                /*
                 * Pastikan nama sekolah kapital
                 */

                if (schoolName) {

                    schoolName.value =
                        schoolName.value
                            .trim()
                            .toUpperCase();

                }


                /*
                 * Tandai sedang submit
                 */

                state.submitting =
                    true;


                /*
                 * Ubah tombol submit
                 */

                if (submitBtn) {

                    submitBtn.disabled =
                        true;

                    submitBtn.innerHTML = `
                        <i class="fas fa-spinner fa-spin"></i>
                        Mendaftarkan...
                    `;

                }

            }
        );

    }


    /* =========================================================
       PASSWORD CONFIRMATION LIVE CHECK
    ========================================================= */

    if (
        password &&
        confirmPassword
    ) {

        confirmPassword.addEventListener(
            "input",
            () => {

                if (
                    isEmpty(
                        confirmPassword.value
                    )
                ) {

                    clearValidation(
                        confirmPassword
                    );

                    return;

                }


                if (
                    password.value ===
                    confirmPassword.value
                ) {

                    markValid(
                        confirmPassword
                    );

                } else {

                    markInvalid(
                        confirmPassword
                    );

                }

            }
        );

    }


    /* =========================================================
       PASSWORD LIVE CHECK
    ========================================================= */

    if (password) {

        password.addEventListener(
            "input",
            () => {

                if (
                    isEmpty(
                        password.value
                    )
                ) {

                    clearValidation(
                        password
                    );

                    return;

                }


                if (
                    isValidPassword(
                        password.value
                    )
                ) {

                    markValid(
                        password
                    );

                } else {

                    markInvalid(
                        password
                    );

                }

            }
        );

    }


    /* =========================================================
       EMAIL LIVE CHECK
    ========================================================= */

    if (email) {

        email.addEventListener(
            "input",
            () => {

                if (
                    isEmpty(
                        email.value
                    )
                ) {

                    clearValidation(
                        email
                    );

                    return;

                }


                if (
                    isValidEmail(
                        email.value.trim()
                    )
                ) {

                    markValid(
                        email
                    );

                } else {

                    markInvalid(
                        email
                    );

                }

            }
        );

    }


    /* =========================================================
       INITIALIZATION
    ========================================================= */

    /*
     * Pastikan card level sesuai dengan old value.
     */

    levelInputs.forEach(
        input => {

            const card =
                input.closest(
                    ".radio-card"
                );


            if (!card) {
                return;
            }


            if (input.checked) {

                card.classList.add(
                    "active"
                );

            } else {

                card.classList.remove(
                    "active"
                );

            }

        }
    );


    /*
     * Pastikan nama sekolah yang berasal
     * dari old input juga kapital.
     */

    if (schoolName) {

        schoolName.value =
            schoolName.value
                .toUpperCase();

    }


    /*
     * Mulai dari Step 1
     */

    showStep(1);


});