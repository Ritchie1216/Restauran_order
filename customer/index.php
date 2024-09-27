<?php
include '../db_connect.php'; // Include your database connection
include '../classes/Menu.php';
include_once '../classes/Cart.php'; // Include the Cart class

$menu = new Menu($conn);
$cart = new Cart($conn);

// Check if the add to cart form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_id'], $_POST['quantity'])) {
    $itemId = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    
    // Fetch the menu item details
    $items = $menu->getAvailableMenuItems();
    $itemDetails = [];
    foreach ($items as $item) {
        if ($item['id'] == $itemId) {
            $itemDetails = $item;
            break;
        }
    }

    if (!empty($itemDetails)) {
        // Add the item to the cart
        $cart->addToCart($itemDetails['id'], $itemDetails['name'], $itemDetails['price'], $quantity);
        echo "<div class='alert alert-success'>Item added to cart successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Item not found.</div>";
    }
}

// Fetch the menu items to display
$menuItems = $menu->getAvailableMenuItems();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Menu</h2>

    <div class="row">
        <?php foreach ($menuItems as $item): ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="../<?php echo htmlspecialchars($item['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($item['description']); ?></p>
                        <p class="card-text"><strong>Price: $<?php echo number_format($item['price'], 2); ?></strong></p>
                       <form action="view_cart.php" method="POST">
                            <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                            <div class="form-group mb-2">
                                <label for="quantity">Quantity:</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
