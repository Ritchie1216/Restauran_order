<?php
include '../db_connect.php'; // Include your database connection
include '../classes/Cart.php'; // Include the Cart class



// Assuming the user is logged in and user_id is stored in session
$tableId = $_SESSION['table_id'] ?? null;

if (!$tableId) {
    echo "Please log in to add items to your cart.";
    exit;
}

$cart = new Cart($conn);

// Adding an item to the cart
if (isset($_POST['add_to_cart'])) {
    $menuItemId = $_POST['menu_item_id'];
    $quantity = $_POST['quantity'];
    $cart->addToCart($userId, $menuItemId, $quantity);
    echo "Item added to cart!";
}

// Displaying cart items
$cartItems = $cart->getCartItems($userId);
$totalPrice = $cart->getTotalPrice($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Your Cart</h2>
    <?php if ($cartItems->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $cartItems->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td>
                            <form method="POST" action="remove_from_cart.php">
                                <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="btn btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <td colspan="3"><strong>Total Price</strong></td>
                    <td><strong>$<?php echo number_format($totalPrice, 2); ?></strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
