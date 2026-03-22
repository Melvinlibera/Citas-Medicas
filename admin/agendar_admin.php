<?php
session_start();
include("../config/db.php");
if(!isset($_SESSION['rol']) || $_SESSION['rol']!='admin'){
    header("Location: ../auth/login.php");
    exit();
}

if($_POST){
    $id_usuario = $_POST['usuario'];
    $id_doctor = $_POST['doctor'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    $stmt = $pdo->prepare("INSERT INTO citas (id_usuario, id_doctor, fecha, hora, estado) VALUES (?,?,?,?,?)");
    $stmt->execute([$id_usuario,$id_doctor,$fecha,$hora,'pendiente']);

    echo "<p style='color:green'>Cita agendada correctamente</p>";
}
$usuarios = $pdo->query("SELECT id,nombre FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
$especialidades = $pdo->query("SELECT * FROM especialidades")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Agendar Cita (Admin)</h2>
<form method="POST">
<select name="usuario" required>
<option value="">Selecciona Usuario</option>
<?php foreach($usuarios as $u): ?>
<option value="<?php echo $u['id'];?>"><?php echo $u['nombre'];?></option>
<?php endforeach;?>
</select>

<select name="especialidad" id="especialidad" required>
<option value="">Selecciona Especialidad</option>
<?php foreach($especialidades as $e): ?>
<option value="<?php echo $e['id'];?>"><?php echo $e['nombre'];?></option>
<?php endforeach;?>
</select>

<select name="doctor" id="doctor" required>
<option value="">Selecciona Doctor</option>
</select>

<input type="date" name="fecha" required>
<input type="time" name="hora" required>
<button type="submit">Agendar</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.getElementById('especialidad').addEventListener('change', function(){
    var espId = this.value;
    var doctorSelect = document.getElementById('doctor');
    doctorSelect.innerHTML = "<option value=''>Cargando...</option>";

    axios.post('ajax/get_doctores.php', {id_especialidad: espId})
        .then(res=>{
            var docs = res.data;
            doctorSelect.innerHTML = "<option value=''>Selecciona Doctor</option>";
            docs.forEach(d=>{
                var opt = document.createElement('option');
                opt.value = d.id;
                opt.textContent = d.nombre;
                doctorSelect.appendChild(opt);
            });
        }).catch(err=>{
            doctorSelect.innerHTML="<option value=''>Error al cargar</option>";
        });
});
</script>