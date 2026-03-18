<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

// Traer todas las especialidades
$stmt = $pdo->query("SELECT * FROM especialidades ORDER BY id DESC");
$especialidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestionar Especialidades - Admin</title>
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
input, textarea{width:100%; padding:10px; margin:5px 0 15px 0; border-radius:8px; border:1px solid #ddd; font-size:14px;}
</style>
</head>
<body>

<?php include('sidebar.php'); ?>

<div class="main">
    <div class="card">
        <h2>Gestionar Especialidades</h2>

        <h3>Agregar Especialidad</h3>
        <form id="formEspecialidad">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <textarea name="descripcion" placeholder="Descripción"></textarea>
            <input type="number" name="precio" placeholder="Precio" required>
            <button type="submit">Agregar</button>
        </form>

        <h3>Especialidades Existentes</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
            <?php foreach($especialidades as $e): ?>
            <tr>
                <td><?= $e['id'] ?></td>
                <td><?= $e['nombre'] ?></td>
                <td><?= $e['descripcion'] ?></td>
                <td><?= $e['precio'] ?></td>
                <td>
                    <button onclick="editarEspecialidad(<?= $e['id'] ?>)">Editar</button>
                    <button onclick="borrarEspecialidad(<?= $e['id'] ?>)">Borrar</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.getElementById('formEspecialidad').addEventListener('submit', function(e){
    e.preventDefault();
    var formData = new FormData(this);
    axios.post('../ajax/agregar_especialidad.php', formData)
        .then(res=>{ alert(res.data.message); location.reload(); })
        .catch(err=>console.error(err));
});

function editarEspecialidad(id){
    var nuevoNombre = prompt("Nuevo nombre:");
    var nuevaDescripcion = prompt("Nueva descripción:");
    var nuevoPrecio = prompt("Nuevo precio:");
    if(nuevoNombre && nuevoPrecio){
        axios.post('../ajax/editar_especialidad.php',{
            id:id,
            nombre:nuevoNombre,
            descripcion:nuevaDescripcion,
            precio:nuevoPrecio
        }).then(res=>{ alert(res.data.message); location.reload(); })
          .catch(err=>console.error(err));
    }
}

function borrarEspecialidad(id){
    if(confirm("¿Desea eliminar esta especialidad?")){
        axios.post('../ajax/borrar_especialidad.php',{id:id})
        .then(res=>{ alert(res.data.message); location.reload(); })
        .catch(err=>console.error(err));
    }
}
</script>

</body>
</html>