<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

// Traer todas las citas con usuario, doctor y especialidad
$stmt = $pdo->query("
    SELECT citas.*, usuarios.nombre AS usuario_nombre,
           doctores.nombre AS doctor_nombre,
           especialidades.nombre AS especialidad_nombre
    FROM citas
    JOIN usuarios ON citas.id_usuario = usuarios.id
    JOIN doctores ON citas.id_doctor = doctores.id
    JOIN especialidades ON citas.id_especialidad = especialidades.id
    ORDER BY citas.fecha DESC, citas.hora DESC
");
$citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestionar Citas - Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
.main{margin-left:220px; padding:40px 30px;}
.card{background:#fff; padding:25px; border-radius:16px; box-shadow:0 8px 20px rgba(0,0,0,0.08); margin-bottom:20px;}
.card:hover{box-shadow:0 10px 30px rgba(0,0,0,0.12);}
table{width:100%; border-collapse:collapse; margin-top:20px;}
table th, table td{padding:12px; border-bottom:1px solid #ddd; text-align:left;}
table th{background:#f0f4f8; color:#0a1f44;}
button{padding:8px 16px; border:none; border-radius:8px; background:#0a1f44; color:white; cursor:pointer; transition:0.3s; margin-right:5px;}
button:hover{background:#1e90ff;}
</style>
</head>
<body>

<?php include('sidebar.php'); ?>

<div class="main">
    <div class="card">
        <h2>Gestionar Citas</h2>
        <table>
            <tr>
                <th>Usuario</th>
                <th>Doctor</th>
                <th>Especialidad</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            <?php foreach($citas as $c): ?>
            <tr>
                <td><?= $c['usuario_nombre'] ?></td>
                <td><?= $c['doctor_nombre'] ?></td>
                <td><?= $c['especialidad_nombre'] ?></td>
                <td><?= $c['fecha'] ?></td>
                <td><?= $c['hora'] ?></td>
                <td><?= ucfirst($c['estado']) ?></td>
                <td>
                    <button onclick="cambiarEstado(<?= $c['id'] ?>)">Cambiar Estado</button>
                    <button onclick="eliminarCita(<?= $c['id'] ?>)">Eliminar</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
// Cambiar estado
function cambiarEstado(id){
    const nuevoEstado = prompt("Nuevo estado (pendiente, confirmada, cancelada):");
    if(nuevoEstado && ['pendiente','confirmada','cancelada'].includes(nuevoEstado.toLowerCase())){
        axios.post('../ajax/cambiar_estado_cita.php',{id:id,estado:nuevoEstado.toLowerCase()})
        .then(res=>{alert(res.data.message); location.reload();})
        .catch(err=>console.error(err));
    } else {
        alert("Estado inválido");
    }
}

// Eliminar cita
function eliminarCita(id){
    if(confirm("¿Desea eliminar esta cita?")){
        axios.post('../ajax/eliminar_cita.php',{id:id})
        .then(res=>{alert(res.data.message); location.reload();})
        .catch(err=>console.error(err));
    }
}
</script>
</body>
</html>