<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

// Traer doctores
$stmt = $pdo->query("
    SELECT d.id, d.nombre AS doctor, e.nombre AS especialidad
    FROM doctores d
    LEFT JOIN especialidades e ON d.id_especialidad = e.id
");
$doctores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestionar Doctores - Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<style>
.main{margin-left:220px; padding:40px 30px;}
.card{background:#fff; padding:25px; border-radius:16px; box-shadow:0 8px 20px rgba(0,0,0,0.08); margin-bottom:20px;}
.card:hover{box-shadow:0 10px 30px rgba(0,0,0,0.12);}
button{padding:10px 18px; border:none; border-radius:8px; background:#0a1f44; color:white; cursor:pointer; transition:0.3s;}
button:hover{background:#1e90ff;}
table{width:100%; border-collapse:collapse;}
table th, table td{padding:12px; border-bottom:1px solid #ddd; text-align:left;}
table th{background:#f0f4f8; color:#0a1f44;}
</style>
</head>
<body>

<?php include('sidebar.php'); ?> <!-- Sidebar común -->

<div class="main">
    <div class="card">
        <h2>Doctores</h2>

        <button onclick="document.getElementById('modalAdd').style.display='flex'">Agregar Doctor</button>

        <table>
            <tr>
                <th>Nombre</th>
                <th>Especialidad</th>
                <th>Acciones</th>
            </tr>
            <?php foreach($doctores as $d): ?>
            <tr>
                <td><?= $d['doctor'] ?></td>
                <td><?= $d['especialidad'] ?></td>
                <td>
                    <button onclick="editarDoctor(<?= $d['id'] ?>, '<?= $d['doctor'] ?>', <?= $d['especialidad'] ?>)">Editar</button>
                    <button onclick="eliminarDoctor(<?= $d['id'] ?>)">Eliminar</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<!-- Modal Agregar -->
<div id="modalAdd" style="display:none; position:fixed; top:0; left:0;width:100%;height:100%; background:rgba(0,0,0,0.6); justify-content:center; align-items:center;">
    <div style="background:#fff; padding:30px; border-radius:16px; width:400px; position:relative;">
        <span style="position:absolute; top:10px; right:15px; cursor:pointer;" onclick="document.getElementById('modalAdd').style.display='none'">&times;</span>
        <h3>Agregar Doctor</h3>
        <form id="formAddDoctor">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <select name="id_especialidad" required>
                <option value="">Selecciona Especialidad</option>
                <?php
                $esp = $pdo->query("SELECT * FROM especialidades");
                while($e = $esp->fetch(PDO::FETCH_ASSOC)){
                    echo "<option value='{$e['id']}'>{$e['nombre']}</option>";
                }
                ?>
            </select>
            <button type="submit">Agregar</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
// Agregar Doctor
document.getElementById('formAddDoctor').addEventListener('submit', function(e){
    e.preventDefault();
    const form = new FormData(this);
    axios.post('../ajax/agregar_doctor.php', form)
    .then(res=>{
        alert(res.data.message);
        location.reload();
    }).catch(err=>{console.error(err);});
});

function editarDoctor(id,nombre,especialidad){
    const nombreNew = prompt("Editar nombre del doctor:",nombre);
    const idEsp = prompt("ID de Especialidad:",especialidad);
    if(nombreNew && idEsp){
        axios.post('../ajax/editar_doctor.php',{id:id,nombre:nombreNew,id_especialidad:idEsp})
        .then(res=>{alert(res.data.message); location.reload();})
        .catch(err=>console.error(err));
    }
}

function eliminarDoctor(id){
    if(confirm("¿Desea eliminar este doctor?")){
        axios.post('../ajax/eliminar_doctor.php',{id:id})
        .then(res=>{alert(res.data.message); location.reload();})
        .catch(err=>console.error(err));
    }
}
</script>
</body>
</html>