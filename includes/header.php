<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Hospital & Human</title>

<link rel="stylesheet" href="/citas_medicas/assets/css/style.css">

</head>
<body>

<header id="header">

    <!-- LOGO -->
    <img src="/citas_medicas/assets/img/logo.png" class="logo">

    <!-- NAV -->
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