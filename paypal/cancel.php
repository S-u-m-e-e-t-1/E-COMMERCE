<?php
include('connection.php');
session_start();

// Check if relevant session data is available
if (isset($_SESSION['user_id']) && isset($_SESSION['product']) && isset($_SESSION['amount'])) {
    $user_id = $_SESSION['user_id']; // Assuming user ID is stored in session
    $product_name = $_SESSION['product'];
    $amount = $_SESSION['amount'];
    $txn_id = $_SESSION['txn_id'] ?? 'N/A'; // Use a placeholder if no transaction ID is set

    date_default_timezone_set('Asia/Kolkata');

    // Insert order with status 'payment not confirmed'
    $order_query = "INSERT INTO orders (user_id, product_id, quantity, order_date, status, total_price) 
                    VALUES ('$user_id', (SELECT id FROM products WHERE name='$product_name' LIMIT 1), 1, '".date('y-m-d h:i:s')."', 'payment not confirmed', '$amount')";
    
    if (mysqli_query($conn, $order_query)) {
        // Order entry added with 'payment not confirmed' status
    } else {
        echo "Error: " . $order_query . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Cancel</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-3">
  <div class="alert alert-danger">
    <strong>Sorry!</strong> Your payment has been cancelled. Your order status is set to "payment not confirmed."
  </div>
</div>

</body>
</html>
