<?php
/**
 * HEADER PRINCIPAL - COMPONENTE INCLUIBLE
 *
 * Funcionalidad:
 * - Componente de cabecera reutilizable en todas las páginas
 * - Muestra logo de la institución Hospital & Human
 * - Navegación adaptada según estado de sesión del usuario
 * - Menú diferente para: invitados, usuarios regulares, doctores, administradores
 * - Opción de cerrar sesión para usuarios autenticados
 *
 * Estados de navegación:
 *
 * 1. USUARIO NO AUTENTICADO (invitado):
 *    - Inicio, Especialidades, Registrarse, Iniciar Sesión
 *
 * 2. USUARIO REGULAR (paciente):
 *    - Mi Panel, Agendar Cita, Mis Citas, Mi Perfil, Cerrar Sesión
 *
 * 3. DOCTOR:
 *    - Mi Panel, Mis Citas, Mi Perfil, Cerrar Sesión
 *
 * 4. ADMINISTRADOR:
 *    - Dashboard, Doctores, Especialidades, Citas, Usuarios, Cerrar Sesión
 *
 * Elementos visuales:
 * - Logo de la institución (imagen PNG)
 * - Menú de navegación responsive
 * - Estilos CSS personalizados
 * - Efectos hover y transiciones
 *
 * Inclusión en páginas:
 * - require_once 'includes/header.php';
 * - Debe incluirse después de session_start()
 * - Requiere conexión a config/db.php en páginas que lo usen
 *
 * Archivos relacionados:
 * - assets/css/style.css (estilos del header)
 * - assets/img/logo.png (logo de la institución)
 * - auth/logout.php (cierre de sesión)
 *
 * Variables de sesión utilizadas:
 * - $_SESSION['usuario'] (nombre del usuario)
 * - $_SESSION['rol'] (admin, doctor, user)
 * - $_SESSION['id'] (ID del usuario)
 */

session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Hospital & Human</title>

<link rel="stylesheet" href="/citas_medicas/assets/css/style.css">

</head>
<body>

<header id="header">

    <!-- LOGO INSTITUCIONAL -->
    <img src="/citas_medicas/assets/img/logo.png" class="logo">

    <!-- MENÚ DE NAVEGACIÓN ADAPTADO POR ROL -->
    <div class="nav">

        <?php if(isset($_SESSION['usuario'])): ?>

            <span><?php echo $_SESSION['usuario']; ?></span>

            <?php if($_SESSION['rol'] == 'admin'): ?>
                <a href="/citas_medicas/admin/index.php">Admin</a>
            <?php else: ?>
                <a href="/citas_medicas/user/index.php">Mi Panel</a>
            <?php endif; ?>

            <a href="/citas_medicas/logout.php">Salir</a>

        <?php else: ?>

            <a href="/citas_medicas/index.php">Inicio</a>
            <a href="#especialidades">Especialidades</a>
            <a href="/citas_medicas/login.php">Login</a>
            <a href="/citas_medicas/register.php">Registrarse</a>

        <?php endif; ?>

    </div>

</header>