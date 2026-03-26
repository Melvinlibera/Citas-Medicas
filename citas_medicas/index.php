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
            min-height: 420px;
            padding: 40px 30px;
            position: relative;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: scroll;
            overflow: visible;
            transition: background 0.5s ease;
        }

        .info-container {
            width: 100%;
            max-width: 1200px;
            margin: 0; /* siempre izquierda */
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 24px;
            align-items: start;
            justify-content: start;
            justify-items: start;
            padding-left: 20px;
            padding-top: 24px;
            padding-bottom: 24px;
            position: relative;
            z-index: 1;
        }

        #infSection {
            display: grid;
            justify-content: start;
            align-items: start;
            margin: 0;
            padding-left: 0;
        }

        .info-left {
            display: flex;
            flex-direction: column;
            gap: 16px;
            width: 420px;
            align-items: flex-start;
            justify-content: flex-start;
            margin: 0;
        }

        .info-card {
            background-color: rgba(255, 255, 255, 0.22);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 14px;
            padding: 30px;
            padding-bottom: 72px; /* espacio para botones */
            box-shadow: 0 10px 40px rgba(10, 25, 80, 0.16);
            color: #0a1f44;
            opacity: 0;
            visibility: hidden;
            transform: translateX(-20px);
            transition: opacity 0.35s ease, transform 0.35s ease, visibility 0.35s ease, background-image 0.4s ease;
            min-height: 260px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: scroll;
            position: relative;
        }

        .info-buttons {
            position: absolute;
            bottom: 12px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }

        .info-card.active {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
            outline: 2px solid #1e40af;
            box-shadow: 0 10px 36px rgba(10, 25, 80, 0.25);
        }

        .info-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 14px;
            z-index: 1;
            pointer-events: none;
        }

        .info-buttons {
            position: absolute;
            bottom: 12px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 3;
        }

        .info-card h3,
        .info-card p {
            position: relative;
            z-index: 2;
        }

        .info-card h3 {
            margin: 0 0 12px 0;
            font-size: 1.4rem;
            font-weight: 800;
            color: #0a1f44;
        }

        .info-card p {
            margin: 0;
            font-size: 0.95rem;
            line-height: 1.6;
            color: #334155;
        }

        .info-buttons {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }

        .info-buttons button {
            flex: 1;
            padding: 12px 16px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 12px rgba(30, 144, 255, 0.2);
        }

        .info-buttons button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 144, 255, 0.35);
        }

        .info-buttons button:active {
            transform: translateY(0);
        }

        .info-right {
            width: 100%;
            height: 380px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-image: url('assets/img/logo.png');
            background-color: rgba(30, 64, 175, 0.1);
            filter: brightness(0.95);
            transition: background-image 0.4s ease, filter 0.4s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .info-right::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(30, 64, 175, 0.4) 0%, rgba(59, 130, 246, 0.3) 100%);
            pointer-events: none;
        }

        .section {
            animation: slideInUp 0.8s ease-out;
        }

        /* Efecto hover en tarjetas de especialidades */
        .cards a::after {
            content: '';
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

<!-- SECCIÓN INFORMACIÓN - TARJETA ÚNICA (NUEVA) -->
<section class="info" id="infSection">
    <div class="info-container">
        <div class="info-left">
            <div class="info-card active" id="card0">
                <h3>Quiénes somos</h3>
                <p>En Hospital & Human, la excelencia médica se une al trato humano. Somos una institución de alta complejidad enfocada en transformar la experiencia del paciente mediante atención integral y tecnología avanzada.</p>
            </div>
            <div class="info-card" id="card1">
                <h3>Nuestra Misión</h3>
                <p>Brindar servicios de salud con altos estándares, priorizando el bienestar físico, emocional y social de cada paciente, en un ambiente de calidez y respeto.</p>
            </div>
            <div class="info-card" id="card2">
                <h3>Nuestra Visión</h3>
                <p>Ser referente en medicina moderna, destacando por innovación, calidad y humanidad. Aspiramos a ser el hospital de confianza para las familias dominicanas.</p>
            </div>
            <div class="info-card" id="card3">
                <h3>Nuestras Sucursales</h3>
                <p>Sede Principal: Avenida Independencia #123, Santo Domingo.</p> 
                <p>Teléfono: +1 (809) 123-4567. </p>
                <p>Email: info@Hospital&Human.com.</p>
            </div>
        </div>
        <div class="info-buttons" id="infoButtons">
            <button id="btnPrev" type="button">←</button>
            <button id="btnNext" type="button">→</button>
        </div>
        <div class="info-right" id="infImage"></div>
    </div>
</section>

<!-- SECCIÓN ESPECIALIDADES (MODERNA Y PROFESIONAL) -->
<section class="especialidades-modernas" style="background: linear-gradient(120deg, #f5f9ff 0%, #e3f0ff 50%, #f8fafc 100%); min-height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">
    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="color: #0a1f44; font-weight: 800; font-size: 3em; text-shadow: 0 2px 12px rgba(182, 208, 247, 0.3); margin-bottom: 16px; letter-spacing: -0.5px;">Nuestras Especialidades Médicas</h2>
        <p style="color: #1e90ff; font-size: 20px; margin-bottom: 40px; font-weight: 500; text-shadow: 0 1px 8px rgba(227, 240, 255, 0.5); max-width: 700px; margin-left: auto; margin-right: auto; line-height: 1.6;">Contamos con especialistas altamente capacitados en diversas ramas de la medicina para tu bienestar</p>
    </div>
    <div class="cards cards-glass">
        <!-- Cada especialidad es un enlace a su página de detalle -->
        <a href="especialidades/ver.php?id=1" title="Ver especialistas en Psicología" class="card-especialidad"><span>🧠</span><strong>Psicología</strong></a>
        <a href="especialidades/ver.php?id=2" title="Ver especialistas en Medicina General" class="card-especialidad"><span>⚕️</span><strong>Medicina General</strong></a>
        <a href="especialidades/ver.php?id=3" title="Ver especialistas en Cardiología" class="card-especialidad"><span>❤️</span><strong>Cardiología</strong></a>
        <a href="especialidades/ver.php?id=4" title="Ver especialistas en Ginecología" class="card-especialidad"><span>👩‍⚕️</span><strong>Ginecología<br>y Obstetricia</strong></a>
        <a href="especialidades/ver.php?id=5" title="Ver especialistas en Urología" class="card-especialidad"><span>🏥</span><strong>Urología</strong></a>
        <a href="especialidades/ver.php?id=6" title="Ver especialistas en Oncología" class="card-especialidad"><span>🔬</span><strong>Oncología</strong></a>
        <a href="especialidades/ver.php?id=7" title="Ver especialistas en Nefrología" class="card-especialidad"><span>💧</span><strong>Nefrología</strong></a>
        <a href="especialidades/ver.php?id=8" title="Ver especialistas en Endocrinología" class="card-especialidad"><span>🧬</span><strong>Endocrinología</strong></a>
        <a href="especialidades/ver.php?id=9" title="Ver especialistas en Traumatología" class="card-especialidad"><span>🦴</span><strong>Traumatología<br>y Ortopedia</strong></a>
        <a href="especialidades/ver.php?id=10" title="Ver especialistas en Pediatría" class="card-especialidad"><span>👶</span><strong>Pediatría</strong></a>
        <a href="especialidades/ver.php?id=11" title="Ver especialistas en Neonatología" class="card-especialidad"><span>🍼</span><strong>Neonatología</strong></a>
        <a href="especialidades/ver.php?id=12" title="Ver especialistas en Medicina Intensiva" class="card-especialidad"><span>🚑</span><strong>Medicina<br>Intensiva (UCI)</strong></a>
        <a href="especialidades/ver.php?id=13" title="Ver especialistas en Radiología" class="card-especialidad"><span>📸</span><strong>Radiología</strong></a>
        <a href="especialidades/ver.php?id=14" title="Ver especialistas en Dermatología" class="card-especialidad"><span>🩹</span><strong>Dermatología</strong></a>
        <a href="especialidades/ver.php?id=15" title="Ver especialistas en Oftalmología" class="card-especialidad"><span>👁️</span><strong>Oftalmología</strong></a>
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

<!-- FOOTER -->
<footer style="background: linear-gradient(90deg, #0a1f44 60%, #1e90ff 100%); color: white; text-align: center; padding: 38px 20px 28px 20px; margin-top: 40px; box-shadow: 0 -2px 16px rgba(30,144,255,0.08);">
    <p style="font-size: 18px; font-weight: 600; letter-spacing: 1px;">&copy; 2026 Hospital & Human. Todos los derechos reservados.</p>
    <p style="font-size: 13px; opacity: 0.8;">Medicina de excelencia con trato humano</p>
</footer>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js" defer></script>
<script defer>
window.addEventListener('DOMContentLoaded', () => {
    const infSection = document.getElementById('infSection');
    const infImage = document.getElementById('infImage');
    const btnPrev = document.getElementById('btnPrev');
    const btnNext = document.getElementById('btnNext');

    const cardData = [
        {
            title: 'Quiénes somos',
            text: 'En Hospital & Human, la excelencia médica se une al trato humano. Somos una institución de alta complejidad enfocada en transformar la experiencia del paciente mediante atención integral y tecnología avanzada.',
            bg: 'url(assets/img/quienes-somos.jpg)',
            img: 'assets/img/fondo.jpg'
        },
        {
            title: 'Nuestra Misión',
            text: 'Brindar servicios de salud con altos estándares, priorizando el bienestar físico, emocional y social de cada paciente, en un ambiente de calidez y respeto.',
            bg: 'url(assets/img/mision.jpg)',
            img: 'assets/img/fondo.jpg'
        },
        {
            title: 'Nuestra Visión',
            text: 'Ser referente en medicina moderna, destacando por innovación, calidad y humanidad. Aspiramos a ser el hospital de confianza para las familias dominicanas.',
            bg: 'url(assets/img/vision.jpg)',
            img: 'assets/img/fondo.jpg'
        },
        {
            title: 'Nuestras Sucursales',
            text: 'Sede Principal: Avenida Independencia #123, Santo Domingo. Teléfono: +1 (809) 123-4567. Email: info@Hospital&Human.com.',
            bg: 'url(assets/img/sucursales.jpg)',
            img: 'assets/img/fondo.jpg'
        }
    ];

    let currentIndex = 0;

        const infoButtons = document.getElementById('infoButtons');

        function relocateButtons() {
            const activeCard = document.querySelector('.info-card.active');
            if (activeCard && infoButtons) {
                activeCard.appendChild(infoButtons);
            }
        }

        function showCard(index) {
            // Validar índice
            if (index < 0 || index >= cardData.length) return;
            
            currentIndex = index;
            const card = cardData[currentIndex];

            const allCards = document.querySelectorAll('.info-card');
            allCards.forEach((el, idx) => {
                const cardItem = cardData[idx];
                el.classList.toggle('active', idx === currentIndex);
                el.style.backgroundImage = `url('${cardItem.img}')`;
            });

            relocateButtons();

            // Cambiar fondo de la sección principal sin perder el tamaño del contenedor
            infSection.style.backgroundImage = card.bg;
            infSection.style.backgroundSize = '100% 100%';
            infSection.style.backgroundPosition = 'center';
            infSection.style.backgroundRepeat = 'no-repeat';

            // Cambiar imagen del panel derecho
            infImage.style.backgroundImage = `url('${card.img}')`;
        }

    // Botones
    btnPrev.addEventListener('click', () => {
        showCard((currentIndex - 1 + cardData.length) % cardData.length);
    });

    btnNext.addEventListener('click', () => {
        showCard((currentIndex + 1) % cardData.length);
    });

    // Inicializar
    showCard(0);
});
</script>
</script>
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