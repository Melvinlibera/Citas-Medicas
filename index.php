<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>HOSPITAL & HUMAN</title>

<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/main.js" defer></script>

<style>
.nav {
    position: absolute;
    top: 20px;
    right: 40px;
}

.nav a {
    margin-left: 10px;
    text-decoration: none;
    background: #0a1f44;
    color: white;
    padding: 8px 14px;
    border-radius: 6px;
    transition: 0.3s;
}

.nav a:hover {
    background: #1e90ff;
}
</style>

</head>

<body>

<!-- HEADER CON LOGO -->
<header id="header">
    <img src="assets/img/logo.png" class="logo">

    <!-- NUEVO NAV (SIN BORRAR LO TUYO) -->
    <div class="nav">
        <?php if(isset($_SESSION['usuario'])): ?>

            <span> <?php echo $_SESSION['usuario']; ?></span>

            <?php if($_SESSION['rol'] == 'admin'): ?>
                <a href="admin/dashboard.php">Admin</a>
            <?php else: ?>
                <a href="user/dashboard.php">Mi Panel</a>
            <?php endif; ?>

            <a href="auth/logout.php">Salir</a>

        <?php else: ?>

            <a href="auth/login.php">Login</a>
            <a href="auth/register.php">Registro</a>

        <?php endif; ?>
    </div>

</header>

<!-- SECCIÓN PRINCIPAL -->
<section class="hero">
    <h1>HOSPITAL & HUMAN</h1>
    <h2>TU SALUD ES NUESTRA PRIORIDAD</h2>
</section>

<!-- QUIENES SOMOS -->
<section class="info">

    <div class="container">
        <h2>¿Quiénes somos?</h2>

        <p>
        En Hospital & Human, entendemos que la medicina de excelencia no solo se mide por diagnósticos precisos, sino por la calidad del trato humano.
        </p>

        <p>
        Somos una institución de alta complejidad enfocada en transformar la experiencia del paciente mediante atención integral.
        </p>

        <p>
        Integramos tecnología avanzada con un acompañamiento cercano, ético y profundamente humano.
        </p>
    </div>

    <div class="container">
        <h2>Nuestra Misión</h2>
        <p>
        Brindar servicios de salud con altos estándares, priorizando el bienestar físico, emocional y social de cada paciente.
        </p>
    </div>

    <div class="container">
        <h2>Nuestra Visión</h2>
        <p>
        Ser referente en medicina moderna, destacando por innovación, calidad y humanidad.
        </p>
    </div>

</section>

<!-- ESPECIALIDADES (NO TOCAR) -->
<section class="section">
<h2>Especialidades</h2>

<div class="cards">

    <a href="especialidades/ver.php?id=1"><span>Psicología</span></a>
    <a href="especialidades/ver.php?id=2"><span>Medicina General</span></a>
    <a href="especialidades/ver.php?id=3"><span>Cardiología</span></a>
    <a href="especialidades/ver.php?id=4"><span>Ginecología y Obstetricia</span></a>
    <a href="especialidades/ver.php?id=5"><span>Urología</span></a>
    <a href="especialidades/ver.php?id=6"><span>Oncología</span></a>
    <a href="especialidades/ver.php?id=7"><span>Nefrología</span></a>
    <a href="especialidades/ver.php?id=8"><span>Endocrinología</span></a>
    <a href="especialidades/ver.php?id=9"><span>Traumatología y Ortopedia</span></a>
    <a href="especialidades/ver.php?id=10"><span>Pediatría</span></a>
    <a href="especialidades/ver.php?id=11"><span>Neonatología</span></a>
    <a href="especialidades/ver.php?id=12"><span>Medicina Intensiva (UCI)</span></a>
    <a href="especialidades/ver.php?id=13"><span>Radiología</span></a>
    <a href="especialidades/ver.php?id=14"><span>Dermatología</span></a>
    <a href="especialidades/ver.php?id=15"><span>Oftalmología</span></a>

</div>
</section>

<!-- LOGIN (NO TOCADO) -->
<section class="section">

<?php if(isset($_SESSION['usuario'])): ?>

    <p>Bienvenido, <b><?php echo $_SESSION['usuario']; ?></b></p>
    <a href="auth/logout.php">Cerrar sesión</a>

<?php else: ?>

    <a href="auth/login.php">Iniciar sesión</a>
    <a href="auth/register.php">Registrarse</a>

<?php endif; ?>

</section>

</body>
</html>