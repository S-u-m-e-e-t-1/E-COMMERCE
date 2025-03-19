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

    // Fetch user data for sidebar (profile image, name, and mobile number)
    $userQuery = $conn->prepare("SELECT profile_image, name, mobile FROM user WHERE user_id = ?");
    $userQuery->bind_param("i", $userId);
    $userQuery->execute();
    $userData = $userQuery->get_result()->fetch_assoc();

    // Fetch recent cart items (last 5 entries)
    $cartQuery = $conn->prepare("
        SELECT c.cart_id, p.name, p.image1 
        FROM cart c 
        JOIN product p ON c.product_id = p.id 
        WHERE c.user_id = ? 
        ORDER BY c.added_at DESC 
        LIMIT 5
    ");
    $cartQuery->bind_param("i", $userId);
    $cartQuery->execute();
    $cartItems = $cartQuery->get_result()->fetch_all(MYSQLI_ASSOC);

    // Fetch recent orders (last 5 entries)
    $orderQuery = $conn->prepare("
        SELECT o.order_id, p.name, p.image1 
        FROM orders o 
        JOIN product p ON o.product_id = p.id 
        WHERE o.user_id = ? 
        ORDER BY o.order_date DESC 
        LIMIT 5
    ");
    $orderQuery->bind_param("i", $userId);
    $orderQuery->execute();
    $orderItems = $orderQuery->get_result()->fetch_all(MYSQLI_ASSOC);
    ?>

    <!-- Your HTML for the dashboard goes here -->

    <?php
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="stylesheet" href="../css/dashboard.css">
    </head>
    <body>

        <div class="container">
            <!-- Sidebar -->
            <div id="sidebar" class="sidebar closed">
                <div class="profile">
                    <!-- User Profile Image -->
                    <img src="../images/user/<?php echo $userData['profile_image']; ?>" alt="Profile Image">
                    <div class="profile-info">
                        <p><?php echo $userData['name']; ?></p>
                        <p><?php echo $userData['mobile']; ?></p>
                    </div>
                </div>
                <ul class="nav-links">
                    <li><a href="edit_profile.php">Edit Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="content" style="margin-right:20px">
                <!-- Toggle Sidebar Button -->
                <button id="toggleSidebar" onclick="toggleSidebar()">☰</button>

                <!-- Header with Greeting and Back Home Button -->
                <div class="header">
                    <div class="greeting">
                        <?php 
                            date_default_timezone_set("Asia/Kolkata");
                            // Set your timezone
                            echo "Welcome, " . $userData['name'] . "! Today is " . date("F j, Y");
                        ?>
                    </div>
                    <a href="../index.php" class="back-home">Back to Home</a>
                </div>

                <!-- Cart Section -->
                <div class="section">
                    <h2>Recent Cart Items</h2>
                    <?php foreach ($cartItems as $item): ?>
                        <div class="card">
                            <!-- Product Image from Cart -->
                            <img src="../images/product/<?php echo $item['image1']; ?>" alt="Product Image">
                            <p><?php echo $item['name']; ?></p>
                        </div>
                    <?php endforeach; ?>
                    <a href="view_all_cart.php">View All Cart Items</a>
                </div>

                <!-- Orders Section -->
                <div class="section">
                    <h2>Recent Orders</h2>
                    <?php foreach ($orderItems as $item): ?>
                        <div class="card">
                            <!-- Product Image from Orders -->
                            <img src="../images/product/<?php echo $item['image1']; ?>" alt="Product Image">
                            <p><?php echo $item['name']; ?></p>
                        </div>
                    <?php endforeach; ?>
                    <a href="view_all_orders.php">View All Orders</a>
                </div>
            </div>
        </div>

        <script>
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const content = document.querySelector('.content');
                const toggleBtn = document.getElementById('toggleSidebar');

                sidebar.classList.toggle('closed');

                if (sidebar.classList.contains('closed')) {
                    toggleBtn.innerHTML = '☰';
                    content.style.marginLeft = '0';
                } else {
                    toggleBtn.innerHTML = '✖';
                    content.style.marginLeft = '250px';
                }
            }
        </script>

    </body>
</html>