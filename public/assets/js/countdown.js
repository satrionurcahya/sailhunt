/* =========================================================
   Sail & Hunt Chapter I
   File : assets/js/countdown.js
   ========================================================= */

"use strict";

/* =========================================================
   EVENT DATE
   ========================================================= */
const EVENT_DATE = new Date(2026, 8, 26, 7, 0, 0); // 26 September 2026, 07:00 WIB

/* =========================================================
   SELECTOR
   ========================================================= */
const dayEl = document.getElementById("day");
const hourEl = document.getElementById("hour");
const minuteEl = document.getElementById("minute");
const secondEl = document.getElementById("second");

/* =========================================================
   FORMAT NUMBER
   ========================================================= */
function pad(value) {
    return String(value).padStart(2, "0");
}

/* =========================================================
   UPDATE COUNTDOWN
   ========================================================= */
function updateCountdown() {
    const now = new Date();
    const distance = EVENT_DATE - now;

    if (distance <= 0) {
        finishCountdown();
        return;
    }

    const day = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hour = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minute = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const second = Math.floor((distance % (1000 * 60)) / 1000);

    if (dayEl) dayEl.textContent = pad(day);
    if (hourEl) hourEl.textContent = pad(hour);
    if (minuteEl) minuteEl.textContent = pad(minute);
    if (secondEl) secondEl.textContent = pad(second);
}

/* =========================================================
   FINISH
   ========================================================= */
function finishCountdown() {
    if (dayEl) dayEl.textContent = "00";
    if (hourEl) hourEl.textContent = "00";
    if (minuteEl) minuteEl.textContent = "00";
    if (secondEl) secondEl.textContent = "00";

    // Trigger toast kalau ada fungsi showToast
    if (typeof showToast === "function") {
        showToast("Sail & Hunt Chapter I telah dimulai!", "info");
    }
}

/* =========================================================
   START
   ========================================================= */
updateCountdown();
setInterval(updateCountdown, 1000);

/* =========================================================
   OPTIONAL: Hero countdown text (jika ada #heroCountdown)
   ========================================================= */
const heroCounter = document.getElementById("heroCountdown");
if (heroCounter) {
    setInterval(() => {
        const now = new Date();
        const diff = EVENT_DATE - now;
        if (diff <= 0) {
            heroCounter.innerHTML = "EVENT DIMULAI";
            return;
        }
        const d = Math.floor(diff / (1000 * 60 * 60 * 24));
        const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        heroCounter.innerHTML = `<strong>${pad(d)}</strong> Hari <strong>${pad(h)}</strong> Jam`;
    }, 1000);
}