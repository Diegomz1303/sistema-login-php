<?php
session_start();
require_once '../backend/db_connect.php';

// --- Lógica para obtener los datos del carrito ---
$cart_items = [];
$total_carrito = 0;
$total_items_en_carrito = 0;
if (!empty($_SESSION['carrito'])) {
    $product_ids = array_keys($_SESSION['carrito']);
    if (!empty($product_ids)) {
        $product_ids_string = implode(',', $product_ids);
        $sql_cart = "SELECT id, nombre, precio, imagen FROM productos WHERE id IN ($product_ids_string)";
        $result_cart = $conn->query($sql_cart);
        $products_in_cart = [];
        if($result_cart){
            while($row = $result_cart->fetch_assoc()) {
                $products_in_cart[$row['id']] = $row;
            }
        }
        foreach ($_SESSION['carrito'] as $id => $cantidad) {
            if (isset($products_in_cart[$id])) {
                $producto_en_carrito = $products_in_cart[$id];
                $subtotal = $producto_en_carrito['precio'] * $cantidad;
                $total_carrito += $subtotal;
                $total_items_en_carrito += $cantidad;
                $cart_items[] = [
                    'id' => $id, 'nombre' => $producto_en_carrito['nombre'], 'precio' => $producto_en_carrito['precio'],
                    'imagen' => $producto_en_carrito['imagen'], 'cantidad' => $cantidad, 'subtotal' => $subtotal
                ];
            }
        }
    }
}
// --- Fin de la lógica del carrito ---

$sql_tienda = "SELECT * FROM productos WHERE stock > 0 ORDER BY fecha_creacion DESC";
$result_tienda = $conn->query($sql_tienda);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuestros Postres</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<header class="top-nav">
    <div class="top-nav-container page-container">
        <div class="nav-logo">
            <a href="tienda.php"><span>Postres</span>Delicia</a>
        </div>
        <div class="nav-order">
            <a href="#" class="nav-order-btn">Ordenar</a>
        </div>
        <div class="nav-user">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="#" title="Mi Perfil"><i class="fas fa-user"></i></a>
            <?php else: ?>
                <a href="index.html" title="Iniciar Sesión"><i class="fas fa-user"></i></a>
            <?php endif; ?>
        </div>
    </div>
</header>
<?php if (isset($_GET['status']) && $_GET['status'] == 'success_cart'): ?>
    <div id="cart-notification" class="toast-notification">¡Añadido al carrito! ✅</div>
<?php endif; ?>

<div id="side-cart" class="cart-container">
    <a href="#" id="close-cart-btn" class="close-cart-btn">&times;</a>
    <h3>Mi Carrito</h3>
    <div id="cart-items-list" class="cart-items-list">
        <?php if (empty($cart_items)): ?>
            <p id="cart-empty-msg">Tu carrito está vacío.</p>
        <?php else: ?>
            <?php foreach($cart_items as $item): ?>
                <div class="cart-item" id="cart-item-<?php echo $item['id']; ?>">
                    <img src="../uploads/<?php echo htmlspecialchars($item['imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>">
                    <div class="cart-item-info">
                        <h4><?php echo htmlspecialchars($item['nombre']); ?></h4>
                        <div class="cart-item-qty">
                            <button class="qty-btn" data-id="<?php echo $item['id']; ?>" data-change="-1">-</button>
                            <span id="qty-<?php echo $item['id']; ?>"><?php echo $item['cantidad']; ?></span>
                            <button class="qty-btn" data-id="<?php echo $item['id']; ?>" data-change="1">+</button>
                        </div>
                    </div>
                    <div class="cart-item-price" id="subtotal-<?php echo $item['id']; ?>">S/ <?php echo number_format($item['subtotal'], 2); ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="cart-footer">
        <div class="cart-total">
            <strong>Total:</strong>
            <span id="cart-total-amount">S/ <?php echo number_format($total_carrito, 2); ?></span>
        </div>
        <a href="#" class="btn" style="width: 100%;">Finalizar Compra</a>
    </div>
</div>

<a href="#" id="cart-fab" class="cart-fab">
    <i class="fas fa-shopping-cart"></i>
    <span id="cart-counter" class="cart-counter"><?php echo $total_items_en_carrito; ?></span>
</a>

<main class="page-container">
    <h1>Nuestros Deliciosos Postres</h1>
    <p>Elige tu favorito y disfrútalo.</p>
    <hr>
    <div class="product-grid">
        <?php if ($result_tienda && $result_tienda->num_rows > 0): ?>
            <?php while($producto = $result_tienda->fetch_assoc()): ?>
                <div class="product-card">
                    <img class="product-image" src="../uploads/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                    <h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>
                    <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                    <div class="product-price">S/ <?php echo htmlspecialchars($producto['precio']); ?></div>
                    <form action="../backend/agregar_al_carrito.php" method="post">
                        <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                        <input type="number" name="cantidad" value="1" min="1" style="width: 60px; padding: 0.5rem; margin-right: 10px;">
                        <button type="submit" class="btn">Añadir al Carrito</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay postres disponibles en este momento. ¡Vuelve pronto!</p>
        <?php endif; $conn->close(); ?>
    </div>
</main>

<?php include 'templates/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lógica para el carrito lateral y la modal de imagen
    // (Este código no cambia, se mantiene como en el paso anterior)
    const cartFab = document.getElementById('cart-fab'); 
    const sideCart = document.getElementById('side-cart');
    const closeCartBtn = document.getElementById('close-cart-btn');
    if (cartFab) { cartFab.addEventListener('click', (e) => { e.preventDefault(); sideCart.classList.add('open'); }); }
    if (closeCartBtn) { closeCartBtn.addEventListener('click', (e) => { e.preventDefault(); sideCart.classList.remove('open'); }); }
    document.getElementById('cart-items-list').addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('qty-btn')) {
            const button = e.target;
            const producto_id = button.dataset.id;
            const change = parseInt(button.dataset.change);
            fetch('../backend/update_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ producto_id: producto_id, change: change })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.newQuantity > 0) {
                        document.getElementById('qty-' + producto_id).textContent = data.newQuantity;
                        document.getElementById('subtotal-' + producto_id).textContent = 'S/ ' + data.newSubtotal;
                    } else {
                        document.getElementById('cart-item-' + producto_id).remove();
                    }
                    document.getElementById('cart-total-amount').textContent = 'S/ ' + data.newCartTotal;
                    document.getElementById('cart-counter').textContent = data.totalItemsInCart;
                    if (data.totalItemsInCart === 0) {
                        document.getElementById('cart-items-list').innerHTML = '<p id="cart-empty-msg">Tu carrito está vacío.</p>';
                    }
                }
            });
        }
    });
    const notification = document.getElementById('cart-notification');
    if (notification) {
        setTimeout(() => { notification.classList.add('show'); }, 100);
        setTimeout(() => { notification.classList.remove('show'); }, 3000);
    }
    const modal = document.getElementById("image-modal");
    const modalImg = document.getElementById("modal-image");
    const captionText = document.getElementById("image-modal-caption");
    const productImages = document.querySelectorAll('.product-image');
    productImages.forEach(img => {
        img.onclick = function(){
            modal.style.display = "block";
            modalImg.src = this.src;
            captionText.innerHTML = this.alt;
        }
    });
    const span = document.getElementsByClassName("image-modal-close")[0];
    span.onclick = function() { 
        modal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});
</script>

</body>

<footer class="site-footer">
    <div class="footer-content page-container">
        
        <div class="footer-section about">
            <h2 class="logo-text"><span>Postres</span>Delicia</h2>
            <p>
                Deléitate con nuestros postres hechos en casa con los ingredientes más frescos. 
                ¡Sabor y calidad en cada bocado!
            </p>
            <div class="socials">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i> Chatea con nosotros</a>
            </div>
        </div>

        <div class="footer-section links">
            <h2>Enlaces Rápidos</h2>
            <ul>
                <li><a href="#">Inicio</a></li>
                <li><a href="#">Productos</a></li>
                <li><a href="#">Promociones</a></li>
                <li><a href="#">Contacto</a></li>
            </ul>
        </div>

        <div class="footer-section contact">
            <h2>Contacto</h2>
            <span><i class="fas fa-map-marker-alt"></i> &nbsp; San Isidro, Calle Margaritas 120, Ica, Perú</span>
            <span><i class="fas fa-phone"></i> &nbsp; +51 970 846 395</span>
            <span><i class="fas fa-envelope"></i> &nbsp; info@postresdelicia.com</span>
            <span><i class="fas fa-clock"></i> &nbsp; Lunes - Sábado: 9:00 AM -- 8:00 PM</span>
        </div>

    </div>

    <div class="footer-bottom">
        &copy; <?php echo date('Y'); ?> PostresDelicia - Todos los derechos reservados.
    </div>
</footer>
<script>
// ... tu código JavaScript existente ...
</script>

</body>
</html>
</html>