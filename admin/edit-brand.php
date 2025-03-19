<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add Brand
if (isset($_POST['add_brand'])) {
    $brand_name = $_POST['brand_name'];
    $category_id = $_POST['category_id']; // Retrieve selected category ID

    // Check if an image file is uploaded
    if (isset($_FILES['brand_image']) && $_FILES['brand_image']['error'] == 0) {
        $image = $_FILES['brand_image'];
        $target_dir = "C:/xampp/htdocs/E-Commerce-Website/images/brand/";
        $image_name = basename($image['name']);
        $target_file = $target_dir . $image_name;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            // Save brand information, category ID, and image name in the database
            $sql = "INSERT INTO brand (name, image, category_id) VALUES ('$brand_name', '$image_name', $category_id)";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Brand and image added successfully!');</script>";
            } else {
                echo "<script>alert('Error adding brand: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('Error uploading image.');</script>";
        }
    } else {
        echo "<script>alert('Please upload an image.');</script>";
    }
}



// Handle Delete Brand
if (isset($_POST['delete_brand'])) {
    $brand_id = $_POST['brand_id'];
    $sql = "DELETE FROM brand WHERE id = '$brand_id'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Brand deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error deleting brand: " . mysqli_error($conn) . "');</script>";
    }
}

// Handle Search Brand
$search_results = [];
if (isset($_POST['search_brand'])) {
    $search_term = $_POST['search_term'];
    $sql = "SELECT * FROM brand WHERE name LIKE '%$search_term%'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $search_results[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Brands</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        .card {
            display: none;
            margin-top: 20px;
            padding: 20px;
            background-color: #fafafa;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .card h3 {
            margin-top: 0;
        }
        .button-group {
            margin-bottom: 20px;
        }
        button {
            padding: 10px 20px;
            margin-right: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function showCard(cardId) {
            document.getElementById('search-card').style.display = 'none';
            document.getElementById('add-card').style.display = 'none';
            document.getElementById('delete-card').style.display = 'none';

            document.getElementById(cardId).style.display = 'block';
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Edit Brands</h2>

    <!-- Buttons to Show Cards -->
    <div class="button-group">
        <button onclick="showCard('search-card')">Search Brand</button>
        <button onclick="showCard('add-card')">Add Brand</button>
        <button onclick="showCard('delete-card')">Delete Brand</button>
    </div>

    <!-- Search Brand Card -->
    <div id="search-card" class="card">
        <h3>Search Brand</h3>
        <form action="edit-brand.php" method="POST">
            <input type="text" name="search_term" placeholder="Enter brand name" required>
            <button type="submit" name="search_brand">Search</button>
        </form>

        <?php if (!empty($search_results)) : ?>
            <h4>Search Results:</h4>
            <ul>
                <?php foreach ($search_results as $brand) : ?>
                    <li><?php echo $brand['name']; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

   <!-- Add Brand Card -->
   <div id="add-card" class="card">
    <h3>Add New Brand</h3>
    <form action="edit-brand.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="brand_name" placeholder="Enter new brand" required>

        <!-- Category dropdown populated from database -->
        <select name="category_id" required>
            <option value="" disabled selected>Select category</option>
            <?php
            // Fetch categories from the database
            $category_query = "SELECT id, name FROM categories";
            $category_result = mysqli_query($conn, $category_query);

            // Check if categories exist and populate dropdown
            if (mysqli_num_rows($category_result) > 0) {
                while ($row = mysqli_fetch_assoc($category_result)) {
                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                }
            } else {
                echo "<option value='' disabled>No categories available</option>";
            }
            ?>
        </select>

        <input type="file" name="brand_image" accept="image/*" required>
        <button type="submit" name="add_brand">Add Brand</button>
    </form>
</div>



    <!-- Delete Brand Card -->
    <div id="delete-card" class="card">
        <h3>Delete Brand</h3>
        <form action="edit-brand.php" method="POST">
            <select name="brand_id" required>
                <option value="">Select Brand</option>
                <?php
                $sql = "SELECT * FROM brand";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                }
                ?>
            </select>
            <button type="submit" name="delete_brand">Delete Brand</button>
        </form>
    </div>

</div>

</body>
</html>
