/* =========================
   MAIN.JS
   Controla:
   - Header dinámico avanzado
   - Optimización de scroll
========================= */

/* =========================
   SCROLL OPTIMIZADO (PERFORMANCE)
========================= */
let lastScroll = 0;

window.addEventListener("scroll", () => {

    const header = document.getElementById("header");
    const currentScroll = window.scrollY;

    /* =========================
       CAMBIO DE ESTILO HEADER
    ========================= */
    if (currentScroll > 80) {
        header.classList.add("scrolled");
    } else {
        header.classList.remove("scrolled");
    }

    /* =========================
       OCULTAR / MOSTRAR HEADER (EFECTO PRO)
    ========================= */
    if (currentScroll > lastScroll && currentScroll > 150) {
        // Scroll hacia abajo → ocultar
        header.style.transform = "translateY(-100%)";
    } else {
        // Scroll hacia arriba → mostrar
        header.style.transform = "translateY(0)";
    }

    lastScroll = currentScroll;

});