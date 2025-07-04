<?php
session_start();
// Si el usuario no ha iniciado sesión o no es admin, lo redirigimos
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'admin') {
    header('Location: ../frontend/index.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Admin</title>
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="admin-wrapper">
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="../frontend/img/google-logo.svg" alt="logo"> <h3>Panel de Administración</h3>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="active"><i class="fa-solid fa-tachometer-alt"></i> Dashboard</a>
            <a href="#"><i class="fa-solid fa-box"></i> Productos</a>
            <a href="#"><i class="fa-solid fa-tags"></i> Categorías</a>
            <a href="#"><i class="fa-solid fa-shopping-cart"></i> Pedidos</a>
            <a href="#"><i class="fa-solid fa-users"></i> Usuarios</a>
            <hr>
            <a href="../frontend/tienda.php" target="_blank"><i class="fa-solid fa-store"></i> Ver Tienda</a>
            <a href="../backend/logout.php" class="logout"><i class="fa-solid fa-sign-out-alt"></i> Cerrar Sesión</a>
        </nav>
    </aside>

    <main class="main-content">
        <header>
            <h1>Dashboard</h1>
            <div class="welcome-text">Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
        </header>

        <section class="stats-cards">
            <div class="card">
                <div class="card-info">
                    <h2>0</h2>
                    <p>Productos</p>
                </div>
                <div class="card-icon products"><i class="fa-solid fa-box"></i></div>
            </div>
            <div class="card">
                <div class="card-info">
                    <h2>0</h2>
                    <p>Categorías</p>
                </div>
                <div class="card-icon categories"><i class="fa-solid fa-tags"></i></div>
            </div>
            <div class="card">
                <div class="card-info">
                    <h2>0</h2>
                    <p>Pedidos</p>
                </div>
                <div class="card-icon orders"><i class="fa-solid fa-shopping-cart"></i></div>
            </div>
            <div class="card">
                <div class="card-info">
                    <h2>0</h2>
                    <p>Usuarios</p>
                </div>
                <div class="card-icon users"><i class="fa-solid fa-users"></i></div>
            </div>
        </section>

        <section class="recent-activity">
            <div class="content-box">
                <h3>Productos Recientes (Ejemplo)</h3>
                <table>
                    <thead>
                        <tr><th>Nombre</th><th>Precio</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Torta de Chocolate</td><td>S/ 25.00</td></tr>
                        <tr><td>Cheesecake de Fresa</td><td>S/ 20.00</td></tr>
                    </tbody>
                </table>
            </div>
        </section>

    </main>
</div>

</body>
</html>