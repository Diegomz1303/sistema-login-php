<?php
// backend/login.php

// Iniciamos la sesión. Es crucial para mantener al usuario logueado.
session_start();

// Incluimos la conexión a la base de datos
require_once 'db_connect.php';

// La respuesta será en formato JSON
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $response['message'] = 'Correo y contraseña son obligatorios.';
    } else {
        // Buscamos al usuario por su email
        $sql = "SELECT id, nombre, password FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // El usuario existe, ahora verificamos la contraseña
            $user = $result->fetch_assoc();
            
            // Usamos password_verify() para comparar la contraseña enviada con el hash guardado
            if (password_verify($password, $user['password'])) {
                // ¡Contraseña correcta!
                $response['success'] = true;
                $response['message'] = 'Inicio de sesión exitoso.';

                // Guardamos la información del usuario en la sesión
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                
            } else {
                // Contraseña incorrecta
                $response['message'] = 'Correo o contraseña incorrectos.';
            }
        } else {
            // Usuario no encontrado
            $response['message'] = 'Correo o contraseña incorrectos.';
        }
        $stmt->close();
    }
} else {
    $response['message'] = 'Método no válido.';
}

$conn->close();
echo json_encode($response);
?>