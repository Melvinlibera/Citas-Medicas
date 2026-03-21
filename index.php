<?php 
/**
 * PÁGINA PRINCIPAL - HOSPITAL & HUMAN
 * 
 * Funcionalidad:
 * - Página de bienvenida con logo dinámico
 * - Secciones de información: Quiénes somos, Misión, Visión
 * - Listado de especialidades con navegación
 * - Acceso a login/registro para usuarios no autenticados
 * - Navegación al panel del usuario para usuarios autenticados
 * 
 * Seguridad:
 * - Sesiones validadas
 * - Roles diferenciados (admin/user)
 */
session_start(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOSPITAL & HUMAN - Citas Médicas</title>
    
    <!-- Estilos principales -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Estilos adicionales para la página de inicio -->
    <style>
        /* Animaciones personalizadas */
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.8;
            }
        }

        /* Estilos del header mejorado */
        #header {
            animation: slideInDown 0.6s ease-out;
        }

        /* Secciones con animación */
        .info {
            animation: slideInUp 0.8s ease-out;
        }

        .section {
            animation: slideInUp 0.8s ease-out;
        }

        /* Efecto hover en tarjetas de especialidades */
        .cards a {
            position: relative;
        }

        .cards a::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: var(--secondary);
            transition: width 0.3s ease;
        }

        .cards a:hover::after {
            width: 100%;
        }

        /* Responsive mejorado */
        @media (max-width: 768px) {
            .nav {
                flex-wrap: wrap;
                gap: 5px;
            }

            .nav a {
                padding: 6px 10px;
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

<!-- HEADER CON LOGO DINÁMICO -->
<header id="header">
    <!-- Logo con efecto de desplazamiento -->
    <img src="assets/img/logo.png" alt="Hospital & Human" class="logo">

    <!-- Navegación con opciones de usuario -->
    <div class="nav">
        <?php if(isset($_SESSION['usuario'])): ?>
            <!-- Usuario autenticado -->
            <span title="Usuario activo">👤 <?php echo htmlspecialchars($_SESSION['usuario']); ?></span>

            <?php if($_SESSION['rol'] == 'admin'): ?>
                <!-- Panel de administrador -->
                <a href="admin/dashboard.php" title="Acceder al panel de administración">Admin</a>
            <?php elseif($_SESSION['rol'] == 'doctor'): ?>
                <!-- Panel de doctor -->
                <a href="doctor/dashboard.php" title="Acceder a mi panel">Mi Panel</a>
            <?php else: ?>
                <!-- Panel de usuario -->
                <a href="user/dashboard.php" title="Acceder a mi panel">Mi Panel</a>
            <?php endif; ?>

            <!-- Cerrar sesión -->
            <a href="auth/logout.php" title="Cerrar sesión">Salir</a>

        <?php else: ?>
            <!-- Usuario no autenticado -->
            <a href="auth/login.php" title="Iniciar sesión">Login</a>
            <a href="auth/register.php" title="Crear nueva cuenta">Registro</a>

        <?php endif; ?>
    </div>
</header>

<?php include("includes/floating_theme_toggle.php"); ?>

<!-- SECCIÓN HERO - BIENVENIDA -->
<section class="hero">
    <h1><p style="font-size: 80px; opacity: 0.9; margin-top: 10px;">HOSPITAL & HUMAN</p></h1>
    <h2><p style="font-size: 30px; opacity: 0.9; margin-top: 10px;">TU SALUD ES NUESTRA PRIORIDAD</p></h2>
    <h3><p style="font-size: 18px; opacity: 0.9; margin-top: 10px;">Medicina de excelencia con trato humano</p></h3>
</section>

<!-- SECCIÓN INFORMACIÓN - QUIÉNES SOMOS -->
<section class="info">
    <div class="container">
        <h2>¿Quiénes somos?</h2>
        <p>
            En Hospital & Human, entendemos que la medicina de excelencia no solo se mide por diagnósticos precisos, 
            sino por la calidad del trato humano. Somos una institución de alta complejidad enfocada en transformar 
            la experiencia del paciente mediante atención integral.
        </p>
    </div>

    <div class="container">
        <h2>Nuestra Misión</h2>
        <p>
            Brindar servicios de salud con altos estándares, priorizando el bienestar físico, emocional y social 
            de cada paciente. Nos comprometemos a utilizar tecnología avanzada en un ambiente de calidez y respeto.
        </p>
    </div>

    <div class="container">
        <h2>Nuestra Visión</h2>
        <p>
            Ser referente en medicina moderna, destacando por innovación, calidad y humanidad. Aspiramos a ser 
            el hospital de confianza para las familias dominicanas, reconocido por excelencia y compromiso.
        </p>
    </div>

    <div class="container">
        <h2>Nuestras Sucursales</h2>
        <p>
            <strong>Sede Principal:</strong> Avenida Independencia #123, Santo Domingo<br>
            <strong>Teléfono:</strong> +1 (809) 123-4567<br>
            <strong>Email:</strong> info@Hospital&Human.com<br><br>
            <strong>Horario de Atención:</strong> Lunes a Domingo, 7:00 AM - 10:00 PM
        </p>
    </div>
</section>

<!-- SECCIÓN ESPECIALIDADES -->
<section class="section">
    <h2>Nuestras Especialidades Médicas</h2>
    <p style="color: var(--text-light); margin-bottom: 30px;">
        Contamos con especialistas altamente capacitados en diversas ramas de la medicina
    </p>

    <div class="cards">
        <!-- Cada especialidad es un enlace a su página de detalle -->
        <a href="especialidades/ver.php?id=1" title="Ver especialistas en Psicología">
            <span>🧠</span>
            <strong>Psicología</strong>
        </a>
        <a href="especialidades/ver.php?id=2" title="Ver especialistas en Medicina General">
            <span>⚕️</span>
            <strong>Medicina General</strong>
        </a>
        <a href="especialidades/ver.php?id=3" title="Ver especialistas en Cardiología">
            <span>❤️</span>
            <strong>Cardiología</strong>
        </a>
        <a href="especialidades/ver.php?id=4" title="Ver especialistas en Ginecología">
            <span>👩‍⚕️</span>
            <strong>Ginecología y Obstetricia</strong>
        </a>
        <a href="especialidades/ver.php?id=5" title="Ver especialistas en Urología">
            <span>🏥</span>
            <strong>Urología</strong>
        </a>
        <a href="especialidades/ver.php?id=6" title="Ver especialistas en Oncología">
            <span>🔬</span>
            <strong>Oncología</strong>
        </a>
        <a href="especialidades/ver.php?id=7" title="Ver especialistas en Nefrología">
            <span>💧</span>
            <strong>Nefrología</strong>
        </a>
        <a href="especialidades/ver.php?id=8" title="Ver especialistas en Endocrinología">
            <span>🧬</span>
            <strong>Endocrinología</strong>
        </a>
        <a href="especialidades/ver.php?id=9" title="Ver especialistas en Traumatología">
            <span>🦴</span>
            <strong>Traumatología y Ortopedia</strong>
        </a>
        <a href="especialidades/ver.php?id=10" title="Ver especialistas en Pediatría">
            <span>👶</span>
            <strong>Pediatría</strong>
        </a>
        <a href="especialidades/ver.php?id=11" title="Ver especialistas en Neonatología">
            <span>🍼</span>
            <strong>Neonatología</strong>
        </a>
        <a href="especialidades/ver.php?id=12" title="Ver especialistas en Medicina Intensiva">
            <span>🚑</span>
            <strong>Medicina Intensiva (UCI)</strong>
        </a>
        <a href="especialidades/ver.php?id=13" title="Ver especialistas en Radiología">
            <span>📸</span>
            <strong>Radiología</strong>
        </a>
        <a href="especialidades/ver.php?id=14" title="Ver especialistas en Dermatología">
            <span>🩹</span>
            <strong>Dermatología</strong>
        </a>
        <a href="especialidades/ver.php?id=15" title="Ver especialistas en Oftalmología">
            <span>👁️</span>
            <strong>Oftalmología</strong>
        </a>
    </div>
</section>

<!-- SECCIÓN LLAMADA A LA ACCIÓN -->
<section class="section" style="background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 60px 20px;">
    <h2 style="color: white;">¿Listo para agendar tu cita?</h2>
    <p style="color: rgba(255,255,255,0.9); margin-bottom: 25px;">
        Nuestros especialistas están disponibles para atenderte. Elige tu especialidad y médico preferido.
    </p>

    <?php if(isset($_SESSION['usuario'])): ?>
        <!-- Usuario autenticado -->
        <a href="user/agendar.php" style="background: white; color: var(--primary); padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block; transition: 0.3s; margin: 10px;">
            📅 Agendar Cita Ahora
        </a>
        <a href="user/mis_citas.php" style="background: rgba(255,255,255,0.2); color: white; padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block; transition: 0.3s; margin: 10px; border: 2px solid white;">
            📋 Ver Mis Citas
        </a>
    <?php else: ?>
        <!-- Usuario no autenticado -->
        <a href="auth/register.php" style="background: white; color: var(--primary); padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block; transition: 0.3s; margin: 10px;">
            ✍️ Registrarse Ahora
        </a>
        <a href="auth/login.php" style="background: rgba(255,255,255,0.2); color: white; padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block; transition: 0.3s; margin: 10px; border: 2px solid white;">
            🔐 Iniciar Sesión
        </a>
    <?php endif; ?>
</section>

<!-- FOOTER -->
<footer style="background: var(--primary); color: white; text-align: center; padding: 30px 20px; margin-top: 40px;">
    <p>&copy; 2026 Hospital & Human. Todos los derechos reservados.</p>
    <p style="font-size: 12px; opacity: 0.8;">Medicina de excelencia con trato humano</p>
</footer>

<!-- Scripts -->
<script src="assets/js/main.js" defer></script>
<script src="assets/js/validaciones.js" defer></script>

</body>
</html>
