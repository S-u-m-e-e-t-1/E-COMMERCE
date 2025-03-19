<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add Category
if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    
    if (isset($_FILES['category_image']) && $_FILES['category_image']['error'] == 0) {
        $image = $_FILES['category_image'];
        $target_dir = "C:/xampp/htdocs/E-Commerce-Website/images/categories/";
        $image_name = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($image['name'])); // Clean file name
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif']) && move_uploaded_file($image['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO categories (name, image) VALUES (?, ?)");
            $stmt->bind_param("ss", $category_name, $image_name);

            if ($stmt->execute()) {
                echo "<script>alert('Category and image added successfully!');</script>";
            } else {
                echo "<script>alert('Error adding category: " . $conn->error . "');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Error uploading image or invalid file type.');</script>";
        }
    } else {
        echo "<script>alert('Please upload an image.');</script>";
    }
}

// Handle Delete Category
if (isset($_POST['delete_category'])) {
    $category_id = $_POST['category_id'];
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        echo "<script>alert('Category deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error deleting category: " . $conn->error . "');</script>";
    }
    $stmt->close();
}

// Handle Search Category
$search_results = [];
if (isset($_POST['search_category'])) {
    $search_term = "%" . $_POST['search_term'] . "%";
    $stmt = $conn->prepare("SELECT * FROM categories WHERE name LIKE ?");
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $search_results[] = $row;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Categories</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
    <h2>Edit Categories</h2>

    <!-- Buttons to Show Cards -->
    <div class="button-group">
        <button onclick="showCard('search-card')">Search Category</button>
        <button onclick="showCard('add-card')">Add Category</button>
        <button onclick="showCard('delete-card')">Delete Category</button>
    </div>

    <!-- Search Category Card -->
    <div id="search-card" class="card">
        <h3>Search Category</h3>
        <form action="edit-category.php" method="POST">
            <input type="text" name="search_term" placeholder="Enter category name" required>
            <button type="submit" name="search_category">Search</button>
        </form>

        <?php if (!empty($search_results)) : ?>
            <h4>Search Results:</h4>
            <ul>
                <?php foreach ($search_results as $category) : ?>
                    <li><?php echo $category['name']; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <!-- Add Category Card -->
    <div id="add-card" class="card">
        <h3>Add New Category</h3>
        <form action="edit-category.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="category_name" placeholder="Enter new category" required>
            <input type="file" name="category_image" accept="image/*" required>
            <button type="submit" name="add_category">Add Category</button>
        </form>
    </div>

    <!-- Delete Category Card -->
    <div id="delete-card" class="card">
        <h3>Delete Category</h3>
        <form action="edit-category.php" method="POST">
            <select name="category_id" required>
                <option value="">Select Category</option>
                <?php
                $conn = new mysqli($servername, $username, $password, $dbname);
                $result = $conn->query("SELECT * FROM category");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                }
                $conn->close();
                ?>
            </select>
            <button type="submit" name="delete_category">Delete Category</button>
        </form>
    </div>

</div>

</body>
</html>
