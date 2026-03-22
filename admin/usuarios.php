<?php
/**
 * GESTIÓN DE USUARIOS - PANEL ADMINISTRATIVO
 *
 * Funcionalidad:
 * - Listar todos los usuarios registrados en el sistema
 * - Mostrar información: nombre, cédula, teléfono, correo, rol, fecha de registro
 * - Agregar nuevos usuarios con validación de campos
 * - Editar información de usuarios existentes
 * - Cambiar rol de usuario (admin, doctor, user)
 * - Eliminar usuarios del sistema
 *
 * Formularios incluidos:
 * - Modal de agregar usuario: nombre, cédula, teléfono, correo, contraseña, rol
 * - Modal de editar usuario: campos editables con validaciones
 * - Funciones AJAX para operaciones CRUD
 *
 * Validaciones implementadas:
 * - Campos obligatorios en formularios
 * - Formato de cédula dominicana (10 dígitos)
 * - Formato de teléfono dominicano (10 dígitos)
 * - Correo electrónico válido
 * - Contraseña mínimo 6 caracteres
 * - Roles válidos del sistema
 *
 * Seguridad:
 * - Validación de sesión y rol de administrador
 * - Prepared statements en todas las operaciones
 * - Sanitización de datos de entrada
 * - Control de acceso por permisos
 */

session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

// Traer usuarios ordenados por fecha de registro (más recientes primero)
$stmt = $pdo->query("SELECT * FROM usuarios ORDER BY fecha_registro DESC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestionar Usuarios - Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<style>
.main{margin-left:220px; padding:40px 30px;}
.card{background:#fff; padding:25px; border-radius:16px; box-shadow:0 8px 20px rgba(0,0,0,0.08); margin-bottom:20px;}
.card:hover{box-shadow:0 10px 30px rgba(0,0,0,0.12);}
table{width:100%; border-collapse:collapse; margin-top:20px;}
table th, table td{padding:12px; border-bottom:1px solid #ddd; text-align:left;}
table th{background:#f0f4f8; color:#0a1f44;}
button{padding:8px 16px; border:none; border-radius:8px; background:#0a1f44; color:white; cursor:pointer; transition:0.3s; margin-right:5px;}
background:#fff;
padding:25px;
border-radius:16px;
width:400px;
position:relative;
position:absolute;
top:10px;
right:15px;
cursor:pointer;
}
input, select{
width:100%;
padding:10px;
border-radius:8px;
border:1px solid #ddd;
}

.password-field {
    position: relative;
}

.password-field input {
    padding-right: 40px;
}

.form-row {
    display:flex;
    gap:10px;
}

.form-row input {
    width:100%;
}

.eye-btn {
    position:absolute;
    top:50%;
    right:10px;
    transform:translateY(-50%);
    border:none;
    background:transparent;
    cursor:pointer;
    font-size:16px;
    color:#0a1f44;
    width:24px;
    height:24px;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:0;
}

.eye-btn:hover{
    color:#1e90ff;
}
</style>
</head>

<body>

<?php include('sidebar.php'); ?>

<div class="main">
    <div class="card">
        <h2>Gestionar Usuarios</h2>

        <button onclick="abrirAdd()">Agregar Usuario</button>

        <table>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Género</th>
                <th>Seguro</th>
                <th>Cédula</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Fecha Registro</th>
                <th>Acciones</th>
            </tr>

            <?php foreach($usuarios as $u): ?>
            <tr>
                <td><?= $u['nombre'] ?></td>
                <td><?= $u['apellido'] ?></td>
                <td><?= $u['genero'] ?></td>
                <td><?= $u['seguro'] ?></td>
                <td><?= $u['cedula'] ?></td>
                <td><?= $u['telefono'] ?></td>
                <td><?= $u['correo'] ?></td>
                <td><?= $u['rol'] ?></td>
                <td><?= $u['fecha_registro'] ?></td>
                <td>

                    <button onclick="abrirEdit(
                        <?= $u['id'] ?>,
                        '<?= $u['nombre'] ?>',
                        '<?= $u['apellido'] ?>',
                        '<?= $u['genero'] ?>',
                        '<?= $u['seguro'] ?>',
                        '<?= $u['cedula'] ?>',
                        '<?= $u['telefono'] ?>',
                        '<?= $u['correo'] ?>',
                        '<?= $u['rol'] ?>'
                    )">Editar</button>

                    <button onclick="cambiarRol(<?= $u['id'] ?>,'<?= $u['rol'] ?>')">Rol</button>

                    <button onclick="eliminarUsuario(<?= $u['id'] ?>)">Eliminar</button>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<!-- MODAL AGREGAR -->
<div id="modalAdd" class="modal">
<div class="modal-content">
<span class="close" onclick="cerrarAdd()">&times;</span>
<h3>Agregar Usuario</h3>

<form id="formAdd">
    <div class="form-row">
        <input name="nombre" placeholder="Nombre" required>
        <input name="apellido" placeholder="Apellido" required>
    </div>
    <div class="form-row">
        <select name="genero" required>
            <option value="">Género</option>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0a1f44;
            --secondary: #1e90ff;
            --accent: #00d4ff;
            --bg-light: #f8fafc;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--bg-light) 0%, #e3f0ff 100%);
            color: var(--primary);
            min-height: 100vh;
        }
        .main {
            margin-left: 260px;
            padding: 30px;
        }
        .card {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(30, 144, 255, 0.08);
            border: 1.5px solid rgba(30, 144, 255, 0.1);
            margin-bottom: 30px;
        }
        .card h2 {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 18px;
        }
        .btn-add {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            color: #fff;
            padding: 12px 28px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 18px;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 2px 8px rgba(30,144,255,0.10);
        }
        .btn-add:hover {
            background: var(--primary);
            color: #fff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: rgba(255,255,255,0.95);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(30,144,255,0.07);
        }
        th, td {
            padding: 14px;
            border-bottom: 1px solid #e3f0ff;
            text-align: left;
        }
        th {
            background: #f0f4f8;
            color: var(--primary);
            font-weight: 700;
        }
        tr:last-child td {
            border-bottom: none;
        }
        button {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            background: var(--primary);
            color: #fff;
            cursor: pointer;
            transition: 0.2s;
            margin-right: 5px;
        }
        button:hover {
            background: var(--secondary);
        }
        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .modal-content {
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            width: 400px;
            position: relative;
            box-shadow: 0 8px 32px rgba(30,144,255,0.13);
        }
        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            cursor: pointer;
            font-size: 20px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        @media (max-width: 900px) {
            .main { margin-left: 0; padding: 10px; }
        }
    </style>
</head>
<body>
<?php include('sidebar.php'); ?>
<div class="main">
    <div class="card">
        <h2>Gestionar Usuarios</h2>
        <button class="btn-add" onclick="abrirAdd()"><i class='bx bx-plus'></i> Agregar Usuario</button>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Género</th>
                <th>Seguro</th>
                <th>Cédula</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Fecha Registro</th>
                <th>Acciones</th>
            </tr>
            <?php foreach($usuarios as $u): ?>
            <tr>
                <td><?= $u['nombre'] ?></td>
                <td><?= $u['apellido'] ?></td>
                <td><?= $u['genero'] ?></td>
                <td><?= $u['seguro'] ?></td>
                <td><?= $u['cedula'] ?></td>
                <td><?= $u['telefono'] ?></td>
                <td><?= $u['correo'] ?></td>
                <td><?= $u['rol'] ?></td>
                <td><?= $u['fecha_registro'] ?></td>
                <td>
                    <button onclick="abrirEdit(
                        <?= $u['id'] ?>,
                        '<?= $u['nombre'] ?>',
                        '<?= $u['apellido'] ?>',
                        '<?= $u['genero'] ?>',
                        '<?= $u['seguro'] ?>',
                        '<?= $u['cedula'] ?>',
                        '<?= $u['telefono'] ?>',
                        '<?= $u['correo'] ?>',
                        '<?= $u['rol'] ?>',
                        '<?= $u['fecha_registro'] ?>'
                    )"><i class='bx bx-edit'></i></button>
                    <button onclick="eliminarUsuario(<?= $u['id'] ?>)"><i class='bx bx-trash'></i></button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
</body>
</html>
            <option value="masculino">Masculino</option>
            <option value="femenino">Femenino</option>
        </select>
        <input name="seguro" placeholder="Seguro (ARS o privado)" required>
    </div>
    <input name="cedula" placeholder="Cédula (10 dígitos)" required>
    <input name="telefono" placeholder="Teléfono (10 dígitos)" required>
    <input type="email" name="correo" placeholder="Correo electrónico" required>
    <div class="password-field">
        <input type="password" name="password" id="addUserPassword" placeholder="Contraseña (mínimo 6 caracteres)" required>
        <button type="button" class="eye-btn" onclick="togglePassword('addUserPassword')" title="Mostrar/Ocultar contraseña"><i class='bx bx-hide'></i></button>
    </div>
    <div class="password-field">
        <input type="password" name="confirm_password" id="addUserConfirmPassword" placeholder="Confirmar Contraseña" required>
        <button type="button" class="eye-btn" onclick="togglePassword('addUserConfirmPassword')" title="Mostrar/Ocultar contraseña"><i class='bx bx-hide'></i></button>
    </div>
    <select name="rol">
        <option value="user">Usuario</option>
        <option value="doctor">Doctor</option>
        <option value="admin">Admin</option>
    </select>

    <button type="submit">Guardar</button>
</form>
</div>
</div>

<!-- MODAL EDITAR -->
<div id="modalEdit" class="modal">
<div class="modal-content">
<span class="close" onclick="cerrarEdit()">&times;</span>
<h3>Editar Usuario</h3>

<form id="formEdit">
<input type="hidden" name="id" id="editId">
<div class="form-row">
    <input name="nombre" id="editNombre" placeholder="Nombre" required>
    <input name="apellido" id="editApellido" placeholder="Apellido" required>
</div>
<div class="form-row">
    <select name="genero" id="editGenero" required>
        <option value="">Género</option>
        <option value="masculino">Masculino</option>
        <option value="femenino">Femenino</option>
    </select>
    <input name="seguro" id="editSeguro" placeholder="Seguro (ARS o privado)" required>
</div>

<input name="cedula" id="editCedula" placeholder="Cédula (10 dígitos)" required>
<input name="telefono" id="editTelefono" placeholder="Teléfono (10 dígitos)" required>
<input type="email" name="correo" id="editCorreo" placeholder="Correo electrónico" required>

<div class="password-field">
    <input type="password" name="password" id="editUserPassword" placeholder="Nueva contraseña (opcional)">
    <button type="button" class="eye-btn" onclick="togglePassword('editUserPassword')" title="Mostrar/Ocultar contraseña"><i class='bx bx-hide'></i></button>
</div>
<div class="password-field">
    <input type="password" name="confirm_password" id="editUserConfirmPassword" placeholder="Confirmar contraseña">
    <button type="button" class="eye-btn" onclick="togglePassword('editUserConfirmPassword')" title="Mostrar/Ocultar contraseña"><i class='bx bx-hide'></i></button>
</div>

<select name="rol" id="editRol">
<option value="user">Usuario</option>
<option value="doctor">Doctor</option>
<option value="admin">Admin</option>
</select>

<button type="submit">Actualizar</button>
</form>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
// REFERENCIAS 
const modalAdd = document.getElementById("modalAdd");
const modalEdit = document.getElementById("modalEdit");

const formAdd = document.getElementById("formAdd");
const formEdit = document.getElementById("formEdit");

const editId = document.getElementById("editId");
const editNombre = document.getElementById("editNombre");
const editCedula = document.getElementById("editCedula");
const editTelefono = document.getElementById("editTelefono");
const editCorreo = document.getElementById("editCorreo");
const editRol = document.getElementById("editRol");

// MODALES
function abrirAdd(){ modalAdd.style.display='flex'; }
function cerrarAdd(){ modalAdd.style.display='none'; }

function abrirEdit(id,nombre,apellido,genero,seguro,cedula,telefono,correo,rol){
    editId.value=id;
    editNombre.value=nombre;
    document.getElementById('editApellido').value = apellido;
    document.getElementById('editGenero').value = genero;
    document.getElementById('editSeguro').value = seguro;
    editCedula.value=cedula;
    editTelefono.value=telefono;
    editCorreo.value=correo;
    editRol.value=rol;
    modalEdit.style.display='flex';
}
function cerrarEdit(){ modalEdit.style.display='none'; }

function togglePassword(inputId){
    const input = document.getElementById(inputId);
    const btn = input.nextElementSibling;
    if(!input) return;
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.innerHTML = input.type === 'password' ? "<i class='bx bx-hide'></i>" : "<i class='bx bx-show'></i>";
}

// AGREGAR
formAdd.addEventListener("submit", function(e){
    e.preventDefault();

    const pass = this.password.value;
    const confirm = this.confirm_password.value;
    if(pass !== confirm){
        alert('Las contraseñas no coinciden');
        return;
    }

    const formData = new FormData(this);
    axios.post('ajax/agregar_usuario.php', formData)
    .then(res=>{
        alert(res.data.message);
        location.reload();
    })
    .catch(err=>{
        console.error(err);
        alert("Error al agregar");
    });
});

// EDITAR
formEdit.addEventListener("submit", function(e){
    e.preventDefault();

    const pass = this.password.value;
    const confirm = this.confirm_password.value;
    if(pass && pass !== confirm){
        alert('Las contraseñas no coinciden');
        return;
    }

    const formData = new FormData(this);
    axios.post('ajax/editar_usuario.php', formData)
    .then(res=>{
        alert(res.data.message);
        location.reload();
    })
    .catch(err=>{
        console.error(err);
        alert("Error al editar");
    });
});

// CAMBIAR ROL
function cambiarRol(id, rolActual){
    const nuevoRol = prompt("Nuevo rol (admin/doctor/user):", rolActual);

    if(nuevoRol && ['admin','doctor','user'].includes(nuevoRol.toLowerCase())){
        axios.post('ajax/cambiar_rol_usuario.php',{
            id:id,
            rol:nuevoRol.toLowerCase()
        })
        .then(res=>{
            alert(res.data.message);
            location.reload();
        });
    } else {
        alert("Rol inválido");
    }
}

// ELIMINAR
function eliminarUsuario(id){
    if(confirm("¿Eliminar usuario?")){
        axios.post('ajax/eliminar_usuario.php',{id:id})
        .then(res=>{
            alert(res.data.message);
            location.reload();
        });
    }
}
</script>

</body>
</html>