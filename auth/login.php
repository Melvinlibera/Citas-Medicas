<?php
// =========================
// LOGIN DE USUARIO CON DISEÑO PERSONALIZADO
// =========================
session_start();
include("../config/db.php");

$error = "";

// Si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $correo = trim($_POST['correo']);
    $password = $_POST['password'];

    // Validación básica
    if(empty($correo) || empty($password)){
        $error = "Completa todos los campos";
    } else {

        // Buscar usuario en la base de datos
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        $user = $stmt->fetch();

        // Verificar contraseña usando password_verify
        if($user && password_verify($password, $user['password'])){

            // Guardar datos en sesión
            $_SESSION['usuario'] = $user['nombre'];
            $_SESSION['rol'] = $user['rol'];
            $_SESSION['id'] = $user['id'];

            // Redirección según rol
            if($user['rol'] == 'admin'){
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../user/dashboard.php");
            }
            exit();

        } else {
            $error = "Credenciales incorrectas";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login - Hospital & Human</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>
/* ========================= */
/* ESTILO SOLO PARA LOGIN    */
/* ========================= */
.login-container {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #0a1f44, #1e90ff);
}

.login-box {
    background: white;
    padding: 40px;
    border-radius: 14px;
    width: 320px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    text-align: center;
}

.login-box h2 {
    margin-bottom: 20px;
    color: #0a1f44;
}

.login-box input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 14px;
}

.login-box button {
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

.login-box button:hover {
    background: #1e90ff;
}

.error {
    color: red;
    margin-bottom: 10px;
    font-size: 14px;
}
</style>

</head>

<body>

<div class="login-container">

    <form class="login-box" method="POST">

        <h2>Iniciar Sesión</h2>

        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>

        <button type="submit">Entrar</button>

        <p style="margin-top:15px;">
            ¿No tienes cuenta?<br>
            <a href="register.php">Regístrate</a>
        </p>

    </form>

</div>

</body>
</html>