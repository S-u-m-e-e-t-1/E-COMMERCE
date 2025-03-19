
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product List</title>
       <link rel="stylesheet" href="./css/category_products.css">
    </head>
    <body>
        <?php

            include('./includes/connection.php');


            $category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

            $category_query = "SELECT name FROM categories WHERE id = $category_id";
            $category_result = mysqli_query($conn, $category_query);
            $category_name = mysqli_fetch_assoc($category_result)['name'] ?? 'Category Not Found';

            echo '<h1 class="category-title">' . htmlspecialchars($category_name, ENT_QUOTES) . '</h1>';

            $product_query = "
                SELECT p.id, p.name, p.price, p.image1, b.name AS brand_name 
                FROM product p 
                JOIN brand b ON p.brand= b.id
                WHERE p.category= $category_id";
            $product_result = mysqli_query($conn, $product_query);

            echo '<div class="container">';
                if (mysqli_num_rows($product_result) > 0) {
                    while ($row = mysqli_fetch_assoc($product_result)) {
                        // Wrap the product card in a link to redirect on click
                        echo '<a href="http://localhost/E-Commerce-Website/product_view.php?id=' . $row['id'] . '" style="text-decoration: none; color: inherit;">';
                        echo '<div class="product-card">';
                        echo '<img src="http://localhost/E-Commerce-Website/images/product/' . htmlspecialchars($row['image1'], ENT_QUOTES) . '" alt="' . htmlspecialchars($row['name'], ENT_QUOTES) . '" class="product-image">';
                        echo '<div class="product-details">';
                        echo '<p class="product-name">' . htmlspecialchars($row['name'], ENT_QUOTES) . '</p>';
                        echo '<p class="product-brand">' . htmlspecialchars($row['brand_name'], ENT_QUOTES) . '</p>';
                        echo '<p class="product-price">$' . number_format($row['price'], 2) . '</p>';
                        echo '</div>';
                        echo '<form action="./user/add_to_cart.php" method="POST">';
                        echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
                        echo '<button type="submit" class="add-to-cart">Add to Cart</button>';
                        echo '</form>';
                        echo '</div>';
                        echo '</a>'; // Close the link
                    }
                } else {
                    echo "<p>No products available</p>";
                }
            echo '</div>';
        ?>
    </body>
</html>
