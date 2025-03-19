<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('You must be logged in to remove items from the cart.');</script>";
        echo "<script>window.location.href = 'login.php';</script>";
        exit();
    }
    // Database connection
    include('../includes/connection.php');

    if (isset($_POST['cart_id'])) {
        $cart_id = $_POST['cart_id'];

        // Prepare and execute the SQL query to delete the item from the cart
        $query = "DELETE FROM cart WHERE cart_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $cart_id);

        if ($stmt->execute()) {
            echo "<script>alert('Item removed from cart successfully.');</script>";
        } else {
            echo "<script>alert('Failed to remove item from cart. Please try again.');</script>";
        }

        $stmt->close();
        $conn->close();

        // Redirect back to the cart page
        echo "<script>window.location.href = 'view_all_cart.php';</script>";
        exit();
    } else {
        echo "<script>alert('No item selected to remove.');</script>";
        echo "<script>window.location.href = 'cart.php';</script>";
        exit();
    }
?>
