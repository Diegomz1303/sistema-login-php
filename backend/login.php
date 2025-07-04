<?php
// backend/login.php

session_start();

require_once 'db_connect.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $response['message'] = 'Correo y contraseña son obligatorios.';
    } else {
        
        // 1. Modificamos la consulta para incluir el 'rol'
        $sql = "SELECT id, nombre, password, rol FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                
                $response['success'] = true;
                $response['message'] = 'Inicio de sesión exitoso.';

                // 2. Guardamos todos los datos del usuario en la sesión
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                $_SESSION['user_rol'] = $user['rol']; // <-- CAMBIO AQUÍ
                
            } else {
                
                $response['message'] = 'Correo o contraseña incorrectos.';
            }
        } else {
            
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