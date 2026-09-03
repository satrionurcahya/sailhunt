/* =========================================================
   Sail & Hunt Chapter I
   File : assets/js/app.js
   ========================================================= */

"use strict";

/* =========================================================
   SELECTOR
   ========================================================= */
const $ = (el) => document.querySelector(el);
const $$ = (el) => document.querySelectorAll(el);

/* =========================================================
   NAVBAR SCROLL
   ========================================================= */
const navbar = $("#navbar");

function navbarScroll() {
    if (!navbar) return;
    if (window.scrollY > 60) {
        navbar.classList.add("scrolled");
    } else {
        navbar.classList.remove("scrolled");
    }
}

window.addEventListener("scroll", navbarScroll);

/* =========================================================
   MOBILE MENU
   ========================================================= */
const navToggle = $(".nav-toggle");
const navMenu = $(".nav-menu");

if (navToggle) {
    navToggle.addEventListener("click", () => {
        navMenu.classList.toggle("show");
    });
}

/* =========================================================
   CLOSE MENU AFTER CLICK
   ========================================================= */
$$(".nav-menu a").forEach((item) => {
    item.addEventListener("click", () => {
        navMenu.classList.remove("show");
    });
});

/* =========================================================
   SMOOTH SCROLL
   ========================================================= */
$$('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
        const target = document.querySelector(this.getAttribute("href"));
        if (!target) return;
        e.preventDefault();
        target.scrollIntoView({ behavior: "smooth", block: "start" });
    });
});

/* =========================================================
   FAQ ACCORDION
   ========================================================= */
$$(".faq-item").forEach((item) => {
    const header = item.querySelector(".faq-header");
    header.addEventListener("click", () => {
        if (item.classList.contains("active")) {
            item.classList.remove("active");
            return;
        }
        $$(".faq-item").forEach((i) => i.classList.remove("active"));
        item.classList.add("active");
    });
});

/* =========================================================
   ACCORDION (general)
   ========================================================= */
$$(".accordion").forEach((item) => {
    const head = item.querySelector(".accordion-header");
    if (!head) return;
    head.addEventListener("click", () => {
        item.classList.toggle("active");
    });
});

/* =========================================================
   TOAST – PERBAIKAN UI/UX: WARNA BERBEDA PER TIPE
   ========================================================= */
window.showToast = function (message, type = "success") {
    let toast = $("#toast");
    if (!toast) {
        toast = document.createElement("div");
        toast.id = "toast";
        toast.className = "toast";
        document.body.appendChild(toast);
    }

    let icon = "fa-circle-check";
    let bgColor = "#16a34a"; // hijau sukses

    switch (type) {
        case "danger":
            icon = "fa-circle-xmark";
            bgColor = "#dc2626"; // merah
            break;
        case "warning":
            icon = "fa-circle-exclamation";
            bgColor = "#d97706"; // kuning/oranye
            break;
        case "info":
            icon = "fa-circle-info";
            bgColor = "#2563eb"; // biru
            break;
        default:
            icon = "fa-circle-check";
            bgColor = "#16a34a"; // hijau
            break;
    }

    toast.innerHTML = `<i class="fa-solid ${icon}"></i><span>${message}</span>`;
    toast.style.background = bgColor;
    toast.style.color = "#fff";
    toast.style.boxShadow = "0 10px 30px rgba(0,0,0,0.2)";
    toast.classList.add("show");
    clearTimeout(window.toastTimer);
    window.toastTimer = setTimeout(() => {
        toast.classList.remove("show");
    }, 3500);
};

/* =========================================================
   MODAL
   ========================================================= */
window.openModal = function (id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.add("show");
};

window.closeModal = function (id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.remove("show");
};

$$(".modal").forEach((modal) => {
    modal.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.classList.remove("show");
        }
    });
});

/* =========================================================
   COUNTER ANIMATION
   ========================================================= */
function animateCounter(el) {
    const target = parseInt(el.dataset.target);
    if (isNaN(target)) return;
    let value = 0;
    const speed = Math.max(10, target / 120);
    const timer = setInterval(() => {
        value += Math.ceil(speed);
        if (value >= target) {
            value = target;
            clearInterval(timer);
        }
        el.innerHTML = value;
    }, 20);
}

const counterObserver = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                counterObserver.unobserve(entry.target);
            }
        });
    },
    { threshold: 0.4 }
);

$$("[data-target]").forEach((item) => {
    counterObserver.observe(item);
});

/* =========================================================
   REVEAL ANIMATION
   ========================================================= */
const revealObserver = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("fade-in");
                revealObserver.unobserve(entry.target);
            }
        });
    },
    { threshold: 0.15 }
);

$$("section").forEach((sec) => {
    revealObserver.observe(sec);
});

/* =========================================================
   RIPPLE BUTTON
   ========================================================= */
$$(".btn").forEach((btn) => {
    btn.addEventListener("click", function (e) {
        const circle = document.createElement("span");
        const size = Math.max(this.clientWidth, this.clientHeight);
        circle.style.width = size + "px";
        circle.style.height = size + "px";
        circle.style.left = e.offsetX - size / 2 + "px";
        circle.style.top = e.offsetY - size / 2 + "px";
        circle.style.position = "absolute";
        circle.style.borderRadius = "50%";
        circle.style.background = "rgba(255,255,255,.35)";
        circle.style.transform = "scale(0)";
        circle.style.animation = "ripple .6s linear";
        circle.style.pointerEvents = "none";
        this.appendChild(circle);
        setTimeout(() => circle.remove(), 600);
    });
});

/* =========================================================
   RIPPLE STYLE (dinamis)
   ========================================================= */
const style = document.createElement("style");
style.innerHTML = `
@keyframes ripple {
    from { transform: scale(0); opacity: 1; }
    to { transform: scale(4); opacity: 0; }
}
.nav-menu.show {
    display: flex;
    position: absolute;
    left: 20px;
    right: 20px;
    top: 85px;
    background: #0D4A85;
    padding: 20px;
    border-radius: 20px;
    flex-direction: column;
    box-shadow: 0 25px 60px rgba(0,0,0,.25);
    animation: fadeIn .3s;
}
`;
document.head.appendChild(style);

/* =========================================================
   PRELOADER
   ========================================================= */
window.addEventListener("load", () => {
    document.body.classList.add("loaded");
});

/* =========================================================
   PAGE READY
   ========================================================= */
document.addEventListener("DOMContentLoaded", () => {
    navbarScroll();
    console.log("Sail & Hunt Chapter I Loaded");
});

/* =========================================================
   HERO PARTICLES
   ========================================================= */
(function() {
    const container = document.getElementById('heroParticles');
    if (!container) return;

    for (let i = 0; i < 25; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 8 + 's';
        particle.style.animationDuration = (6 + Math.random() * 6) + 's';
        container.appendChild(particle);
    }
})();

/* =========================================================
   PERBAIKAN UI/UX: LOADING SPINNER UNTUK UPLOAD
   ========================================================= */
document.addEventListener('DOMContentLoaded', function() {
    // Semua form dengan class .upload-form akan otomatis disable tombol submit
    document.querySelectorAll('.upload-form, form[enctype="multipart/form-data"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                const originalHtml = btn.innerHTML;
                btn.dataset.originalHtml = originalHtml;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengunggah...';
                // Kembalikan setelah 30 detik (fallback jika terjadi error)
                setTimeout(function() {
                    if (btn.disabled) {
                        btn.disabled = false;
                        btn.innerHTML = btn.dataset.originalHtml || 'Submit';
                    }
                }, 30000);
            }
        });
    });
});