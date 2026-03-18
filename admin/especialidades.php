<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM especialidades ORDER BY id DESC");
$especialidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Especialidades - Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">

<style>
.main{margin-left:220px; padding:40px;}
.card{background:#fff; padding:25px; border-radius:16px; box-shadow:0 8px 20px rgba(0,0,0,0.08);}
button{padding:10px 18px; border:none; border-radius:8px; background:#0a1f44; color:white; cursor:pointer;}
button:hover{background:#1e90ff;}
table{width:100%; margin-top:20px; border-collapse:collapse;}
th,td{padding:12px; border-bottom:1px solid #ddd;}
th{background:#f0f4f8;}
.modal{display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); justify-content:center; align-items:center;}
.modal-content{background:#fff; padding:30px; border-radius:16px; width:400px; position:relative;}
.close{position:absolute; top:10px; right:15px; cursor:pointer;}
input,textarea{width:100%; padding:10px; margin:10px 0; border-radius:8px; border:1px solid #ddd;}
</style>
</head>

<body>

<?php include("sidebar.php"); ?>

<div class="main">
<div class="card">

<h2>Especialidades</h2>
<button onclick="abrirAdd()">Agregar Especialidad</button>

<table>
<tr>
<th>Nombre</th>
<th>Descripción</th>
<th>Precio</th>
<th>Acciones</th>
</tr>

<?php foreach($especialidades as $e): ?>
<tr>
<td><?= $e['nombre'] ?></td>
<td><?= $e['descripcion'] ?></td>
<td>$<?= $e['precio'] ?></td>
<td>
<button onclick="abrirEdit(<?= $e['id'] ?>,'<?= $e['nombre'] ?>','<?= $e['descripcion'] ?>',<?= $e['precio'] ?>)">Editar</button>
<button onclick="eliminar(<?= $e['id'] ?>)">Eliminar</button>
</td>
</tr>
<?php endforeach; ?>

</table>

</div>
</div>

<!-- MODAL ADD -->
<div id="modalAdd" class="modal">
<div class="modal-content">
<span class="close" onclick="cerrarAdd()">&times;</span>
<h3>Agregar Especialidad</h3>

<form id="formAdd">
<input name="nombre" placeholder="Nombre" required>
<textarea name="descripcion" placeholder="Descripción"></textarea>
<input type="number" name="precio" placeholder="Precio" required>
<button>Guardar</button>
</form>
</div>
</div>

<!-- MODAL EDIT -->
<div id="modalEdit" class="modal">
<div class="modal-content">
<span class="close" onclick="cerrarEdit()">&times;</span>
<h3>Editar Especialidad</h3>

<form id="formEdit">
<input type="hidden" name="id" id="editId">
<input name="nombre" id="editNombre" required>
<textarea name="descripcion" id="editDesc"></textarea>
<input type="number" name="precio" id="editPrecio" required>
<button>Actualizar</button>
</form>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
// MODALES
function abrirAdd(){modalAdd.style.display='flex'}
function cerrarAdd(){modalAdd.style.display='none'}
function abrirEdit(id,n,d,p){
editId.value=id; editNombre.value=n; editDesc.value=d; editPrecio.value=p;
modalEdit.style.display='flex';
}
function cerrarEdit(){modalEdit.style.display='none'}

// AGREGAR
formAdd.onsubmit=e=>{
e.preventDefault();
axios.post('../ajax/agregar_especialidad.php', new FormData(formAdd))
.then(r=>{alert(r.data.message); location.reload();});
}

// EDITAR
formEdit.onsubmit=e=>{
e.preventDefault();
axios.post('../ajax/editar_especialidad.php', new FormData(formEdit))
.then(r=>{alert(r.data.message); location.reload();});
}

// ELIMINAR
function eliminar(id){
if(confirm("¿Eliminar especialidad?")){
axios.post('../ajax/eliminar_especialidad.php',{id:id})
.then(r=>{alert(r.data.message); location.reload();});
}
}
</script>

</body>
</html>