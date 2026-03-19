<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

// Traer citas
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

// Datos para selects
$usuarios = $pdo->query("SELECT id, nombre FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
$doctores = $pdo->query("SELECT id, nombre FROM doctores")->fetchAll(PDO::FETCH_ASSOC);
$especialidades = $pdo->query("SELECT id, nombre FROM especialidades")->fetchAll(PDO::FETCH_ASSOC);
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
padding:25px;
border-radius:16px;
width:400px;
position:relative;
}
.close{
position:absolute;
top:10px;
right:15px;
cursor:pointer;
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
        <h2>Gestionar Citas</h2>

        <!-- BOTÓN NUEVO -->
        <button onclick="abrirAdd()">Agregar Cita</button>

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
                    <!-- abre modal -->
                    <button onclick="abrirEdit(<?= $c['id'] ?>,'<?= $c['fecha'] ?>','<?= $c['hora'] ?>','<?= $c['estado'] ?>')">Editar</button>

                    <button onclick="eliminarCita(<?= $c['id'] ?>)">Eliminar</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<!-- ================= MODAL AGREGAR ================= -->
<div id="modalAdd" class="modal">
<div class="modal-content">
<span class="close" onclick="cerrarAdd()">&times;</span>
<h3>Agregar Cita</h3>

<form id="formAdd">
<select name="id_usuario" required>
<option value="">Usuario</option>
<?php foreach($usuarios as $u): ?>
<option value="<?= $u['id'] ?>"><?= $u['nombre'] ?></option>
<?php endforeach; ?>
</select>

<select name="id_doctor" required>
<option value="">Doctor</option>
<?php foreach($doctores as $d): ?>
<option value="<?= $d['id'] ?>"><?= $d['nombre'] ?></option>
<?php endforeach; ?>
</select>

<select name="id_especialidad" required>
<option value="">Especialidad</option>
<?php foreach($especialidades as $e): ?>
<option value="<?= $e['id'] ?>"><?= $e['nombre'] ?></option>
<?php endforeach; ?>
</select>

<input type="date" name="fecha" required>
<input type="time" name="hora" required>

<button>Guardar</button>
</form>
</div>
</div>

<!-- ================= MODAL EDITAR ================= -->
<div id="modalEdit" class="modal">
<div class="modal-content">
<span class="close" onclick="cerrarEdit()">&times;</span>
<h3>Editar Cita</h3>

<form id="formEdit">
<input type="hidden" name="id" id="editId">

<input type="date" name="fecha" id="editFecha" required>
<input type="time" name="hora" id="editHora" required>

<select name="estado" id="editEstado">
<option value="pendiente">Pendiente</option>
<option value="confirmada">Confirmada</option>
<option value="cancelada">Cancelada</option>
</select>

<button>Actualizar</button>
</form>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
// MODALES
function abrirAdd(){modalAdd.style.display='flex'}
function cerrarAdd(){modalAdd.style.display='none'}

function abrirEdit(id,fecha,hora,estado){
editId.value=id;
editFecha.value=fecha;
editHora.value=hora;
editEstado.value=estado;
modalEdit.style.display='flex';
}
function cerrarEdit(){modalEdit.style.display='none'}

// AGREGAR
formAdd.onsubmit=e=>{
e.preventDefault();
axios.post('../ajax/agregar_cita.php', new FormData(formAdd))
.then(r=>{alert(r.data.message); location.reload();});
}

// EDITAR
formEdit.onsubmit=e=>{
e.preventDefault();
axios.post('../ajax/editar_cita.php', new FormData(formEdit))
.then(r=>{alert(r.data.message); location.reload();});
}

// ELIMINAR
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