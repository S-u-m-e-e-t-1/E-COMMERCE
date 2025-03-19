<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php"); // Redirect to login if session is not set
        exit();
    }

    // Database connection
    include('../includes/connection.php');

    // Retrieve user ID from session
    $userId = $_SESSION['user_id'];

    // Fetch cart items for the user
    $sql = "SELECT cart.cart_id, product.id, product.name, product.price, product.image1, cart.quantity 
            FROM cart 
            JOIN product ON cart.product_id = product.id 
            WHERE cart.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId); // Corrected variable name to $userId
    $stmt->execute();
    $result = $stmt->get_result();

    $total_price = 0;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Cart</title>
       <link rel="stylesheet" href="../css/view_all_cart.css">
    </head>
    <body>
    <div class="cart-container">
        <div class="header">
            <h1>Your Cart</h1>
            <a href="dashboard.php" class="home-btn">Back to Home</a>
        </div>

        <div class="cart-products">
            <?php 
            $total_price = 0; 
            while ($row = $result->fetch_assoc()): 
                $product_total = $row['price'] * $row['quantity']; 
                $total_price += $product_total; 
            ?>
                <div class="product-card">
                    <img src="../images/product/<?php echo $row['image1']; ?>" alt="<?php echo $row['name']; ?>" class="product-image">
                    <h2><?php echo $row['name']; ?></h2>
                    <p>Price: $<?php echo $row['price']; ?></p>
                    <p>Quantity: <?php echo $row['quantity']; ?></p>
                    <p>Total: $<?php echo $product_total; ?></p>

                    <div class="buttons">
                        <form action="../product_view.php" method="GET" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="view-btn">View Product</button>
                        </form>
                        <form action="remove_from_cart.php" method="POST" class="inline-form">
                            <input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>">
                            <button type="submit" class="remove-btn">Remove from Cart</button>
                        </form>
                        <form action="../paypal/buy_now.php" method="POST" class="inline-form">
                            <input type="hidden" name="products[]" value='<?php echo json_encode($row); ?>'>
                            <button type="submit" class="buy-btn">Buy Now</button>
                        </form>

                    </div>
                </div>
            <?php endwhile; ?>
    </div>
    
    <div class="cart-total">
        <h2>Total Price: $<?php echo $total_price; ?></h2>
        <!-- Buy All Products Button -->
        <form action="../paypal/buy_now.php" method="POST">
            <?php
            $result->data_seek(0); // Reset pointer for the loop
            while ($row = $result->fetch_assoc()):
            ?>
                <input type="hidden" name="products[]" value="<?php echo htmlspecialchars(json_encode($row)); ?>">
            <?php endwhile; ?>
            <button type="submit" class="buy-all-btn">Buy All Products</button>
        </form>
    </div>
</div>

    </body>
</html>
