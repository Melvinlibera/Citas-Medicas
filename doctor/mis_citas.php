<?php
/**
 * MIS CITAS DEL DOCTOR - HOSPITAL & HUMAN
 * 
 * Funcionalidad:
 * - Listado de todas las citas del doctor
 * - Filtrado por estado
 * - Cambio de estado de citas
 * - Visualización de detalles del paciente
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

$id_doctor = $_SESSION['id_usuario'];

// Procesar cambio de estado de cita
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_cita'], $_POST['nuevo_estado'])) {
    $id_cita = $_POST['id_cita'];
    $nuevo_estado = $_POST['nuevo_estado'];

    // Validar que la cita pertenece al doctor
    $stmt = $pdo->prepare("SELECT * FROM citas WHERE id = ? AND id_doctor = ?");
    $stmt->execute([$id_cita, $id_doctor]);
    $cita = $stmt->fetch();

    if ($cita && in_array($nuevo_estado, ['pendiente', 'completada', 'cancelada'])) {
        $stmt = $pdo->prepare("UPDATE citas SET estado = ? WHERE id = ?");
        $stmt->execute([$nuevo_estado, $id_cita]);
        $mensaje = "Estado de la cita actualizado correctamente.";
    }
}

// Obtener filtro
$filtro = $_GET['filtro'] ?? 'todas';
$estados_validos = ['pendiente', 'completada', 'cancelada'];

// Construir consulta
$query = "
    SELECT c.*, u.nombre as paciente_nombre, u.telefono, u.correo, u.seguro, e.nombre as especialidad_nombre
    FROM citas c
    JOIN usuarios u ON c.id_usuario = u.id
    JOIN especialidades e ON c.id_especialidad = e.id
    WHERE c.id_doctor = ?
";

$params = [$id_doctor];

if ($filtro !== 'todas' && in_array($filtro, $estados_validos)) {
    $query .= " AND c.estado = ?";
    $params[] = $filtro;
}

$query .= " ORDER BY c.fecha DESC, c.hora DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$citas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Citas - Hospital & Human</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header-section {
            margin-bottom: 2rem;
        }

        .header-section h1 {
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .filtros {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .filtro-btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid var(--secondary);
            background: var(--white);
            color: var(--secondary);
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
            text-decoration: none;
            display: inline-block;
        }

        .filtro-btn:hover,
        .filtro-btn.active {
            background: var(--secondary);
            color: var(--white);
        }

        .citas-list {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .cita-card {
            border-bottom: 1px solid #eee;
            padding: 1.5rem;
            transition: var(--transition);
        }

        .cita-card:last-child {
            border-bottom: none;
        }

        .cita-card:hover {
            background: var(--background);
        }

        .cita-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .cita-info {
            flex: 1;
        }

        .paciente-nombre {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .cita-detalles {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }

        .detalle-item {
            color: var(--text-light);
        }

        .detalle-label {
            font-weight: 600;
            color: var(--text);
        }

        .estado-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.875rem;
        }

        .estado-pendiente {
            background: #fff3cd;
            color: #856404;
        }

        .estado-completada {
            background: #d4edda;
            color: #155724;
        }

        .estado-cancelada {
            background: #f8d7da;
            color: #721c24;
        }

        .cita-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-weight: 600;
            font-size: 0.875rem;
            transition: var(--transition);
        }

        .btn-completar {
            background: var(--success);
            color: var(--white);
        }

        .btn-completar:hover {
            background: #218838;
        }

        .btn-cancelar {
            background: var(--error);
            color: var(--white);
        }

        .btn-cancelar:hover {
            background: #c82333;
        }

        .no-citas {
            text-align: center;
            padding: 3rem;
            color: var(--text-light);
        }

        .mensaje {
            padding: 1rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1rem;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <?php include("../includes/header_dynamic.php"); ?>

    <div class="container">
        <div class="header-section">
            <h1>Mis Citas</h1>
            <?php if (isset($mensaje)): ?>
                <div class="mensaje"><?php echo htmlspecialchars($mensaje); ?></div>
            <?php endif; ?>
        </div>

        <!-- Filtros -->
        <div class="filtros">
            <a href="?filtro=todas" class="filtro-btn <?php echo $filtro === 'todas' ? 'active' : ''; ?>">
                Todas
            </a>
            <a href="?filtro=pendiente" class="filtro-btn <?php echo $filtro === 'pendiente' ? 'active' : ''; ?>">
                Pendientes
            </a>
            <a href="?filtro=completada" class="filtro-btn <?php echo $filtro === 'completada' ? 'active' : ''; ?>">
                Completadas
            </a>
            <a href="?filtro=cancelada" class="filtro-btn <?php echo $filtro === 'cancelada' ? 'active' : ''; ?>">
                Canceladas
            </a>
        </div>

        <!-- Listado de citas -->
        <div class="citas-list">
            <?php if (empty($citas)): ?>
                <div class="no-citas">
                    <p>No hay citas <?php echo $filtro !== 'todas' ? 'con estado ' . htmlspecialchars($filtro) : ''; ?></p>
                </div>
            <?php else: ?>
                <?php foreach ($citas as $cita): ?>
                    <div class="cita-card">
                        <div class="cita-header">
                            <div class="cita-info">
                                <div class="paciente-nombre"><?php echo htmlspecialchars($cita['paciente_nombre']); ?></div>
                                <div class="cita-detalles">
                                    <div class="detalle-item">
                                        <span class="detalle-label">Fecha:</span> <?php echo date('d/m/Y', strtotime($cita['fecha'])) . ' ' . date('H:i', strtotime($cita['hora'])); ?>
                                    </div>
                                    <div class="detalle-item">
                                        <span class="detalle-label">Especialidad:</span> <?php echo htmlspecialchars($cita['especialidad_nombre']); ?>
                                    </div>
                                    <div class="detalle-item">
                                        <span class="detalle-label">Teléfono:</span> <?php echo htmlspecialchars($cita['telefono']); ?>
                                    </div>
                                    <div class="detalle-item">
                                        <span class="detalle-label">Seguro:</span> <?php echo htmlspecialchars($cita['seguro'] ?? 'Privado'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="estado-badge estado-<?php echo $cita['estado']; ?>">
                                <?php echo ucfirst($cita['estado']); ?>
                            </div>
                        </div>

                        <?php if ($cita['estado'] === 'pendiente'): ?>
                            <div class="cita-actions">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="id_cita" value="<?php echo $cita['id']; ?>">
                                    <input type="hidden" name="nuevo_estado" value="completada">
                                    <button type="submit" class="btn-action btn-completar">Marcar como Completada</button>
                                </form>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="id_cita" value="<?php echo $cita['id']; ?>">
                                    <input type="hidden" name="nuevo_estado" value="cancelada">
                                    <button type="submit" class="btn-action btn-cancelar">Cancelar Cita</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
