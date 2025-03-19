<?php
session_start();

    // Connect to the database
    include('../includes/connection.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../register.php");
    exit;
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT name, mobile, email, address FROM user WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

// Process the Buy Now request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['products'])) {
    $products = array_map('json_decode', $_POST['products']); // Decode JSON strings
    $total_price = 0;

    foreach ($products as $product) {
        $total_price += $product->price * $product->quantity;
    }

    // Handle optional secondary phone and delivery address
    $secondary_phone = $_POST['secondary_phone'] ?? $user['mobile'];
    $delivery_address = $_POST['delivery_address'] ?? $user['address'];

    // Insert order into the database
    foreach ($products as $product) {
        $insert_query = "
            INSERT INTO `orders` (user_id, product_id, quantity, total_price, order_date, status, mobile, delivery_address) 
            VALUES (?, ?, ?, ?, NOW(), 'payment not confirmed', ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param(
            'iiidss',
            $user_id,
            $product->id,
            $product->quantity,
            $total_price,
            $secondary_phone,
            $delivery_address
        );
        $insert_stmt->execute();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Now</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ffefba, #ffffff);
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1, h2, h3 {
            text-align: center;
            color: #4CAF50;
            text-transform: uppercase;
            margin: 20px 0;
        }

        .user-info, .product-list, form {
            background: #fff;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            
        }

        .product-item, label, input, textarea, button {
            display: block;
            width: 100%;
            margin: 10px 0;
        }

        input, textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.3s, background-color 0.3s;
        }

        button:hover {
            background: #45a049;
            transform: scale(1.05);
        }

        .product-item {
            animation: fadeIn 1s ease-in-out;
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-item:hover {
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            transform: scale(1.02);
            transition: all 0.3s ease-in-out;
        }

        .user-info {
            border-left: 5px solid #4CAF50;
            animation: slideIn 0.7s ease-out;
        }
        form{
          animation: slideIn 1.4s ease-out;
        }
        .product-list{
          animation: slideIn 2.1s ease-out;
        }
        .h2{
          animation: slideIn 2.8s ease-out;
        }

        /* Slide-in animation */
        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }
            to {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <h1>Buy Now</h1>

    <!-- Display User Information -->
    <div class="user-info">
        <h2>User Information</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['mobile']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
    </div>

    <!-- Secondary Information Form -->
    <form method="post" action="">
        <h3>Optional: Add Secondary Details</h3>
        <label for="secondary_phone">Secondary Phone:</label>
        <input type="text" id="secondary_phone" name="secondary_phone" placeholder="Enter secondary phone number">
        
        <label for="delivery_address">Delivery Address:</label>
        <textarea id="delivery_address" name="delivery_address" placeholder="Enter delivery address"></textarea>

        <!-- Pass Product Details -->
        <?php foreach ($products as $product): ?>
            <input type="hidden" name="products[]" value='<?php echo json_encode($product); ?>'>
        <?php endforeach; ?>

        <button type="submit">Update and Continue</button>
    </form>

    <!-- Display Products -->
    <div class="product-list">
        <?php foreach ($products as $product): ?>
            <div class="product-item">
                <h2><?php echo htmlspecialchars($product->name); ?></h2>
                <p>Price: $<?php echo htmlspecialchars($product->price); ?></p>
                <p>Quantity: <?php echo htmlspecialchars($product->quantity); ?></p>
                <p>Total: $<?php echo htmlspecialchars($product->price * $product->quantity); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
    <h2>Total Price: $<?php echo $total_price; ?></h2>

    <!-- PayPal Checkout -->
    <form method="post" action="https://www.sandbox.paypal.com/cgi-bin/webscr">
        <input type="hidden" name="business" value="sb-8bca333930367@business.example.com">
        <input type="hidden" name="currency_code" value="USD">
        <input type="hidden" name="cmd" value="_cart">
        <input type="hidden" name="upload" value="1">

        <?php foreach ($products as $index => $product): ?>
            <input type="hidden" name="item_name_<?php echo $index + 1; ?>" value="<?php echo htmlspecialchars($product->name); ?>">
            <input type="hidden" name="amount_<?php echo $index + 1; ?>" value="<?php echo htmlspecialchars($product->price); ?>">
            <input type="hidden" name="quantity_<?php echo $index + 1; ?>" value="<?php echo htmlspecialchars($product->quantity); ?>">
        <?php endforeach; ?>

        <input type="hidden" name="return" value="http://localhost/E-Commerce-Website/paypal/success.php">
        <input type="hidden" name="cancel_return" value="http://localhost/E-Commerce-Website/paypal/cancel.php">
        <button type="submit">Proceed to PayPal</button>
    </form>
</body>
</html>
