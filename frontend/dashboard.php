<?php
// Lo primero siempre es iniciar la sesión.
session_start();

// Si el usuario no ha iniciado sesión, lo redirigimos al login.
// La variable de sesión 'user_id' se crea en login.php y google-callback.php
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Estilos adicionales para el dashboard */
        body {
            display: block;
        }
        .dashboard-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 2rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container dashboard-container">
        <h1>¡Bienvenido a tu Panel, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <p>Has iniciado sesión correctamente.</p>
        <p>Tu ID de usuario es: <?php echo $_SESSION['user_id']; ?></p>
        <br>
        <a href="../backend/logout.php" class="btn">Cerrar Sesión</a>
    </div>
</body>
</html>