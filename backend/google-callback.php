<?php
// backend/google-callback.php

require_once '../vendor/autoload.php';
require_once 'db_connect.php';
session_start();

$client = new Google_Client();
$client->setClientId('1025249111506-f45fhikj07ft79q2efr1vhs4avgdngve.apps.googleusercontent.com'); // Reemplaza con tu ID de Cliente
$client->setClientSecret('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'); // Reemplaza con tu Secreto de Cliente
$client->setRedirectUri('http://localhost/mi_proyecto/backend/google-callback.php');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    if (isset($token['error'])) {
        header('Location: ../frontend/index.html');
        exit;
    }
    
    $client->setAccessToken($token);

    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    
    $google_id = $google_account_info->id;
    $email = $google_account_info->email;
    $nombre = $google_account_info->name;

    // 1. CAMBIO: Ahora también pedimos el 'rol'
    $sql = "SELECT id, nombre, rol FROM usuarios WHERE google_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $google_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nombre'];
        $_SESSION['user_rol'] = $user['rol']; // 2. CAMBIO: Guardamos el rol
    } else {
        // Si no existe con google_id, buscamos por email para enlazar cuentas
        $sql = "SELECT id, nombre, rol FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // El usuario existe con ese email, enlazamos su google_id
            $user = $result->fetch_assoc();
            $user_id = $user['id'];
            
            $update_sql = "UPDATE usuarios SET google_id = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $google_id, $user_id);
            $update_stmt->execute();

            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_rol'] = $user['rol']; // 3. CAMBIO: Guardamos el rol
        } else {
            // El usuario es completamente nuevo, lo creamos
            $sql_insert = "INSERT INTO usuarios (google_id, nombre, email, rol) VALUES (?, ?, ?, 'cliente')";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("sss", $google_id, $nombre, $email);
            $stmt_insert->execute();
            
            $_SESSION['user_id'] = $stmt_insert->insert_id;
            $_SESSION['user_name'] = $nombre;
            $_SESSION['user_rol'] = 'cliente'; // Un usuario nuevo siempre es 'cliente'
        }
    }

    // Ya que la sesión está creada, redirigimos al panel de administrador si es admin
    if ($_SESSION['user_rol'] === 'admin') {
        header('Location: ../admin/index.php');
    } else {
        header('Location: ../frontend/tienda.php');
    }
    exit();

} else {
    header('Location: ../frontend/index.html');
    exit();
}
?>