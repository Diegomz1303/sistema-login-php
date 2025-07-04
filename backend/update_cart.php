<?php
session_start();
require_once 'db_connect.php';

// Leer los datos JSON enviados desde JavaScript
$data = json_decode(file_get_contents('php://input'), true);

$response = ['success' => false];

if (isset($data['producto_id']) && isset($data['change']) && isset($_SESSION['carrito'][$data['producto_id']])) {
    $producto_id = $data['producto_id'];
    $change = (int)$data['change'];

    // Actualizar la cantidad
    $_SESSION['carrito'][$producto_id] += $change;

    // Si la cantidad llega a 0 o menos, eliminar el producto del carrito
    if ($_SESSION['carrito'][$producto_id] <= 0) {
        unset($_SESSION['carrito'][$producto_id]);
    }

    // --- Recalcular todo el carrito para devolver los datos actualizados ---
    $total_carrito = 0;
    $total_items_en_carrito = 0;
    $new_quantity = 0;
    $new_subtotal = '0.00';

    if (!empty($_SESSION['carrito'])) {
        $product_ids = array_keys($_SESSION['carrito']);
        $product_ids_string = implode(',', $product_ids);
        $sql = "SELECT id, precio FROM productos WHERE id IN ($product_ids_string)";
        $result = $conn->query($sql);
        $products_info = [];
        while($row = $result->fetch_assoc()) {
            $products_info[$row['id']] = $row['precio'];
        }

        foreach($_SESSION['carrito'] as $id => $cantidad) {
            $total_carrito += $products_info[$id] * $cantidad;
            $total_items_en_carrito += $cantidad;
            if ($id == $producto_id) {
                $new_quantity = $cantidad;
                $new_subtotal = number_format($products_info[$id] * $cantidad, 2);
            }
        }
    }
    
    // Preparar la respuesta JSON para JavaScript
    $response = [
        'success' => true,
        'newQuantity' => $new_quantity,
        'newSubtotal' => $new_subtotal,
        'newCartTotal' => number_format($total_carrito, 2),
        'totalItemsInCart' => $total_items_en_carrito
    ];
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($response);
exit;