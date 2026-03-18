<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

// Traer todos los usuarios
$stmt = $pdo->query("SELECT * FROM usuarios ORDER BY fecha_registro DESC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestionar Usuarios - Admin</title>
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
        <h2>Gestionar Usuarios</h2>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Cédula</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Fecha Registro</th>
                <th>Acciones</th>
            </tr>
            <?php foreach($usuarios as $u): ?>
            <tr>
                <td><?= $u['nombre'] ?></td>
                <td><?= $u['cedula'] ?></td>
                <td><?= $u['correo'] ?></td>
                <td><?= $u['rol'] ?></td>
                <td><?= $u['fecha_registro'] ?></td>
                <td>
                    <button onclick="cambiarRol(<?= $u['id'] ?>,'<?= $u['rol'] ?>')">Cambiar Rol</button>
                    <button onclick="eliminarUsuario(<?= $u['id'] ?>)">Eliminar</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
function cambiarRol(id, rolActual){
    const nuevoRol = prompt("Nuevo rol (admin/user):", rolActual);
    if(nuevoRol && ['admin','user'].includes(nuevoRol.toLowerCase())){
        axios.post('../ajax/cambiar_rol_usuario.php',{id:id,rol:nuevoRol.toLowerCase()})
        .then(res=>{alert(res.data.message); location.reload();})
        .catch(err=>console.error(err));
    } else {
        alert("Rol inválido");
    }
}

function eliminarUsuario(id){
    if(confirm("¿Desea eliminar este usuario?")){
        axios.post('../ajax/eliminar_usuario.php',{id:id})
        .then(res=>{alert(res.data.message); location.reload();})
        .catch(err=>console.error(err));
    }
}
</script>
</body>
</html>