<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php"); // Redirect to login if session is not set
        exit();
    }

    include('../includes/connection.php');

    $userId = $_SESSION['user_id'];

    $sql = "SELECT orders.order_id, product.id, product.name, product.price, product.image1, orders.quantity, orders.total_price, orders.order_date, orders.status 
            FROM orders 
            JOIN product ON orders.product_id = product.id 
            WHERE orders.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View All Orders</title>
        <link rel="stylesheet" href="../css/view_all_orders.css">
    </head>
    <body>
        <div class="orders-container">
            <div class="header">
                <h1>Your Orders</h1>
                <a href="dashboard.php" class="home-btn">Back to Home</a>
            </div>
        
            <div class="order-products">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <img src="../images/product/<?php echo $row['image1']; ?>" alt="<?php echo $row['name']; ?>" class="product-image">
                        <h2><?php echo $row['name']; ?></h2>
                        <p>Price: $<?php echo $row['price']; ?></p>
                        <p>Quantity: <?php echo $row['quantity']; ?></p>
                        <p>Total: $<?php echo $row['total_price']; ?></p>
                        <p>Order Date: <?php echo $row['order_date']; ?></p>
                        <p>Status: <?php echo $row['status']; ?></p>

                        <div class="buttons">
                            <form action="product_view.php" method="GET" class="inline-form">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="view-btn">View Product</button>
                            </form>
                            <form action="track_order.php" method="POST" class="inline-form">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <button type="submit" class="track-btn">Track Order</button>
                            </form>
                            <form action="download_invoice.php" method="POST" class="inline-form">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <button type="submit" class="invoice-btn">Download Invoice</button>
                            </form>
                            <form action="cancel_order.php" method="POST" class="inline-form">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <button type="submit" class="cancel-btn">Cancel Order</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </body>
</html>
