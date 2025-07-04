<?php
// backend/logout.php

session_start(); // Reanuda la sesión existente

session_unset(); // Libera todas las variables de sesión

session_destroy(); // Destruye toda la información registrada de una sesión

// Redirige al usuario a la página de inicio de sesión
header('Location: ../frontend/index.html');
exit;
?>