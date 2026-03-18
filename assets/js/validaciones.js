/* =========================
   VALIDACIONES DEL SISTEMA
   Maneja:
   - Registro
   - Login
   - Validaciones reutilizables
========================= */

/* =========================
   FUNCIONES BASE
========================= */

/* Validar email */
function esEmailValido(email) {
    const regex = /^[^@]+@[^@]+\.[a-zA-Z]{2,}$/;
    return regex.test(email);
}

/* Validar cédula (simple RD) */
function esCedulaValida(cedula) {
    return cedula.length >= 10;
}

/* Mostrar error visual */
function mostrarError(mensaje) {
    alert(mensaje); // luego lo podemos mejorar a UI
}

/* =========================
   VALIDAR REGISTRO
========================= */
function validarRegistro() {

    const nombre = document.querySelector("[name='nombre']")?.value.trim();
    const correo = document.querySelector("[name='correo']")?.value.trim();
    const telefono = document.querySelector("[name='telefono']")?.value.trim();
    const cedula = document.querySelector("[name='cedula']")?.value.trim();
    const password = document.querySelector("[name='password']")?.value;

    /* Campos obligatorios */
    if (!nombre || !correo || !telefono || !cedula || !password) {
        mostrarError("Todos los campos son obligatorios");
        return false;
    }

    /* Email */
    if (!esEmailValido(correo)) {
        mostrarError("Correo inválido");
        return false;
    }

    /* Cédula */
    if (!esCedulaValida(cedula)) {
        mostrarError("Cédula inválida");
        return false;
    }

    /* Teléfono */
    if (telefono.length < 7) {
        mostrarError("Teléfono inválido");
        return false;
    }

    /* Contraseña */
    if (password.length < 6) {
        mostrarError("La contraseña debe tener al menos 6 caracteres");
        return false;
    }

    return true;
}

/* =========================
   VALIDAR LOGIN
========================= */
function validarLogin() {

    const correo = document.querySelector("[name='correo']")?.value.trim();
    const password = document.querySelector("[name='password']")?.value;

    if (!correo || !password) {
        mostrarError("Debe completar todos los campos");
        return false;
    }

    if (!esEmailValido(correo)) {
        mostrarError("Correo inválido");
        return false;
    }

    return true;
}