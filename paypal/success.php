<?php 
include('../includes/connection.php');
session_start();

// Check if GET parameters are present
if (!empty($_GET)) {
    // Store payment details in session
    $_SESSION['product'] = $_GET['item_name'];  
    $_SESSION['txn_id'] = $_GET['tx']; 
    $_SESSION['amount'] = $_GET['amt']; 
    $_SESSION['currency'] = $_GET['cc']; 
    $_SESSION['status'] = $_GET['st']; 
    $_SESSION['payer_id'] = $_GET['payer_id']; 
    $_SESSION['payer_email'] = $_GET['payer_email']; 
    $_SESSION['payer_name'] = $_GET['first_name'].' '.$_GET['last_name'];
    $user_id = $_SESSION['user_id']; // Assuming user ID is stored in session

    date_default_timezone_set('Asia/Kolkata');

    // Insert payment details into the `payments` table
    $sql = "INSERT INTO payments (payment_id, payer_id, payer_name, payer_email, item_name, currency, amount, status, created_at) 
            VALUES ('".$_SESSION['txn_id']."', '".$_SESSION['payer_id']."', '".$_SESSION['payer_name']."', '".$_SESSION['payer_email']."', 
                    '".$_SESSION['product']."', '".$_SESSION['currency']."', '".$_SESSION['amount']."', '".$_SESSION['status']."', 
                    '".date('Y-m-d H:i:s')."')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Update existing orders with payment details
        $update_order_query = "UPDATE orders 
                               SET status = 'payment confirmed', payment_id = '" . $_SESSION['txn_id'] . "', 
                                   order_date = '" . date('Y-m-d H:i:s') . "' 
                               WHERE user_id = '$user_id' AND status = 'payment not confirmed'";
        mysqli_query($conn, $update_order_query);

        // Clear the user's cart after successful payment
        $clear_cart_query = "DELETE FROM cart WHERE user_id = '$user_id'";
        mysqli_query($conn, $clear_cart_query);

        // Redirect to success page
        header('Location: success.php');
    } else {
        echo "Error inserting payment details: " . mysqli_error($conn);
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payment Success</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #e3ffe7, #d9e7ff);
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .alert {
            padding: 20px;
            background: #4CAF50;
            color: white;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            text-align: left;
            padding: 10px;
        }

        th {
            background: #f4f4f4;
        }

        .button {
            display: inline-block;
            background: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .button:hover {
            background: #45a049;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="alert">
        <strong>Success!</strong> Payment has been successful.
    </div>
              
    <table>
        <tbody>
            <tr>
                <td><strong>Transaction Id</strong></td>
                <td><?php echo $_SESSION['txn_id']; ?></td>
            </tr>
            <tr>
                <td><strong>Product Name</strong></td>
                <td><?php echo $_SESSION['product']; ?></td>
            </tr>
            <tr>
                <td><strong>Amount</strong></td>
                <td><?php echo $_SESSION['amount']; ?></td>
            </tr>
            <tr>
                <td><strong>Payment Status</strong></td>
                <td><?php echo $_SESSION['status']; ?></td>
            </tr>
        </tbody>
    </table>

    <a href="../index.php" class="button">Go Back to Home</a>

</div>

</body>
</html>
