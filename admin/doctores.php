<?php
/**
 * GESTIÓN DE DOCTORES - PANEL ADMINISTRATIVO
 *
 * Funcionalidad:
 * - Listar todos los doctores registrados en el sistema
 * - Mostrar información: nombre del doctor y especialidad médica
 * - Agregar nuevos doctores con enlace automático a especialidad
 * - Editar información del doctor y cambiar especialidad
 * - Eliminar doctores del sistema
 *
 * Relaciones importantes:
 * - Cada doctor está asociado a UNA especialidad médica
 * - Los doctores son usuarios del sistema (rol = 'doctor')
 * - La relación doctor-especialidad es crítica para el agendamiento
 *
 * Formularios incluidos:
 * - Modal de agregar doctor: nombre, cédula, correo, especialidad
 * - Modal de editar doctor: nombre y especialidad editable
 * - Funciones AJAX para operaciones CRUD
 *
 * Proceso de creación de doctor:
 * 1. Se crea el usuario con rol 'doctor'
 * 2. Se crea el registro en tabla doctores
 * 3. Se enlaza automáticamente con la especialidad seleccionada
 * 4. Contraseña por defecto: 123456 (debe cambiarse al primer login)
 *
 * Validaciones implementadas:
 * - Campos obligatorios en formularios
 * - Formato de cédula dominicana (10 dígitos)
 * - Correo electrónico válido y único
 * - Especialidad debe existir en el sistema
 * - Contraseña mínimo 6 caracteres
 *
 * Seguridad:
 * - Validación de sesión y rol de administrador
 * - Prepared statements en todas las operaciones
 * - Control de integridad referencial
 */

session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

// Traer doctores con información de especialidad
// IMPORTANTE: JOIN con especialidades para mostrar nombre de especialidad
$stmt = $pdo->query("
    SELECT d.id, d.nombre AS doctor, d.id_especialidad, e.nombre AS especialidad,
           u.cedula, u.telefono, u.correo, u.genero, u.rol
    FROM doctores d
    LEFT JOIN usuarios u ON u.id = d.id_usuario
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
        <h2>Doctores</h2>

        <button onclick="abrirModalAdd()">Agregar Doctor</button>

        <table>
            <tr>
                <th>Nombre</th>
                <th>Cédula</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Género</th>
                <th>Rol</th>
                <th>Especialidad</th>
                <th>Acciones</th>
            </tr>

            <?php foreach($doctores as $d): ?>
            <tr>
                <td><?= htmlspecialchars($d['doctor']) ?></td>
                <td><?= htmlspecialchars($d['cedula'] ?? '-') ?></td>
                <td><?= htmlspecialchars($d['telefono'] ?? '-') ?></td>
                <td><?= htmlspecialchars($d['correo'] ?? '-') ?></td>
                <td><?= htmlspecialchars($d['genero'] ?? '-') ?></td>
                <td><?= htmlspecialchars($d['rol'] ?? '-') ?></td>
                <td><?= htmlspecialchars($d['especialidad'] ?? '-') ?></td>
                <td>
                    <button onclick="abrirModalEdit(
                        <?= $d['id'] ?>,
                        '<?= addslashes($d['doctor']) ?>',
                        '<?= addslashes($d['cedula'] ?? '') ?>',
                        '<?= addslashes($d['telefono'] ?? '') ?>',
                        '<?= addslashes($d['correo'] ?? '') ?>',
                        '<?= addslashes($d['genero'] ?? '') ?>',
                        '<?= addslashes($d['rol'] ?? '') ?>',
                        <?= $d['id_especialidad'] ?>
                    )">Editar</button>
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
            <div class="form-row">
                <input type="text" name="nombre" placeholder="Nombre" required>
                <input type="text" name="apellido" placeholder="Apellido" required>
            </div>
            <input type="text" name="cedula" placeholder="Cédula (XXX-XXXXXXX-X)" required>
            <input type="tel" name="telefono" placeholder="Teléfono (10 dígitos)" required>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <select name="genero" required>
                <option value="">Selecciona Género</option>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
            </select>
            <select name="rol" required>
                <option value="">Selecciona Rol</option>
                <option value="user">Usuario</option>
                <option value="doctor">Doctor</option>
                <option value="admin">Admin</option>
            </select>
            <div class="form-row">
                <div class="password-field">
                    <input type="password" name="password" id="addDoctorPassword" placeholder="Contraseña (mínimo 6 caracteres)" required>
                    <button type="button" class="eye-btn" onclick="togglePassword('addDoctorPassword')" title="Mostrar/Ocultar contraseña"><i class='bx bx-hide'></i></button>
                </div>
                <div class="password-field">
                    <input type="password" name="confirm_password" id="addDoctorConfirmPassword" placeholder="Confirmar contraseña" required>
                    <button type="button" class="eye-btn" onclick="togglePassword('addDoctorConfirmPassword')" title="Mostrar/Ocultar contraseña"><i class='bx bx-hide'></i></button>
                </div>
            </div>
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
            <div class="form-row">
                <input type="text" name="nombre" id="editNombre" placeholder="Nombre" required>
                <input type="text" name="apellido" id="editApellido" placeholder="Apellido" required>
            </div>
            <input type="text" name="cedula" id="editCedula" placeholder="Cédula (XXX-XXXXXXX-X)" required>
            <input type="tel" name="telefono" id="editTelefono" placeholder="Teléfono (10 dígitos)" required>
            <input type="email" name="correo" id="editCorreo" placeholder="Correo electrónico" required>
            <select name="genero" id="editGenero" required>
                <option value="">Selecciona Género</option>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
            </select>
            <select name="rol" id="editRol" required>
                <option value="">Selecciona Rol</option>
                <option value="user">Usuario</option>
                <option value="doctor">Doctor</option>
                <option value="admin">Admin</option>
            </select>
            <select name="id_especialidad" id="editEspecialidad" required>
                <option value="">Selecciona Especialidad</option>
                <?php
                $esp = $pdo->query("SELECT * FROM especialidades");
                while($e = $esp->fetch(PDO::FETCH_ASSOC)){
                    echo "<option value='{$e['id']}'>{$e['nombre']}</option>";
                }
                ?>
            </select>
            <div class="form-row">
                <div class="password-field">
                    <input type="password" name="password" id="editDoctorPassword" placeholder="Nueva contraseña (opcional)">
                    <button type="button" class="eye-btn" onclick="togglePassword('editDoctorPassword')" title="Mostrar/Ocultar contraseña"><i class='bx bx-hide'></i></button>
                </div>
                <div class="password-field">
                    <input type="password" name="confirm_password" id="editDoctorConfirmPassword" placeholder="Confirmar contraseña">
                    <button type="button" class="eye-btn" onclick="togglePassword('editDoctorConfirmPassword')" title="Mostrar/Ocultar contraseña"><i class='bx bx-hide'></i></button>
                </div>
            </div>
            <button type="submit">Guardar cambios</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
// MODALES
function abrirModalAdd(){ document.getElementById('modalAdd').style.display='flex'; }
function cerrarModalAdd(){ document.getElementById('modalAdd').style.display='none'; }

function abrirModalEdit(id, nombreCompleto, cedula, telefono, correo, genero, rol, idEsp){
    const parts = nombreCompleto.trim().split(' ');
    const apellido = parts.length > 1 ? parts.slice(1).join(' ') : '';
    const nombre = parts[0] || '';

    document.getElementById('editId').value = id;
    document.getElementById('editNombre').value = nombre;
    document.getElementById('editApellido').value = apellido;
    document.getElementById('editCedula').value = cedula;
    document.getElementById('editTelefono').value = telefono;
    document.getElementById('editCorreo').value = correo;
    document.getElementById('editGenero').value = genero;
    document.getElementById('editRol').value = rol;
    document.getElementById('editEspecialidad').value = idEsp;
    document.getElementById('modalEdit').style.display='flex';
}
function cerrarModalEdit(){ document.getElementById('modalEdit').style.display='none'; }

function togglePassword(inputId){
    const input = document.getElementById(inputId);
    const btn = input.nextElementSibling;
    if(!input) return;
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.innerHTML = input.type === 'password' ? "<i class='bx bx-hide'></i>" : "<i class='bx bx-show'></i>";
}

// AGREGAR
document.getElementById('formAddDoctor').addEventListener('submit', function(e){
    e.preventDefault();
    const password = this.password.value;
    const confirmPassword = this.confirm_password.value;

    if(password !== confirmPassword){
        alert('Las contraseñas no coinciden');
        return;
    }

    const form = new FormData(this);
    const fullName = `${form.get('nombre').trim()} ${form.get('apellido').trim()}`.trim();
    form.set('nombre', fullName);

    axios.post('ajax/agregar_doctor.php', form)
    .then(res=>{
        alert(res.data.message);
        location.reload();
    }).catch(err=>console.error(err));
});

// EDITAR
document.getElementById('formEditDoctor').addEventListener('submit', function(e){
    e.preventDefault();
    const password = this.password.value;
    const confirmPassword = this.confirm_password.value;

    if(password && password !== confirmPassword){
        alert('Las contraseñas no coinciden');
        return;
    }

    const form = new FormData(this);
    const fullName = `${form.get('nombre').trim()} ${form.get('apellido').trim()}`.trim();
    form.set('nombre', fullName);

    axios.post('ajax/editar_doctor.php', form)
    .then(res=>{
        alert(res.data.message);
        location.reload();
    }).catch(err=>console.error(err));
});

// ELIMINAR
function eliminarDoctor(id){
    if(confirm("¿Eliminar este doctor?")){
        axios.post('ajax/eliminar_doctor.php',{id:id})
        .then(res=>{
            alert(res.data.message);
            location.reload();
        }).catch(err=>console.error(err));
    }
}
</script>

</body>
</html>