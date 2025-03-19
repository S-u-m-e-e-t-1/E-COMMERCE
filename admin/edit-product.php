<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add Product
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $keywords = $_POST['keywords'];
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    // Handling image uploads
    $target_dir = "C:/xampp/htdocs/E-Commerce-Website/images/";
    $image1 = basename($_FILES["image1"]["name"]);
    $image2 = basename($_FILES["image2"]["name"]);
    $image3 = basename($_FILES["image3"]["name"]);

    move_uploaded_file($_FILES["image1"]["tmp_name"], $target_dir . $image1);
    move_uploaded_file($_FILES["image2"]["tmp_name"], $target_dir . $image2);
    move_uploaded_file($_FILES["image3"]["tmp_name"], $target_dir . $image3);

    $sql = "INSERT INTO product (name, description, keywords, category, brand, image1, image2, image3, price, date, status) 
            VALUES ('$name', '$description', '$keywords', '$category', '$brand', '$image1', '$image2', '$image3', '$price', NOW(), '$status')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Product added successfully!'); window.location.href='edit-product.php';</script>";
    } else {
        echo "<script>alert('Error adding product: " . mysqli_error($conn) . "');</script>";
    }
}

// Handle Delete Product
if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    $sql = "DELETE FROM product WHERE id = '$product_id'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Product deleted successfully!'); window.location.href='edit-product.php';</script>";
    } else {
        echo "<script>alert('Error deleting product: " . mysqli_error($conn) . "');</script>";
    }
}

// Handle Edit Product
if (isset($_POST['edit_product'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $keywords = $_POST['keywords'];
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $sql = "UPDATE product SET name='$name', description='$description', keywords='$keywords', category='$category', brand='$brand', price='$price', status='$status' WHERE id='$product_id'";

    // Update images if new ones are uploaded
    $target_dir = "C:/xampp/htdocs/E-Commerce-Website/images/";
    if (!empty($_FILES["image1"]["name"])) {
        $image1 = basename($_FILES["image1"]["name"]);
        move_uploaded_file($_FILES["image1"]["tmp_name"], $target_dir . $image1);
        $sql .= ", image1='$image1'";
    }
    if (!empty($_FILES["image2"]["name"])) {
        $image2 = basename($_FILES["image2"]["name"]);
        move_uploaded_file($_FILES["image2"]["tmp_name"], $target_dir . $image2);
        $sql .= ", image2='$image2'";
    }
    if (!empty($_FILES["image3"]["name"])) {
        $image3 = basename($_FILES["image3"]["name"]);
        move_uploaded_file($_FILES["image3"]["tmp_name"], $target_dir . $image3);
        $sql .= ", image3='$image3'";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Product updated successfully!'); window.location.href='edit-product.php';</script>";
    } else {
        echo "<script>alert('Error updating product: " . mysqli_error($conn) . "');</script>";
    }
}

// Handle Search Product
$search_results = [];
if (isset($_POST['search_product'])) {
    $search_term = $_POST['search_term'];
    $sql = "SELECT * FROM product WHERE name LIKE '%$search_term%' OR category LIKE '%$search_term%' OR brand LIKE '%$search_term%'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $search_results[] = $row;
    }
}

// Fetch categories
$categories = [];
$sql = "SELECT id, name FROM categories";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}

// Fetch brands
$brands = [];
$sql = "SELECT id, name FROM brand";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $brands[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Products</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }
    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .card {
        display: none; /* Hide cards by default */
        margin-top: 20px;
        padding: 20px;
        background-color: #fafafa;
        border-radius: 8px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }
    .card.active {
        display: block; /* Show only the active card */
    }
    .button-group {
        margin-bottom: 20px;
        text-align: center;
    }
    button {
        padding: 10px 20px;
        margin-right: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    button:hover {
        background-color: #0056b3;
    }
    .slider {
        width: 100%;
        position: relative;
        overflow: hidden;
        margin-bottom: 10px;
    }
    .slider img {
        width: 100%;
        max-height: 300px;
        object-fit: cover;
    }
    .input-field {
        margin-bottom: 15px;
        display: flex;
        flex-direction: column;
    }
    .input-field input,
    .input-field textarea,
    .input-field select {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .input-field input:focus,
    .input-field select:focus,
    .input-field textarea:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        outline: none;
    }
    .input-field textarea {
        resize: vertical;
    }
    .input-field label {
        margin-bottom: 5px;
        color: #333;
        font-weight: bold;
    }
</style>
</head>
<body>

<div class="container">
    <h2>Edit Products</h2>

    <div class="button-group">
        <button onclick="showCard('search-card')">Search Product</button>
        <button onclick="showCard('add-card')">Add Product</button>
        <button onclick="showCard('delete-card')">Delete Product</button>
    </div>

    <div id="search-card" class="card active">
        <h3>Search Product</h3>
        <form action="edit-product.php" method="POST">
            <input type="text" name="search_term" placeholder="Enter product name, category, or brand" required>
            <button type="submit" name="search_product">Search</button>
        </form>

        <?php if (!empty($search_results)) : ?>
            <h4>Search Results:</h4>
            <ul>
                <?php foreach ($search_results as $product) : ?>
                    <li>
                        <?php echo $product['name']; ?> 
                        <button onclick="editProduct(<?php echo $product['id']; ?>, '<?php echo $product['name']; ?>', '<?php echo $product['description']; ?>', '<?php echo $product['keywords']; ?>', '<?php echo $product['category']; ?>', '<?php echo $product['brand']; ?>', <?php echo $product['price']; ?>, '<?php echo $product['status']; ?>')">Edit</button>
                        <form style="display:inline;" method="POST" action="edit-product.php">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" name="delete_product">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <div id="add-card" class="card">
        <h3>Add Product</h3>
        <form action="edit-product.php" method="POST" enctype="multipart/form-data">
            <div class="input-field">
                <label for="name">Product Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="input-field">
                <label for="description">Description</label>
                <textarea name="description" required></textarea>
            </div>
            <div class="input-field">
                <label for="keywords">Keywords</label>
                <input type="text" name="keywords" required>
            </div>
            <div class="input-field">
                <label for="category">Category</label>
                <select name="category" required>
                    <?php foreach ($categories as $cat) : ?>
                        <option value="<?php echo $cat['name']; ?>"><?php echo $cat['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-field">
                <label for="brand">Brand</label>
                <select name="brand" required>
                    <?php foreach ($brands as $brand) : ?>
                        <option value="<?php echo $brand['name']; ?>"><?php echo $brand['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-field">
                <label for="price">Price</label>
                <input type="number" name="price" step="0.01" required>
            </div>
            <div class="input-field">
                <label for="status">Status</label>
                <select name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="input-field">
                <label for="image1">Image 1</label>
                <input type="file" name="image1" accept="image/*">
            </div>
            <div class="input-field">
                <label for="image2">Image 2</label>
                <input type="file" name="image2" accept="image/*">
            </div>
            <div class="input-field">
                <label for="image3">Image 3</label>
                <input type="file" name="image3" accept="image/*">
            </div>
            <button type="submit" name="add_product">Add Product</button>
        </form>
    </div>

    <div id="delete-card" class="card">
        <h3>Delete Product</h3>
        <form action="edit-product.php" method="POST">
            <div class="input-field">
                <label for="product_id">Product ID</label>
                <input type="number" name="product_id" required>
            </div>
            <button type="submit" name="delete_product">Delete Product</button>
        </form>
    </div>

    <div id="edit-card" class="card">
        <h3>Edit Product</h3>
        <form action="edit-product.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" id="edit_product_id">
            <div class="input-field">
                <label for="edit_name">Product Name</label>
                <input type="text" name="name" id="edit_name" required>
            </div>
            <div class="input-field">
                <label for="edit_description">Description</label>
                <textarea name="description" id="edit_description" required></textarea>
            </div>
            <div class="input-field">
                <label for="edit_keywords">Keywords</label>
                <input type="text" name="keywords" id="edit_keywords" required>
            </div>
            <div class="input-field">
                <label for="edit_category">Category</label>
                <select name="category" id="edit_category" required>
                    <?php foreach ($categories as $cat) : ?>
                        <option value="<?php echo $cat['name']; ?>"><?php echo $cat['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-field">
                <label for="edit_brand">Brand</label>
                <select name="brand" id="edit_brand" required>
                    <?php foreach ($brands as $brand) : ?>
                        <option value="<?php echo $brand['name']; ?>"><?php echo $brand['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-field">
                <label for="edit_price">Price</label>
                <input type="number" name="price" id="edit_price" step="0.01" required>
            </div>
            <div class="input-field">
                <label for="edit_status">Status</label>
                <select name="status" id="edit_status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="input-field">
                <label for="image1">Image 1 (Optional)</label>
                <input type="file" name="image1" accept="image/*">
            </div>
            <div class="input-field">
                <label for="image2">Image 2 (Optional)</label>
                <input type="file" name="image2" accept="image/*">
            </div>
            <div class="input-field">
                <label for="image3">Image 3 (Optional)</label>
                <input type="file" name="image3" accept="image/*">
            </div>
            <button type="submit" name="edit_product">Update Product</button>
        </form>
    </div>
</div>

<script>
function showCard(cardId) {
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => card.classList.remove('active'));
    document.getElementById(cardId).classList.add('active');
}

function editProduct(id, name, description, keywords, category, brand, price, status) {
    document.getElementById('edit_product_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_keywords').value = keywords;
    document.getElementById('edit_category').value = category;
    document.getElementById('edit_brand').value = brand;
    document.getElementById('edit_price').value = price;
    document.getElementById('edit_status').value = status;

    showCard('edit-card'); // Show the edit card
}
</script>

</body>
</html>
