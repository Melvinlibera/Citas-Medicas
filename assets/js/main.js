/**
 * MAIN.JS - LÓGICA PRINCIPAL DEL SITIO
 * 
 * Funcionalidad:
 * - Manejo dinámico del header con efecto de scroll
 * - Logo se desplaza de centro a izquierda al hacer scroll
 * - Cambio de estilos del header según la posición del scroll
 * - Optimización de performance con throttling
 * 
 * Autor: Hospital & Human Development Team XD
 * 
 */

/* =========================
   VARIABLES GLOBALES
========================= */
let lastScroll = 0;
let ticking = false;
const SCROLL_THRESHOLD = 80;  // Umbral para cambiar estilos
const HIDE_THRESHOLD = 150;   // Umbral para ocultar header

/* =========================
   FUNCIÓN DE THROTTLE
   Optimiza el rendimiento limitando la frecuencia de ejecución
========================= */
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    }
}

/* =========================
   FUNCIÓN PRINCIPAL DE SCROLL
   Maneja todos los efectos del header al hacer scroll
========================= */
function handleScroll() {
    const header = document.getElementById("header");
    const currentScroll = window.scrollY;

    if (!header) return; // Validación de seguridad

    /* =========================
       EFECTO 1: CAMBIO DE ESTILO DEL HEADER
       Al pasar el umbral, el header cambia de fondo transparente a azul marino
    ========================= */
    if (currentScroll > SCROLL_THRESHOLD) {
        header.classList.add("scrolled");
    } else {
        header.classList.remove("scrolled");
    }

    /* =========================
       EFECTO 2: MOSTRAR/OCULTAR HEADER
       Al hacer scroll hacia abajo, el header se oculta
       Al hacer scroll hacia arriba, el header se muestra
    ========================= */
    if (currentScroll > HIDE_THRESHOLD) {
        if (currentScroll > lastScroll) {
            // Scroll hacia abajo → ocultar header
            header.style.transform = "translateY(-100%)";
        } else {
            // Scroll hacia arriba → mostrar header
            header.style.transform = "translateY(0)";
        }
    } else {
        // En la parte superior, siempre mostrar el header
        header.style.transform = "translateY(0)";
    }

    lastScroll = currentScroll;
    ticking = false;
}

/* =========================
   EVENT LISTENER DE SCROLL
   Utiliza requestAnimationFrame para mejor performance
========================= */
window.addEventListener("scroll", () => {
    if (!ticking) {
        window.requestAnimationFrame(handleScroll);
        ticking = true;
    }
}, { passive: true }); // passive: true mejora el rendimiento

/* =========================
   INICIALIZACIÓN AL CARGAR LA PÁGINA
========================= */
document.addEventListener("DOMContentLoaded", function() {
    // Llamar a handleScroll una vez al cargar para aplicar estilos iniciales
    handleScroll();

    // Agregar efecto de suavidad a los enlaces internos
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Inicializar modo claro/oscuro
    const themeToggle = document.getElementById('floatingThemeToggle');

    function applyTheme(theme) {
        document.body.classList.remove('light', 'dark');
        document.body.classList.add(theme);

        if (themeToggle) {
            const icon = themeToggle.querySelector('i');

            if (icon) {
                // Agregar animación de rotación
                icon.style.transform = 'rotate(180deg)';
                setTimeout(() => {
                    icon.className = theme === 'dark' ? 'bx bx-moon' : 'bx bx-sun';
                    icon.style.transform = 'rotate(0deg)';
                }, 150);
            }

            themeToggle.title = theme === 'dark' ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro';
            themeToggle.setAttribute('aria-label', themeToggle.title);
        }
    }

    function loadTheme() {
        const storedTheme = window.localStorage.getItem('hnh-theme');
        const defaultTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        return storedTheme === 'dark' || storedTheme === 'light' ? storedTheme : defaultTheme;
    }

    function setTheme(theme) {
        applyTheme(theme);
        window.localStorage.setItem('hnh-theme', theme);
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const current = loadTheme();
            const next = current === 'dark' ? 'light' : 'dark';
            setTheme(next);
        });
    }

    setTheme(loadTheme());
    console.log("Hospital & Human - Sistema de citas médicas inicializado correctamente");
});

/* =========================
   FUNCIONES DE UTILIDAD
========================= */

/**
 * Función para mostrar notificaciones
 * @param {string} mensaje - El mensaje a mostrar
 * @param {string} tipo - Tipo de notificación: 'success', 'error', 'warning', 'info'
 * @param {number} duracion - Duración en milisegundos (por defecto 3000)
 */
function mostrarNotificacion(mensaje, tipo = 'info', duracion = 3000) {
    const notificacion = document.createElement('div');
    notificacion.className = `notificacion notificacion-${tipo}`;
    notificacion.textContent = mensaje;
    notificacion.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        z-index: 9999;
        animation: slideInRight 0.3s ease-out;
    `;

    // Estilos según el tipo
    const estilos = {
        success: { background: '#d4edda', color: '#155724', border: '1px solid #c3e6cb' },
        error: { background: '#f8d7da', color: '#721c24', border: '1px solid #f5c6cb' },
        warning: { background: '#fff3cd', color: '#856404', border: '1px solid #ffeaa7' },
        info: { background: '#d1ecf1', color: '#0c5460', border: '1px solid #bee5eb' }
    };

    const estilo = estilos[tipo] || estilos.info;
    Object.assign(notificacion.style, estilo);

    document.body.appendChild(notificacion);

    // Remover notificación después de la duración especificada
    setTimeout(() => {
        notificacion.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => notificacion.remove(), 300);
    }, duracion);
}

/**
 * Función para validar email
 * @param {string} email - Email a validar
 * @returns {boolean} - True si es válido, false si no
 */
function esEmailValido(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * Función para validar cédula dominicana
 * @param {string} cedula - Cédula a validar
 * @returns {boolean} - True si es válida, false si no
 */
function esCedulaValida(cedula) {
    // Validación básica: debe tener entre 9 y 11 dígitos
    const regex = /^\d{9,11}$/;
    return regex.test(cedula.replace(/[.-]/g, ''));
}

/**
 * Función para validar teléfono dominicano
 * @param {string} telefono - Teléfono a validar
 * @returns {boolean} - True si es válido, false si no
 */
function esTelefonoValido(telefono) {
    // Validación básica: debe tener entre 7 y 15 dígitos
    const regex = /^\d{7,15}$/;
    return regex.test(telefono.replace(/[.\-\s()]/g, ''));
}

/**
 * Función para formatear moneda
 * @param {number} cantidad - Cantidad a formatear
 * @returns {string} - Cantidad formateada
 */
function formatearMoneda(cantidad) {
    return new Intl.NumberFormat('es-DO', {
        style: 'currency',
        currency: 'DOP'
    }).format(cantidad);
}

/* =========================
   ANIMACIONES CSS DINÁMICAS
========================= */
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100px);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);

/* =========================
   EXPORTAR FUNCIONES
   Para uso en otros scripts
========================= */
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        mostrarNotificacion,
        esEmailValido,
        esCedulaValida,
        esTelefonoValido,
        formatearMoneda
    };
}
