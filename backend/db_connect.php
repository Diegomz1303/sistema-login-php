<?php
// backend/db_connect.php

// Credenciales de la base de datos
$servername = "127.0.0.1";
$username = "root";
$password = ""; // Por defecto en XAMPP la contraseña está vacía
$dbname = "login_db";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si hay errores en la conexión
if ($conn->connect_error) {
    // Si hay un error, el script se detiene y muestra el error.
    die("Error de conexión: " . $conn->connect_error);
}

// Opcional: Establecer el juego de caracteres a UTF-8
// Esto es bueno para la compatibilidad con tildes y caracteres especiales.
$conn->set_charset("utf8mb4");

?>