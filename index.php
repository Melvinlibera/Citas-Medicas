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

$nombre_completo = null;
if (isset($_SESSION['id'])) {
    require_once("config/db.php");
    $id_usuario = $_SESSION['id'];
    $stmt = $pdo->prepare("SELECT nombre FROM usuarios WHERE id = ?");
    $stmt->execute([$id_usuario]);
    $nombre_completo = $stmt->fetchColumn();
}
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
            </div>
        </section>
        <!-- SECCIÓN SUCURSALES AL FINAL -->
        <section class="sucursales-final" style="background: #fff; padding: 60px 0 60px 0; display: flex; justify-content: center; align-items: center;">
            <div style="box-shadow: 0 8px 32px rgba(30,144,255,0.10); border-radius: 18px; max-width: 600px; width: 95%; padding: 38px 28px; text-align: center;">
                <h2 style="color: #0a1f44; font-weight: 800; margin-bottom: 18px;">Nuestras Sucursales</h2>
                <p style="color: #555; font-size: 17px;">
                    <strong>Sede Principal:</strong> Avenida Independencia #123, Santo Domingo<br>
                    <strong>Teléfono:</strong> +1 (809) 123-4567<br>
                    <strong>Email:</strong> info@Hospital&Human.com<br><br>
                    <strong>Horario de Atención:</strong> Lunes a Domingo, 7:00 AM - 10:00 PM
                </p>
            </div>
        </section>
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

<!-- SECCIÓN HERO - BIENVENIDA MEJORADA -->
<section class="hero" style="background: linear-gradient(135deg, #0a1f44 60%, #1e90ff 100%); min-height: 90vh; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 18px; padding: 60px 20px 40px 20px;">
    <!-- Logo eliminado para evitar repetición visual -->
    <h1 style="font-size: 60px; font-weight: 800; color: #fff; letter-spacing: 2px; text-shadow: 0 4px 24px #0a1f44; margin: 0; animation: fadeUp 1s 0.2s both;">HOSPITAL & HUMAN</h1>
    <h2 style="font-size: 28px; color: #e2e8f0; font-weight: 500; margin: 0; animation: fadeUp 1s 0.4s both;">TU SALUD ES NUESTRA PRIORIDAD</h2>
    <h3 style="font-size: 20px; color: #b6d0f7; font-weight: 400; margin: 0; animation: fadeUp 1s 0.6s both;">Medicina de excelencia con trato humano</h3>
    <?php if(isset($_SESSION['usuario'])): ?>
        <div style="margin-top: 30px; animation: fadeUp 1s 0.8s both;">
            <span style="font-size: 22px; color: #fff; background: rgba(30,144,255,0.15); padding: 12px 32px; border-radius: 16px; box-shadow: 0 2px 12px rgba(30,144,255,0.08); font-weight: 600;">
                ¡Bienvenido/a, <?php echo htmlspecialchars($nombre_completo ? $nombre_completo : $_SESSION['usuario']); ?>!
            </span>
        </div>
    <?php endif; ?>
    <div style="margin-top: 38px; display: flex; gap: 18px; flex-wrap: wrap; justify-content: center; animation: fadeUp 1s 1s both;">
        <?php if(isset($_SESSION['usuario'])): ?>
            <a href="user/agendar.php" class="btn-hero">📅 Agendar Cita</a>
            <a href="user/mis_citas.php" class="btn-hero btn-outline">📋 Ver Mis Citas</a>
            <a href="user/perfil.php" class="btn-hero btn-outline">👤 Mi Perfil</a>
        <?php else: ?>
            <a href="auth/register.php" class="btn-hero">✍️ Registrarse</a>
            <a href="auth/login.php" class="btn-hero btn-outline">🔐 Iniciar Sesión</a>
        <?php endif; ?>
    </div>
</section>

<!-- SECCIÓN INFORMACIÓN - QUIÉNES SOMOS (PROFESIONAL) -->
<section class="info" style="background: linear-gradient(120deg, #f8fafc 60%, #e3f0ff 100%); padding: 80px 0 60px 0;">
    <div class="container" style="box-shadow: 0 8px 32px rgba(30,144,255,0.07);">
        <h2 style="color: #0a1f44; font-weight: 700;">¿Quiénes somos?</h2>
        <p style="color: #555; font-size: 17px;">En Hospital & Human, la excelencia médica se une al trato humano. Somos una institución de alta complejidad enfocada en transformar la experiencia del paciente mediante atención integral y tecnología avanzada.</p>
    </div>
    <div class="container" style="box-shadow: 0 8px 32px rgba(30,144,255,0.07);">
        <h2 style="color: #0a1f44; font-weight: 700;">Nuestra Misión</h2>
        <p style="color: #555; font-size: 17px;">Brindar servicios de salud con altos estándares, priorizando el bienestar físico, emocional y social de cada paciente, en un ambiente de calidez y respeto.</p>
    </div>
    <div class="container" style="box-shadow: 0 8px 32px rgba(30,144,255,0.07);">
        <h2 style="color: #0a1f44; font-weight: 700;">Nuestra Visión</h2>
        <p style="color: #555; font-size: 17px;">Ser referente en medicina moderna, destacando por innovación, calidad y humanidad. Aspiramos a ser el hospital de confianza para las familias dominicanas.</p>
    </div>
    <div class="container" style="box-shadow: 0 8px 32px rgba(30,144,255,0.07);">
        <h2 style="color: #0a1f44; font-weight: 700;">Nuestras Sucursales</h2>
        <p style="color: #555; font-size: 17px;">
            <strong>Sede Principal:</strong> Avenida Independencia #123, Santo Domingo<br>
            <strong>Teléfono:</strong> +1 (809) 123-4567<br>
            <strong>Email:</strong> info@Hospital&Human.com<br><br>
            <strong>Horario de Atención:</strong> Lunes a Domingo, 7:00 AM - 10:00 PM
        </p>
    </div>
</section>

<!-- SECCIÓN ESPECIALIDADES (PROFESIONAL) -->
<section class="section especialidades-modernas" style="background: linear-gradient(120deg, #e3f0ff 60%, #f8fafc 100%); min-height: 100vh;">
    <h2 style="color: #0a1f44; font-weight: 800; text-shadow: 0 2px 12px #b6d0f7;">Nuestras Especialidades Médicas</h2>
    <p style="color: #1e90ff; font-size: 18px; margin-bottom: 30px; font-weight: 500; text-shadow: 0 1px 8px #e3f0ff;">Contamos con especialistas altamente capacitados en diversas ramas de la medicina</p>
    <div class="cards cards-glass">
        <!-- Cada especialidad es un enlace a su página de detalle -->
        <a href="especialidades/ver.php?id=1" title="Ver especialistas en Psicología"><span>🧠</span><strong>Psicología</strong></a>
        <a href="especialidades/ver.php?id=2" title="Ver especialistas en Medicina General"><span>⚕️</span><strong>Medicina General</strong></a>
        <a href="especialidades/ver.php?id=3" title="Ver especialistas en Cardiología"><span>❤️</span><strong>Cardiología</strong></a>
        <a href="especialidades/ver.php?id=4" title="Ver especialistas en Ginecología"><span>👩‍⚕️</span><strong>Ginecología y Obstetricia</strong></a>
        <a href="especialidades/ver.php?id=5" title="Ver especialistas en Urología"><span>🏥</span><strong>Urología</strong></a>
        <a href="especialidades/ver.php?id=6" title="Ver especialistas en Oncología"><span>🔬</span><strong>Oncología</strong></a>
        <a href="especialidades/ver.php?id=7" title="Ver especialistas en Nefrología"><span>💧</span><strong>Nefrología</strong></a>
        <a href="especialidades/ver.php?id=8" title="Ver especialistas en Endocrinología"><span>🧬</span><strong>Endocrinología</strong></a>
        <a href="especialidades/ver.php?id=9" title="Ver especialistas en Traumatología"><span>🦴</span><strong>Traumatología y Ortopedia</strong></a>
        <a href="especialidades/ver.php?id=10" title="Ver especialistas en Pediatría"><span>👶</span><strong>Pediatría</strong></a>
        <a href="especialidades/ver.php?id=11" title="Ver especialistas en Neonatología"><span>🍼</span><strong>Neonatología</strong></a>
        <a href="especialidades/ver.php?id=12" title="Ver especialistas en Medicina Intensiva"><span>🚑</span><strong>Medicina Intensiva (UCI)</strong></a>
        <a href="especialidades/ver.php?id=13" title="Ver especialistas en Radiología"><span>📸</span><strong>Radiología</strong></a>
        <a href="especialidades/ver.php?id=14" title="Ver especialistas en Dermatología"><span>🩹</span><strong>Dermatología</strong></a>
        <a href="especialidades/ver.php?id=15" title="Ver especialistas en Oftalmología"><span>👁️</span><strong>Oftalmología</strong></a>
    </div>
</section>

<!-- SECCIÓN LLAMADA A LA ACCIÓN (PROFESIONAL) -->
<section class="section" style="background: linear-gradient(135deg, #0a1f44 60%, #1e90ff 100%); color: white; padding: 70px 20px 60px 20px;">
    <h2 style="color: #fff; font-weight: 800;">¿Listo para agendar tu cita?</h2>
    <p style="color: #e2e8f0; font-size: 18px; margin-bottom: 25px; font-weight: 500;">Nuestros especialistas están disponibles para atenderte. Elige tu especialidad y médico preferido.</p>
    <div style="display: flex; gap: 18px; flex-wrap: wrap; justify-content: center;">
        <?php if(isset($_SESSION['usuario'])): ?>
            <a href="user/agendar.php" class="btn-hero">📅 Agendar Cita Ahora</a>
            <a href="user/mis_citas.php" class="btn-hero btn-outline">📋 Ver Mis Citas</a>
        <?php else: ?>
            <a href="auth/register.php" class="btn-hero">✍️ Registrarse Ahora</a>
            <a href="auth/login.php" class="btn-hero btn-outline">🔐 Iniciar Sesión</a>
        <?php endif; ?>
    </div>
</section>

<!-- FOOTER PROFESIONAL -->
<footer style="background: linear-gradient(90deg, #0a1f44 60%, #1e90ff 100%); color: white; text-align: center; padding: 38px 20px 28px 20px; margin-top: 40px; box-shadow: 0 -2px 16px rgba(30,144,255,0.08);">
    <p style="font-size: 18px; font-weight: 600; letter-spacing: 1px;">&copy; 2026 Hospital & Human. Todos los derechos reservados.</p>
    <p style="font-size: 13px; opacity: 0.8;">Medicina de excelencia con trato humano</p>
</footer>

<!-- Scripts -->
<script src="assets/js/main.js" defer></script>
<script src="assets/js/validaciones.js" defer></script>
<style>
    .btn-hero {
        background: #1e90ff;
        color: #fff;
        padding: 14px 32px;
        border-radius: 12px;
        font-size: 18px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 2px 12px rgba(30,144,255,0.10);
        margin: 8px 6px;
        border: none;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.2s;
        display: inline-block;
        letter-spacing: 0.5px;
        cursor: pointer;
        animation: scaleIn 0.8s cubic-bezier(.4,2,.6,1) both;
    }
    .btn-hero:hover {
        background: #0a1f44;
        color: #fff;
        box-shadow: 0 4px 24px rgba(10,31,68,0.13);
        transform: translateY(-2px) scale(1.04);
    }
    .btn-hero.btn-outline {
        background: transparent;
        color: #1e90ff;
        border: 2px solid #1e90ff;
    }
    .btn-hero.btn-outline:hover {
        background: #1e90ff;
        color: #fff;
    }
    </style>
    <style>
    /* Modernización visual de especialidades */
    .cards.cards-glass {
        background: rgba(255,255,255,0.15);
        border-radius: 32px;
        box-shadow: 0 8px 40px 0 rgba(30,144,255,0.10), 0 1.5px 8px 0 rgba(30,144,255,0.07);
        padding: 38px 18px 38px 18px;
        backdrop-filter: blur(6px) saturate(1.2);
        border: 1.5px solid rgba(30,144,255,0.10);
        margin-bottom: 48px;
        margin-top: 0;
        transition: box-shadow 0.3s;
    }
    .cards.cards-glass a {
        background: rgba(10,31,68,0.93);
        color: #fff;
        border-radius: 18px;
        box-shadow: 0 2px 16px 0 rgba(30,144,255,0.10);
        border: 1.5px solid rgba(30,144,255,0.13);
        position: relative;
        overflow: hidden;
        transition: box-shadow 0.25s, background 0.25s, color 0.25s, transform 0.18s;
        min-height: 140px;
        isolation: isolate;
    }
    .cards.cards-glass a::before {
        content: "";
        position: absolute;
        top: 0; left: 0; width: 100%; height: 4px;
        background: linear-gradient(90deg, #1e90ff 0%, #0a1f44 100%);
        opacity: 0.7;
        z-index: 2;
    }
    .cards.cards-glass a span {
        font-size: 38px;
        margin-bottom: 8px;
        filter: drop-shadow(0 2px 8px #1e90ff33);
        transition: filter 0.2s;
    }
    .cards.cards-glass a strong {
        font-size: 18px;
        font-weight: 700;
        letter-spacing: 0.2px;
        text-shadow: 0 1px 8px #0a1f4422;
    }
    .cards.cards-glass a:hover {
        background: linear-gradient(120deg, #1e90ff 60%, #0a1f44 100%);
        color: #fff;
        box-shadow: 0 8px 32px 0 #1e90ff44, 0 2px 16px 0 #0a1f4422;
        transform: translateY(-8px) scale(1.045);
        z-index: 3;
    }
    .cards.cards-glass a:hover span {
        filter: drop-shadow(0 4px 16px #fff8) drop-shadow(0 2px 8px #1e90ff88);
    }
    @media (max-width: 900px) {
        .cards.cards-glass {
            padding: 18px 4px 18px 4px;
        }
    }
    @media (max-width: 600px) {
        .cards.cards-glass {
            border-radius: 18px;
            padding: 8px 0 8px 0;
        }
        .cards.cards-glass a {
            min-height: 100px;
            font-size: 15px;
        }
    }
    </style>

</body>
</html>