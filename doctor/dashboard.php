<?php
/**
 * DASHBOARD DEL DOCTOR - HOSPITAL & HUMAN
 * 
 * Funcionalidad:
 * - Panel principal del doctor
 * - Resumen de citas del día
 * - Estadísticas generales
 * - Acceso rápido a funciones principales
 * 
 * Seguridad:
 * - Validación de sesión y rol
 * - Solo doctores pueden acceder
 */

session_start();

// Validar que el usuario sea doctor
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'doctor') {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/db.php");

// Obtener información del doctor
$id_doctor = $_SESSION['id_usuario'];
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ? AND rol = 'doctor'");
$stmt->execute([$id_doctor]);
$doctor = $stmt->fetch();

if (!$doctor) {
    header("Location: ../auth/login.php");
    exit();
}

// Obtener citas de hoy
$hoy = date('Y-m-d');
$stmt = $pdo->prepare("
    SELECT c.*, u.nombre as paciente_nombre, e.nombre as especialidad_nombre
    FROM citas c
    JOIN usuarios u ON c.id_usuario = u.id
    JOIN especialidades e ON c.id_especialidad = e.id
    WHERE c.id_doctor = ? AND DATE(c.fecha) = ?
    ORDER BY c.fecha ASC, c.hora ASC
");
$stmt->execute([$id_doctor, $hoy]);
$citas_hoy = $stmt->fetchAll();

// Obtener estadísticas
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM citas WHERE id_doctor = ?");
$stmt->execute([$id_doctor]);
$total_citas = $stmt->fetch()['total'];

$stmt = $pdo->prepare("SELECT COUNT(*) as completadas FROM citas WHERE id_doctor = ? AND estado = 'completada'");
$stmt->execute([$id_doctor]);
$citas_completadas = $stmt->fetch()['completadas'];

$stmt = $pdo->prepare("SELECT COUNT(*) as pendientes FROM citas WHERE id_doctor = ? AND estado = 'pendiente'");
$stmt->execute([$id_doctor]);
$citas_pendientes = $stmt->fetch()['pendientes'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Doctor - Hospital & Human</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .dashboard-header {
            margin-bottom: 2rem;
        }

        .dashboard-header h1 {
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .dashboard-header p {
            color: var(--text-light);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .stat-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .stat-card h3 {
            color: var(--text-light);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        .stat-card .number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
        }

        .citas-section {
            background: var(--white);
            border-radius: var(--radius);
            padding: 2rem;
            box-shadow: var(--shadow-sm);
        }

        .citas-section h2 {
            color: var(--primary);
            margin-bottom: 1.5rem;
            border-bottom: 2px solid var(--secondary);
            padding-bottom: 0.5rem;
        }

        .cita-item {
            background: var(--background);
            padding: 1rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1rem;
            border-left: 4px solid var(--secondary);
            transition: var(--transition);
        }

        .cita-item:hover {
            background: #f0f5ff;
        }

        .cita-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .cita-paciente {
            font-weight: 600;
            color: var(--primary);
        }

        .cita-hora {
            color: var(--text-light);
            font-size: 0.875rem;
        }

        .cita-especialidad {
            color: var(--text-light);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .no-citas {
            text-align: center;
            padding: 2rem;
            color: var(--text-light);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: #000a2e;
        }

        .btn-secondary {
            background: var(--secondary);
            color: var(--white);
        }

        .btn-secondary:hover {
            background: #0d7acc;
        }
    </style>
</head>
<body>
    <?php include("../includes/header_dynamic.php"); ?>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Bienvenido, Dr. <?php echo htmlspecialchars($doctor['nombre']); ?></h1>
            <p>Panel de control del doctor - Hospital & Human</p>
        </div>

        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total de Citas</h3>
                <div class="number"><?php echo $total_citas; ?></div>
            </div>
            <div class="stat-card">
                <h3>Citas Completadas</h3>
                <div class="number"><?php echo $citas_completadas; ?></div>
            </div>
            <div class="stat-card">
                <h3>Citas Pendientes</h3>
                <div class="number"><?php echo $citas_pendientes; ?></div>
            </div>
        </div>

        <!-- Citas de Hoy -->
        <div class="citas-section">
            <h2>Citas de Hoy (<?php echo date('d/m/Y'); ?>)</h2>
            
            <?php if (empty($citas_hoy)): ?>
                <div class="no-citas">
                    <p>No tienes citas programadas para hoy.</p>
                </div>
            <?php else: ?>
                <?php foreach ($citas_hoy as $cita): ?>
                    <div class="cita-item">
                        <div class="cita-header">
                            <span class="cita-paciente"><?php echo htmlspecialchars($cita['paciente_nombre']); ?></span>
                            <span class="cita-hora"><?php echo date('H:i', strtotime($cita['hora'])); ?></span>
                        </div>
                        <div class="cita-especialidad">
                            <?php echo htmlspecialchars($cita['especialidad_nombre']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="action-buttons">
                <a href="mis_citas.php" class="btn btn-primary">Ver Todas mis Citas</a>
                <a href="perfil.php" class="btn btn-secondary">Editar Perfil</a>
            </div>
        </div>
    </div>
</body>
</html>
