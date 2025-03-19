<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch profile image, admin name, total users, products, and orders
$user_image = $_SESSION['image']; // Get from session
$admin_name = $_SESSION['username']; // Get from session
$profile_image_path = "http://localhost/E-Commerce-Website/admin/profile/" . $user_image; 

// Fetch data counts
$totalUsers = 0;
$totalProducts = 0;
$totalOrders = 0;

$sqlUsers = "SELECT COUNT(*) as count FROM brand";
$resultUsers = $conn->query($sqlUsers);
if ($resultUsers->num_rows > 0) {
    $totalUsers = $resultUsers->fetch_assoc()['count'];
}

$sqlProducts = "SELECT COUNT(*) as count FROM product";
$resultProducts = $conn->query($sqlProducts);
if ($resultProducts->num_rows > 0) {
    $totalProducts = $resultProducts->fetch_assoc()['count'];
}

$sqlOrders = "SELECT COUNT(*) as count FROM categories";
$resultOrders = $conn->query($sqlOrders);
if ($resultOrders->num_rows > 0) {
    $totalOrders = $resultOrders->fetch_assoc()['count'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
  <link rel="stylesheet" href="styles.css"> <!-- Link to CSS file -->
  <style>
    /* Basic styles for sidebar and content */
    .wrapper {
      display: flex;
      transition: all 0.3s;
    }

    #sidebar {
      width: 250px;
      background: #333;
      color: #fff;
      
      height: 100%;
      position: fixed;
      transition: all 0.3s;
    }

    #sidebar.active {
      width: 0;
      overflow: hidden;
    }

    #content {
      flex-grow: 1;
      margin-left: 250px;
      padding: 20px;
      transition: margin-left 0.3s;
    }

    #content.full-width {
      margin-left: 0;
    }

    .stat-card {
      background: #f8f9fa;
      padding: 20px;
      margin: 20px 0;
      border-radius: 5px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .stat-card h3 {
      margin: 0;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #6c757d;
      color: #fff;
      padding: 10px 20px;
      border-radius: 5px;
    }

    .profile-image {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      margin: 0 80px;
    }

    .admin-name {
      color: #fff;
      font-size: 18px;
      margin-top: 5px;
      text-align: center;
    }

    .list-items a {
      color: #fff;
      display: block;
      padding: 10px;
      text-decoration: none;
    }

    .list-items a:hover {
      background: #444;
    }

    #closeBtn {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: #ff4d4d;
      color: white;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
      font-size: 18px;
    }

    #closeBtn:hover {
      background-color: #ff6666;
    }

    /* Media queries for responsiveness */
    @media screen and (max-width: 768px) {
      #sidebar {
        width: 100%;
        height: auto;
        position: relative;
      }

      #sidebar.active {
        width: 0;
      }

      #content {
        margin-left: 0;
      }

      #content.full-width {
        margin-left: 0;
      }

      header {
        flex-direction: column;
        align-items: flex-start;
      }

      .stat-card {
        padding: 15px;
        margin: 10px 0;
      }
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
      <div class="sidebar-header">
        <h3>Admin Panel</h3>
        <img src="<?php echo $profile_image_path; ?>" alt="Profile Image" class="profile-image">
        <p class="admin-name"><?php echo $admin_name; ?></p>
        <button id="closeBtn">&times;</button>
      </div>
      <ul class="list-items">
        <li><a href="edit-profile.php">Edit Profile</a></li>
        <li><a href="edit-product.php">Product</a></li>
        <li><a href="edit-brand.php">Brand</a></li>
        <li><a href="edit-category.php">Category</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>

    <!-- Page Content -->
    <div id="content">
      <header>
        <button id="sidebarToggle">☰</button>
        <h2>Dashboard</h2>
      </header>

      <div class="stats">
        <div class="stat-card">
          <h3>Total Users</h3>
          <p><?php echo $totalUsers; ?></p>
        </div>
        <div class="stat-card">
          <h3>Total Products</h3>
          <p><?php echo $totalProducts; ?></p>
        </div>
        <div class="stat-card">
          <h3>Total Orders</h3>
          <p><?php echo $totalOrders; ?></p>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('sidebarToggle').addEventListener('click', function() {
      var sidebar = document.getElementById('sidebar');
      var content = document.getElementById('content');
      sidebar.classList.toggle('active');
      content.classList.toggle('full-width');
    });

    document.getElementById('closeBtn').addEventListener('click', function() {
      document.getElementById('sidebar').classList.add('active');
      document.getElementById('content').classList.add('full-width');
    });
  </script>
</body>
</html>
