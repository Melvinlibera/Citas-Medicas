<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

// Traer doctores (IMPORTANTE: traer id_especialidad)
$stmt = $pdo->query("
    SELECT d.id, d.nombre AS doctor, d.id_especialidad, e.nombre AS especialidad
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
table{width:100%; border-collapse:collapse; margin-top:20px;}
table th, table td{padding:12px; border-bottom:1px solid #ddd; text-align:left;}
table th{background:#f0f4f8; color:#0a1f44;}

/* MODAL */
.modal{
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.6);
    justify-content:center;
    align-items:center;
}
.modal-content{
    background:#fff;
    padding:30px;
    border-radius:16px;
    width:400px;
    position:relative;
}
.close{
    position:absolute;
    top:10px;
    right:15px;
    cursor:pointer;
    font-size:20px;
}
input, select{
    width:100%;
    padding:10px;
    margin:10px 0;
    border-radius:8px;
    border:1px solid #ddd;
}
</style>
</head>

<body>

<?php include('sidebar.php'); ?>

<div class="main">
    <div class="card">
        <h2>Doctores</h2>

        <button onclick="abrirModalAdd()">Agregar Doctor</button>

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
                    <button onclick="abrirModalEdit(<?= $d['id'] ?>, '<?= $d['doctor'] ?>', <?= $d['id_especialidad'] ?>)">Editar</button>
                    <button onclick="eliminarDoctor(<?= $d['id'] ?>)">Eliminar</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<!-- ================= MODAL AGREGAR ================= -->
<div id="modalAdd" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModalAdd()">&times;</span>
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

<!-- ================= MODAL EDITAR ================= -->
<div id="modalEdit" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModalEdit()">&times;</span>
        <h3>Editar Doctor</h3>

        <form id="formEditDoctor">
            <input type="hidden" name="id" id="editId">

            <input type="text" name="nombre" id="editNombre" required>

            <select name="id_especialidad" id="editEspecialidad" required>
                <option value="">Selecciona Especialidad</option>
                <?php
                $esp = $pdo->query("SELECT * FROM especialidades");
                while($e = $esp->fetch(PDO::FETCH_ASSOC)){
                    echo "<option value='{$e['id']}'>{$e['nombre']}</option>";
                }
                ?>
            </select>

            <button type="submit">Guardar cambios</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
// MODALES
function abrirModalAdd(){ document.getElementById('modalAdd').style.display='flex'; }
function cerrarModalAdd(){ document.getElementById('modalAdd').style.display='none'; }

function abrirModalEdit(id,nombre,idEsp){
    document.getElementById('editId').value = id;
    document.getElementById('editNombre').value = nombre;
    document.getElementById('editEspecialidad').value = idEsp;
    document.getElementById('modalEdit').style.display='flex';
}
function cerrarModalEdit(){ document.getElementById('modalEdit').style.display='none'; }

// AGREGAR
document.getElementById('formAddDoctor').addEventListener('submit', function(e){
    e.preventDefault();
    const form = new FormData(this);

    axios.post('../ajax/agregar_doctor.php', form)
    .then(res=>{
        alert(res.data.message);
        location.reload();
    }).catch(err=>console.error(err));
});

// EDITAR
document.getElementById('formEditDoctor').addEventListener('submit', function(e){
    e.preventDefault();
    const form = new FormData(this);

    axios.post('../ajax/editar_doctor.php', form)
    .then(res=>{
        alert(res.data.message);
        location.reload();
    }).catch(err=>console.error(err));
});

// ELIMINAR
function eliminarDoctor(id){
    if(confirm("¿Eliminar este doctor?")){
        axios.post('../ajax/eliminar_doctor.php',{id:id})
        .then(res=>{
            alert(res.data.message);
            location.reload();
        }).catch(err=>console.error(err));
    }
}
</script>

</body>
</html>