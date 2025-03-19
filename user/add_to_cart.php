<?php
    session_start();
    include('../includes/connection.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $product_id = $_POST['product_id'];
        $user_id = $_SESSION['user_id']; // Assuming user is logged in and their ID is in session

        if ($user_id && $product_id) {
            // Check if the product is already in the cart
            $checkCartQuery = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
            $stmt = $conn->prepare($checkCartQuery);
            $stmt->bind_param("ii", $user_id, $product_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Product already in cart, update quantity
                $updateQuery = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("ii", $user_id, $product_id);
                $stmt->execute();
                echo json_encode(["status" => "success", "message" => "Quantity updated in cart"]);
            } else {
                // Product not in cart, insert as new entry
                $insertQuery = "INSERT INTO cart (user_id, product_id, quantity, added_at) VALUES (?, ?, 1, NOW())";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("ii", $user_id, $product_id);
                $stmt->execute();
                echo json_encode(["status" => "success", "message" => "Product added to cart"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "User not logged in or invalid product"]);
        }
    }
?>
