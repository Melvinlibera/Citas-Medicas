<?php
/**
 * PÁGINA DE DETALLE DE ESPECIALIDAD
 * 
 * Funcionalidad:
 * - Muestra información detallada de una especialidad médica
 * - Lista los médicos disponibles en esa especialidad
 * - Permite agendar cita directamente
 * - Muestra precios con y sin seguro
 * - Integración con login/registro para usuarios no autenticados
 * 
 * Seguridad:
 * - Validación de ID de especialidad
 * - Sesiones validadas
 * - Inyección SQL prevenida con prepared statements
 */

session_start();
require_once("../config/db.php");

// =========================
// VALIDACIÓN DE PARÁMETROS
// =========================

// Validar que el ID sea numérico y válido
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    die("<div style='text-align:center; padding:40px;'><h2>Error: Especialidad no válida</h2><a href='../index.php'>Volver al inicio</a></div>");
}

$id = intval($_GET['id']);

// =========================
// OBTENER INFORMACIÓN DE ESPECIALIDAD
// =========================

try {
    $stmt = $pdo->prepare("SELECT * FROM especialidades WHERE id = ?");
    $stmt->execute([$id]);
    $esp = $stmt->fetch();

    if(!$esp){
        die("<div style='text-align:center; padding:40px;'><h2>Error: Especialidad no encontrada</h2><a href='../index.php'>Volver al inicio</a></div>");
    }
} catch(PDOException $e) {
    die("<div style='text-align:center; padding:40px;'><h2>Error en la base de datos</h2></div>");
}

// =========================
// OBTENER DOCTORES DE LA ESPECIALIDAD
// =========================

try {
    $stmt = $pdo->prepare("SELECT * FROM doctores WHERE id_especialidad = ? ORDER BY nombre ASC");
    $stmt->execute([$id]);
    $doctores = $stmt->fetchAll();
} catch(PDOException $e) {
    $doctores = [];
}

// =========================
// CALCULAR PRECIOS
// =========================

$precio_sin_seguro = floatval($esp['precio']);
$precio_con_seguro = round($precio_sin_seguro * 0.25, 2); // 75% de descuento = 25% del precio
$descuento = round($precio_sin_seguro * 0.75, 2);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($esp['nombre']); ?> - Hospital & Human</title>
    
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        /* =========================
           CONTENEDOR PRINCIPAL
        ========================= */
        .especialidad-container {
            min-height: 100vh;
            padding: 100px 20px 40px 20px;
            background: var(--background);
        }

        /* =========================
           CAJA DE ESPECIALIDAD
        ========================= */
        .especialidad-box {
            max-width: 900px;
            margin: 0 auto;
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        /* Header de especialidad */
        .especialidad-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--white);
            padding: 40px 30px;
            text-align: center;
        }

        .especialidad-header h1 {
            font-size: 36px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .especialidad-header p {
            font-size: 16px;
            opacity: 0.9;
            margin: 0;
        }

        /* Contenido de especialidad */
        .especialidad-content {
            padding: 40px 30px;
        }

        /* Descripción */
        .descripcion {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 2px solid var(--background);
        }

        .descripcion p {
            font-size: 16px;
            line-height: 1.8;
            color: var(--text-light);
        }

        /* =========================
           SECCIÓN DE PRECIOS
        ========================= */
        .precios-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: var(--radius-sm);
            margin-bottom: 30px;
        }

        .precios-section h3 {
            color: var(--primary);
            margin-bottom: 20px;
            text-align: center;
        }

        .precios-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .precio-card {
            background: var(--white);
            padding: 20px;
            border-radius: var(--radius-sm);
            text-align: center;
            border: 2px solid var(--background);
            transition: var(--transition);
        }

        .precio-card:hover {
            border-color: var(--secondary);
            box-shadow: var(--shadow-md);
        }

        .precio-card h4 {
            color: var(--primary);
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .precio-card .monto {
            font-size: 28px;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 5px;
        }

        .precio-card .moneda {
            font-size: 14px;
            color: var(--text-light);
        }

        .descuento-badge {
            background: var(--success);
            color: var(--white);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            margin-top: 10px;
            display: inline-block;
        }

        /* =========================
           SECCIÓN DE DOCTORES
        ========================= */
        .doctores-section {
            margin-top: 30px;
        }

        .doctores-section h3 {
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 22px;
            font-weight: 700;
        }

        .doctores-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        /* Tarjeta de doctor */
        .doctor-card {
            background: var(--white);
            border: 2px solid var(--background);
            border-radius: var(--radius-sm);
            padding: 25px;
            text-align: center;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .doctor-card:hover {
            border-color: var(--secondary);
            box-shadow: var(--shadow-lg);
            transform: translateY(-5px);
        }

        .doctor-card .doctor-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .doctor-card h4 {
            color: var(--primary);
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .doctor-card .especialidad-badge {
            background: var(--secondary);
            color: var(--white);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
            margin-bottom: 15px;
        }

        .doctor-card .disponibilidad {
            font-size: 13px;
            color: var(--text-light);
            margin-bottom: 15px;
        }

        .doctor-card .btn {
            display: inline-block;
            padding: 12px 20px;
            background: var(--primary);
            color: var(--white);
            text-decoration: none;
            border-radius: var(--radius-sm);
            transition: var(--transition);
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            margin-top: auto;
        }

        .doctor-card .btn:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }

        .doctor-card .btn.login-btn {
            background: var(--warning);
        }

        .doctor-card .btn.login-btn:hover {
            background: #e0a800;
        }

        /* Mensaje cuando no hay doctores */
        .no-doctores {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-light);
        }

        .no-doctores p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        /* =========================
           BOTÓN DE VOLVER
        ========================= */
        .btn-volver {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: var(--primary);
            color: var(--white);
            text-decoration: none;
            border-radius: var(--radius-sm);
            transition: var(--transition);
            font-size: 14px;
        }

        .btn-volver:hover {
            background: var(--secondary);
        }

        /* =========================
           RESPONSIVE
        ========================= */
        @media (max-width: 768px) {
            .especialidad-container {
                padding: 80px 15px 30px 15px;
            }

            .especialidad-header {
                padding: 30px 20px;
            }

            .especialidad-header h1 {
                font-size: 28px;
            }

            .especialidad-content {
                padding: 25px 20px;
            }

            .precios-grid {
                grid-template-columns: 1fr;
            }

            .doctores-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<!-- HEADER -->
<header id="header">
    <img src="../assets/img/logo.png" alt="Hospital & Human" class="logo">
    <div class="nav">
        <?php if(isset($_SESSION['usuario'])): ?>
            <span>👤 <?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
            <?php if($_SESSION['rol'] == 'admin'): ?>
                <a href="../admin/dashboard.php">Admin</a>
            <?php else: ?>
                <a href="../user/dashboard.php">Mi Panel</a>
            <?php endif; ?>
            <a href="../auth/logout.php">Salir</a>
        <?php else: ?>
            <a href="../auth/login.php">Login</a>
            <a href="../auth/register.php">Registro</a>
        <?php endif; ?>
    </div>
</header>

<!-- CONTENEDOR PRINCIPAL -->
<div class="especialidad-container">

    <div class="especialidad-box">

        <!-- HEADER DE ESPECIALIDAD -->
        <div class="especialidad-header">
            <h1><?php echo htmlspecialchars($esp['nombre']); ?></h1>
            <p>Especialidad Médica - Hospital & Human</p>
        </div>

        <!-- CONTENIDO -->
        <div class="especialidad-content">

            <!-- DESCRIPCIÓN -->
            <div class="descripcion">
                <p><?php echo htmlspecialchars($esp['descripcion']); ?></p>
            </div>

            <!-- SECCIÓN DE PRECIOS -->
            <div class="precios-section">
                <h3>💰 Tarifas de Consulta</h3>
                <div class="precios-grid">
                    <!-- Precio sin seguro -->
                    <div class="precio-card">
                        <h4>Sin Seguro</h4>
                        <div class="monto">RD$<?php echo number_format($precio_sin_seguro, 0); ?></div>
                        <div class="moneda">Pesos Dominicanos</div>
                    </div>

                    <!-- Precio con seguro -->
                    <div class="precio-card">
                        <h4>Con Seguro Médico</h4>
                        <div class="monto">RD$<?php echo number_format($precio_con_seguro, 0); ?></div>
                        <div class="moneda">Pesos Dominicanos</div>
                        <div class="descuento-badge">
                            ✓ Ahorro: RD$<?php echo number_format($descuento, 0); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN DE DOCTORES -->
            <div class="doctores-section">
                <h3>👨‍⚕️ Médicos Especialistas Disponibles</h3>

                <?php if($doctores): ?>

                    <div class="doctores-grid">
                        <?php foreach($doctores as $doc): ?>

                            <div class="doctor-card">
                                <div class="doctor-icon">👨‍⚕️</div>
                                <h4><?php echo htmlspecialchars($doc['nombre']); ?></h4>
                                <div class="especialidad-badge"><?php echo htmlspecialchars($esp['nombre']); ?></div>
                                <div class="disponibilidad">
                                    ✓ Disponible<br>
                                    Lunes a Viernes<br>
                                    8:00 AM - 5:00 PM
                                </div>

                                <?php if(isset($_SESSION['usuario'])): ?>
                                    <!-- Usuario autenticado -->
                                    <a href="../user/agendar.php?doctor=<?php echo $doc['id']; ?>&especialidad=<?php echo $id; ?>" class="btn">
                                        📅 Agendar Cita
                                    </a>
                                <?php else: ?>
                                    <!-- Usuario no autenticado -->
                                    <a href="../auth/login.php" class="btn login-btn">
                                        🔐 Iniciar Sesión
                                    </a>
                                <?php endif; ?>
                            </div>

                        <?php endforeach; ?>
                    </div>

                <?php else: ?>

                    <div class="no-doctores">
                        <p>No hay médicos disponibles en esta especialidad en este momento.</p>
                        <p>Por favor, intenta con otra especialidad o contacta con nosotros.</p>
                    </div>

                <?php endif; ?>

            </div>

            <!-- BOTÓN DE VOLVER -->
            <a href="../index.php" class="btn-volver">← Volver al inicio</a>

        </div>

    </div>

</div>

<!-- Scripts -->
<script src="../assets/js/main.js" defer></script>

</body>
</html>
