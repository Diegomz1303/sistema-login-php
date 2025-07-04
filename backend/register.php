<?php



require_once 'db_connect.php';


header('Content-Type: application/json');


$response = array('success' => false, 'message' => '');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    
    if (empty($nombre) || empty($email) || empty($password)) {
        $response['message'] = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'El formato del correo electrónico no es válido.';
    } else {
        
        $sql_check = "SELECT id FROM usuarios WHERE email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $response['message'] = 'El correo electrónico ya está registrado.';
        } else {
            
            
            
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            $sql_insert = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("sss", $nombre, $email, $password_hashed);

            if ($stmt_insert->execute()) {
                $response['success'] = true;
                $response['message'] = '¡Registro exitoso! Ahora puedes iniciar sesión.';
            } else {
                $response['message'] = 'Error al registrar el usuario: ' . $stmt_insert->error;
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
} else {
    $response['message'] = 'Método de solicitud no válido.';
}


$conn->close();


echo json_encode($response);

?>