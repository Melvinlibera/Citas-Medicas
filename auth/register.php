<?php
// =========================
// REGISTRO DE USUARIO - DISEÑO COMPLETO CON SEGURO DINÁMICO
// =========================
session_start();
include("../config/db.php");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre        = trim($_POST['nombre']);
    $cedula        = trim($_POST['cedula']);
    $telefono      = trim($_POST['telefono']);
    $correo        = trim($_POST['correo']);
    $password      = $_POST['password'];
    $seguro        = $_POST['seguro'];
    $nombre_seguro = $_POST['nombre_seguro'] ?? null; // solo si tiene seguro

    // Validación básica
    if(empty($nombre) || empty($cedula) || empty($telefono) || empty($correo) || empty($password) || empty($seguro)){
        $error = "Todos los campos son obligatorios";
    } elseif($seguro === "si" && empty($nombre_seguro)){
        $error = "Selecciona tu seguro médico";
    } else {

        // Verificar si el correo ya existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);

        if($stmt->fetch()){
            $error = "El correo ya está registrado";
        } else {

            // Cifrar contraseña
            $pass = password_hash($password, PASSWORD_DEFAULT);

            // Insertar usuario
            $stmt = $pdo->prepare("INSERT INTO usuarios 
                (nombre, cedula, telefono, correo, password, seguro, rol)
                VALUES (?,?,?,?,?,?,?)");

            $stmt->execute([
                $nombre,
                $cedula,
                $telefono,
                $correo,
                $pass,
                $seguro === "si" ? $nombre_seguro : $seguro, // guardar nombre del seguro si tiene
                'user' // rol por defecto
            ]);

            $success = "Registro exitoso. Ahora puedes iniciar sesión.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Registro - Hospital & Human</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>
/* ========================= */
/* ESTILO SOLO PARA REGISTER */
/* ========================= */
.register-container {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #0a1f44, #1e90ff);
}

.register-box {
    background: white;
    padding: 40px;
    border-radius: 14px;
    width: 340px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    text-align: center;
}

.register-box h2 {
    margin-bottom: 20px;
    color: #0a1f44;
}

.register-box input,
.register-box select {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 14px;
}

.register-box button {
    width: 100%;
    padding: 12px;
    background: #0a1f44;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin-top: 10px;
    transition: 0.3s;
}

.register-box button:hover {
    background: #1e90ff;
}

.error {
    color: red;
    margin-bottom: 10px;
    font-size: 14px;
}

.success {
    color: green;
    margin-bottom: 10px;
    font-size: 14px;
}
</style>

</head>

<body>

<div class="register-container">

    <form class="register-box" method="POST">

        <h2>Crear Cuenta</h2>

        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <input name="nombre" placeholder="Nombre completo" required>
        <input name="cedula" placeholder="Cédula" required>
        <input name="telefono" placeholder="Teléfono" required>
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>

        <select name="seguro" id="seguro">
            <option value="" disabled selected>¿Tienes seguro médico?</option>
            <option value="si">Sí</option>
            <option value="no">No</option>
        </select>

        <!-- Segundo select oculto por defecto -->
        <select name="nombre_seguro" id="nombre_seguro" style="display:none; margin-top:8px;">
            <option value="" disabled selected>Selecciona tu seguro</option>
            <option value="ARS Palic">ARS Palic</option>
            <option value="ARS Humano">ARS Humano</option>
            <option value="ARS Universal">ARS Universal</option>
            <option value="ARS CMD">ARS CMD</option>
            <option value="ARS Mapfre">ARS Mapfre</option>
            <option value="ARS Senasa">ARS Senasa</option>
            <option value="ARS Monumental">ARS Monumental</option>
        </select>

        <button type="submit">Registrarse</button>

        <p style="margin-top:15px;">
            ¿Ya tienes cuenta?<br>
            <a href="login.php">Inicia sesión</a>
        </p>

    </form>

</div>

<script>
// Mostrar el select de seguros solo si se elige "Sí"
document.getElementById("seguro").addEventListener("change", function() {
    const seguroSelect = document.getElementById("nombre_seguro");
    if(this.value === "si"){
        seguroSelect.style.display = "block";
    } else {
        seguroSelect.style.display = "none";
        seguroSelect.value = "";
    }
});
</script>

</body>
</html>