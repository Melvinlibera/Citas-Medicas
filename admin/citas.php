<?php
session_start();
include("../config/db.php");

// Seguridad: solo admin
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

// Cambiar estado de cita
if(isset($_GET['accion']) && isset($_GET['id'])){

    $id = $_GET['id'];
    $accion = $_GET['accion'];

    if(in_array($accion, ['confirmada','cancelada'])){

        $stmt = $pdo->prepare("UPDATE citas SET estado=? WHERE id=?");
        $stmt->execute([$accion, $id]);

        header("Location: citas.php");
        exit();
    }
}

// Obtener citas con JOIN
$stmt = $pdo->prepare("
SELECT citas.*, 
usuarios.nombre AS paciente, 
doctores.nombre AS doctor, 
especialidades.nombre AS especialidad

FROM citas
JOIN usuarios ON citas.id_usuario = usuarios.id
JOIN doctores ON citas.id_doctor = doctores.id
JOIN especialidades ON doctores.id_especialidad = especialidades.id

ORDER BY fecha DESC, hora DESC
");

$stmt->execute();
$citas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Gestión de Citas</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>
.container {
    padding: 80px 20px;
}

h1 {
    text-align: center;
    margin-bottom: 30px;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

th, td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}

th {
    background: #0a1f44;
    color: white;
}

.estado {
    padding: 6px 12px;
    border-radius: 6px;
    color: white;
}

.pendiente { background: orange; }
.confirmada { background: green; }
.cancelada { background: red; }

.acciones a {
    padding: 6px 10px;
    margin: 2px;
    text-decoration: none;
    border-radius: 5px;
    color: white;
}

.confirmar { background: green; }
.cancelar { background: red; }

.volver {
    display: inline-block;
    margin-bottom: 20px;
    text-decoration: none;
    background: #0a1f44;
    color: white;
    padding: 10px;
    border-radius: 6px;
}
</style>

</head>

<body>

<div class="container">

    <a href="dashboard.php" class="volver">← Volver</a>

    <h1>Gestión de Citas</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Paciente</th>
            <th>Doctor</th>
            <th>Especialidad</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>

        <?php foreach($citas as $cita): ?>
        <tr>
            <td><?php echo $cita['id']; ?></td>
            <td><?php echo $cita['paciente']; ?></td>
            <td><?php echo $cita['doctor']; ?></td>
            <td><?php echo $cita['especialidad']; ?></td>
            <td><?php echo $cita['fecha']; ?></td>
            <td><?php echo $cita['hora']; ?></td>

            <td>
                <span class="estado <?php echo $cita['estado']; ?>">
                    <?php echo $cita['estado']; ?>
                </span>
            </td>

            <td class="acciones">
                <?php if($cita['estado'] == 'pendiente'): ?>
                    <a class="confirmar" href="?accion=confirmada&id=<?php echo $cita['id']; ?>">✔</a>
                    <a class="cancelar" href="?accion=cancelada&id=<?php echo $cita['id']; ?>">✖</a>
                <?php else: ?>
                    —
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

</div>

</body>
</html>