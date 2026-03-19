<!-- admin/sidebar.php -->
<div class="sidebar">
    <h2>Panel Admin</h2>
    <ul>
        <li><a href="dashboard.php">🏠 Dashboard</a></li>
        <li><a href="doctores.php">👨‍⚕️ Doctores</a></li>
        <li><a href="especialidades.php">💉 Especialidades</a></li>
        <li><a href="citas.php">📋 Citas</a></li>
        <li><a href="usuarios.php">👤 Usuarios</a></li>
        <li><a href="../auth/logout.php">🚪 Cerrar sesión</a></li>
    </ul>
</div>

<style>
.sidebar {
    width: 220px;
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    background: #0a1f44;
    padding: 20px;
    color: white;
    font-family: 'Segoe UI', sans-serif;
    border-radius: 0 16px 16px 0;
}
.sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
}
.sidebar ul {
    list-style: none;
    padding: 0;
}
.sidebar ul li {
    margin: 15px 0;
}
.sidebar ul li a {
    color: white;
    text-decoration: none;
    display: block;
    padding: 10px;
    border-radius: 8px;
    transition: 0.3s;
}
.sidebar ul li a:hover {
    background: #1e90ff;
}
</style>