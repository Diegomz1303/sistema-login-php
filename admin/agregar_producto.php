<?php
session_start();
require_once '../backend/db_connect.php'; // Ajustamos la ruta para acceder a la conexión

// 1. VERIFICACIÓN DE SEGURIDAD
// Si el usuario no ha iniciado sesión o no es admin, no puede estar aquí.
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'admin') {
    die("Acceso denegado.");
}

// 2. VERIFICAR QUE SE ENVIARON DATOS POR POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 3. RECOGER DATOS DEL FORMULARIO
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $imagen_info = $_FILES['imagen'];

    // 4. LÓGICA PARA SUBIR LA IMAGEN
    $imagen_nombre_final = '';
    if (isset($imagen_info) && $imagen_info['error'] == 0) {
        $directorio_subidas = '../uploads/'; // El directorio donde guardaremos las imágenes
        
        // Crear un nombre de archivo único para evitar sobreescribir imágenes
        $extension_archivo = pathinfo($imagen_info['name'], PATHINFO_EXTENSION);
        $imagen_nombre_final = uniqid('postre_', true) . '.' . $extension_archivo;
        
        $ruta_subida = $directorio_subidas . $imagen_nombre_final;

        // Mover el archivo de la carpeta temporal a nuestro directorio de subidas
        if (!move_uploaded_file($imagen_info['tmp_name'], $ruta_subida)) {
            // Si hay un error al mover el archivo, redirigir con un mensaje de error
            header('Location: index.php?status=error_upload');
            exit;
        }
    } else {
        // Si no se subió imagen o hubo un error
        header('Location: index.php?status=error_upload');
        exit;
    }

    // 5. INSERTAR EL PRODUCTO EN LA BASE DE DATOS
    $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, imagen) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    // 's' para string, 'd' para double (decimal), 'i' para integer (entero)
    $stmt->bind_param("ssdis", $nombre, $descripcion, $precio, $stock, $imagen_nombre_final);

    if ($stmt->execute()) {
        // Si todo fue bien, redirigir con un mensaje de éxito
        header('Location: index.php?status=success');
    } else {
        // Si falló la inserción en la BD
        header('Location: index.php?status=error_db');
    }
    
    $stmt->close();
    $conn->close();

} else {
    // Si alguien intenta acceder al archivo directamente sin enviar datos
    header('Location: index.php');
}
exit;
?>