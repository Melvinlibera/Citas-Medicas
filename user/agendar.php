<?php
session_start();
include("../config/db.php");

// Verifica login
if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

$success = "";
$error = "";

// Guardar cita si se envía el formulario
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $fecha = $_POST['fecha'] ?? '';
    $id_especialidad = $_POST['especialidad'] ?? '';
    $id_doctor = $_POST['doctor'] ?? '';
    $hora = $_POST['hora'] ?? '';

    // Validaciones
    if(!$fecha || !$id_especialidad || !$id_doctor || !$hora){
        $error = "Todos los campos son obligatorios";
    } elseif(strtotime($fecha) < strtotime(date('Y-m-d'))){
        $error = "No se puede agendar una fecha pasada";
    } else {
        // Revisar si ya hay cita del mismo doctor en la misma fecha y hora
        $stmt = $pdo->prepare("SELECT * FROM citas WHERE id_doctor=? AND fecha=? AND hora=?");
        $stmt->execute([$id_doctor, $fecha, $hora]);
        if($stmt->fetch()){
            $error = "Ese horario ya está ocupado";
        } else {
            // Insertar cita
            $stmt = $pdo->prepare("INSERT INTO citas (id_usuario, id_especialidad, id_doctor, fecha, hora, estado) VALUES (?,?,?,?,?,?)");
            $stmt->execute([
                $_SESSION['id'],
                $id_especialidad,
                $id_doctor,
                $fecha,
                $hora,
                'pendiente'
            ]);
            $success = "Cita agendada correctamente";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Agendar Cita - Hospital & Human</title>
<link rel="stylesheet" href="../assets/css/style.css">

<style>
.agendar-container {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #0a1f44, #1e90ff);
    padding: 20px;
}

.agendar-box {
    background: white;
    padding: 40px;
    border-radius: 16px;
    width: 400px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    text-align: center;
}

.agendar-box h2 {
    margin-bottom: 20px;
    color: #0a1f44;
}

.agendar-box select,
.agendar-box input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 14px;
}

.agendar-box button {
    width: 100%;
    padding: 12px;
    margin-top: 10px;
    border-radius: 8px;
    border: none;
    background: #0a1f44;
    color: white;
    cursor: pointer;
    transition: 0.3s;
}

.agendar-box button:hover {
    background: #1e90ff;
}

.error { color: red; margin-bottom: 10px; font-size: 14px; }
.success { color: green; margin-bottom: 10px; font-size: 14px; }
</style>
</head>
<body>

<div class="agendar-container">
    <div class="agendar-box">
        <h2>Agendar Cita</h2>

        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <select name="especialidad" id="especialidad" required>
                <option value="">Selecciona Especialidad</option>
                <?php
                $stmt = $pdo->query("SELECT * FROM especialidades");
                while($esp = $stmt->fetch()){
                    echo "<option value='{$esp['id']}'>{$esp['nombre']}</option>";
                }
                ?>
            </select>

            <select name="doctor" id="doctor" required>
                <option value="">Selecciona Doctor</option>
            </select>

            <input type="date" name="fecha" required>
            <input type="time" name="hora" required>

            <button type="submit">Agendar</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
// Cambiar médicos según especialidad
document.getElementById('especialidad').addEventListener('change', function(){
    var espId = this.value;
    var doctorSelect = document.getElementById('doctor');
    doctorSelect.innerHTML = "<option value=''>Cargando...</option>";

    axios.post('get_doctores.php', { id_especialidad: espId })
    .then(function(res){
        var docs = res.data;
        doctorSelect.innerHTML = "<option value=''>Selecciona Doctor</option>";
        docs.forEach(d => {
            var opt = document.createElement('option');
            opt.value = d.id;
            opt.textContent = d.nombre;
            doctorSelect.appendChild(opt);
        });
    })
    .catch(err=>{
        console.error(err);
        doctorSelect.innerHTML = "<option value=''>Error al cargar</option>";
    });
});
</script>

</body>
</html>