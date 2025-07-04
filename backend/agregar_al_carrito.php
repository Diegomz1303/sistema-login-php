<?php
session_start();

// 1. Verificamos que se haya enviado un ID de producto y una cantidad
if (isset($_POST['producto_id']) && isset($_POST['cantidad'])) {
    $producto_id = $_POST['producto_id'];
    $cantidad = (int)$_POST['cantidad'];

    // 2. Inicializamos el carrito en la sesión si aún no existe
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // 3. Lógica para añadir o actualizar el producto en el carrito
    if ($cantidad > 0) {
        if (isset($_SESSION['carrito'][$producto_id])) {
            // Si el producto ya está en el carrito, sumamos la cantidad
            $_SESSION['carrito'][$producto_id] += $cantidad;
        } else {
            // Si el producto no está, lo añadimos
            $_SESSION['carrito'][$producto_id] = $cantidad;
        }
    }
    
    // 4. Redirigimos de vuelta a la tienda con un mensaje de éxito
    header('Location: ../frontend/tienda.php?status=success_cart');
    exit;

} else {
    // Si no se enviaron los datos correctos, redirigimos sin mensaje
    header('Location: ../frontend/tienda.php');
    exit;
}
?>