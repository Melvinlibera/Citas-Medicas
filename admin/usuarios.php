<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

// Traer usuarios
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

/* MODALES */
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
        <h2>Gestionar Usuarios</h2>

        <!-- BOTÓN NUEVO -->
        <button onclick="abrirAdd()">Agregar Usuario</button>

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

                    <!-- EDITAR -->
                    <button onclick="abrirEdit(
                        <?= $u['id'] ?>,
                        '<?= $u['nombre'] ?>',
                        '<?= $u['cedula'] ?>',
                        '<?= $u['correo'] ?>',
                        '<?= $u['rol'] ?>'
                    )">Editar</button>

                    <!-- CAMBIAR ROL (LO DEJAMOS PERO MEJOR) -->
                    <button onclick="cambiarRol(<?= $u['id'] ?>,'<?= $u['rol'] ?>')">Rol</button>

                    <!-- ELIMINAR -->
                    <button onclick="eliminarUsuario(<?= $u['id'] ?>)">Eliminar</button>

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
<h3>Agregar Usuario</h3>

<form id="formAdd">
<input name="nombre" placeholder="Nombre" required>
<input name="cedula" placeholder="Cédula" required>
<input type="email" name="correo" placeholder="Correo" required>
<input type="password" name="password" placeholder="Contraseña" required>

<select name="rol">
<option value="user">Usuario</option>
<option value="admin">Admin</option>
</select>

<button>Guardar</button>
</form>
</div>
</div>

<!-- ================= MODAL EDITAR ================= -->
<div id="modalEdit" class="modal">
<div class="modal-content">
<span class="close" onclick="cerrarEdit()">&times;</span>
<h3>Editar Usuario</h3>

<form id="formEdit">
<input type="hidden" name="id" id="editId">

<input name="nombre" id="editNombre" required>
<input name="cedula" id="editCedula" required>
<input type="email" name="correo" id="editCorreo" required>

<input type="password" name="password" placeholder="Nueva contraseña (opcional)">

<select name="rol" id="editRol">
<option value="user">Usuario</option>
<option value="admin">Admin</option>
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

function abrirEdit(id,nombre,cedula,correo,rol){
editId.value=id;
editNombre.value=nombre;
editCedula.value=cedula;
editCorreo.value=correo;
editRol.value=rol;
modalEdit.style.display='flex';
}
function cerrarEdit(){modalEdit.style.display='none'}

// AGREGAR
formAdd.onsubmit=e=>{
e.preventDefault();
axios.post('../ajax/agregar_usuario.php', new FormData(formAdd))
.then(r=>{alert(r.data.message); location.reload();});
}

// EDITAR
formEdit.onsubmit=e=>{
e.preventDefault();
axios.post('../ajax/editar_usuario.php', new FormData(formEdit))
.then(r=>{alert(r.data.message); location.reload();});
}

// CAMBIAR ROL (MEJORADO)
function cambiarRol(id, rolActual){
const nuevoRol = prompt("Nuevo rol (admin/user):", rolActual);

if(nuevoRol && ['admin','user'].includes(nuevoRol.toLowerCase())){
axios.post('../ajax/cambiar_rol_usuario.php',{id:id,rol:nuevoRol.toLowerCase()})
.then(res=>{alert(res.data.message); location.reload();});
} else {
alert("Rol inválido");
}
}

// ELIMINAR (PROTECCIÓN BÁSICA)
function eliminarUsuario(id){
if(confirm("¿Eliminar usuario?")){
axios.post('../ajax/eliminar_usuario.php',{id:id})
.then(res=>{alert(res.data.message); location.reload();});
}
}
</script>

</body>
</html>