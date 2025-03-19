<?php
    session_start();

    include('./includes/connection.php');

    $isLoggedIn = isset($_SESSION['user_id']);
    $user_id = $isLoggedIn ? $_SESSION['user_id'] : null;

    $product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $product = null;
    $brandName = '';
    $categoryName = '';

    if ($product_id > 0) {
        $sql = "SELECT * FROM product WHERE id = $product_id";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $product = $result->fetch_assoc();

            // Fetch brand and category names
            $brandResult = $conn->query("SELECT name FROM brand WHERE id = " . intval($product['brand']));
            $categoryResult = $conn->query("SELECT name FROM categories WHERE id = " . intval($product['category']));
            
            $brandName = $brandResult->fetch_assoc()['name'] ?? '';
            $categoryName = $categoryResult->fetch_assoc()['name'] ?? '';
        } else {
            echo "Product not found.";
            exit;
        }
    } else {
        echo "Invalid Product ID.";
        exit;
    }

    // Handle review submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_review') {
        if ($isLoggedIn) {
            // Retrieve and sanitize the rating and review text
            $rating = intval($_POST['rating']);
            $review_text = $conn->real_escape_string($_POST['review_text']);
            $review_image = null;

            // Handle image upload if an image is provided
            if (!empty($_FILES['review_image']['name'])) {
                $target_dir = "images/reviews/";
                $review_image = basename($_FILES["review_image"]["name"]);
                move_uploaded_file($_FILES["review_image"]["tmp_name"], $review_image);
            }

            // Prepare and execute the SQL statement to insert the review
            $sql = "INSERT INTO reviews (product_id, user_id, rating, review_text, review_image) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiiss", $product_id, $user_id, $rating, $review_text, $review_image);
            $stmt->execute();
            $stmt->close();

            // Success message
            echo "<script>alert('Review submitted successfully');</script>";
        } else {
            // Alert user to log in if not authenticated
            echo "<script>alert('Please log in first.');</script>";
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      <?php echo htmlspecialchars($product['name']); ?> - Product View
    </title>
    <link rel="stylesheet" href="./css/product_view.css">
  </head>
  <body>
    
    <div class="container">
      <!-- Left: Image Slider -->
      <div class="image-slider">
        <div class="slider-images">
          <img
            src="http://localhost/E-Commerce-Website/images/product/<?php echo $product['image1']; ?>"
            alt="<?php echo htmlspecialchars($product['name']); ?>"
            class="active"
          />
          <?php if ($product['image2']): ?>
          <img
            src="http://localhost/E-Commerce-Website/images/product/<?php echo $product['image2']; ?>"
            alt="<?php echo htmlspecialchars($product['name']); ?>"
          />
          <?php endif; ?>
          <?php if ($product['image3']): ?>
          <img
            src="http://localhost/E-Commerce-Website/images/product/<?php echo $product['image3']; ?>"
            alt="<?php echo htmlspecialchars($product['name']); ?>"
          />
          <?php endif; ?>
        </div>
        <div class="slider-buttons">
          <button id="prev" onclick="changeSlide(-1)">❮</button>
          <button id="next" onclick="changeSlide(1)">❯</button>
        </div>
        <div class="buttons">
         <button class="buy-now" onclick="addToCart(<?php echo $product_id; ?>)">Add to Cart</button>
         <button class="buy-now" onclick="buyNow()">Buy Now</button>
        </div>
      </div>

      <!-- Right: Product Details and Review Section -->
      <div class="product-info">
        <div class="product-details">
          <h1><?php echo htmlspecialchars($product['name']); ?></h1>
          <p>
            Brand:
            <?php echo htmlspecialchars($brandName); ?>
          </p>
          <p>
            Category:
            <?php echo htmlspecialchars($categoryName); ?>
          </p>
          <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
          <p>
            Description:
            <?php echo htmlspecialchars($product['description']); ?>
          </p>
        </div>

        <!-- Review Section -->
        <div class="review-section">
          <h3>Submit Your Review</h3>
            <form
              action="product_view.php?id=<?php echo $product_id; ?>"
              method="post"
              enctype="multipart/form-data"
            >
              <input type="hidden" name="action" value="submit_review" />

              <!-- Star Rating -->
              <label for="star-rating">Star Rating:</label>
              <div class="star-rating" id="star-rating">
                <input
                  type="radio"
                  name="rating"
                  value="1"
                  id="1-star"
                  required
                />
                <label for="1-star" aria-label="1 star">&#9733;</label>
                <input type="radio" name="rating" value="2" id="2-stars" />
                <label for="2-stars" aria-label="2 stars">&#9733;</label>
                <input type="radio" name="rating" value="3" id="3-stars" />
                <label for="3-stars" aria-label="3 stars">&#9733;</label>
                <input type="radio" name="rating" value="4" id="4-stars" />
                <label for="4-stars" aria-label="4 stars">&#9733;</label>
                <input type="radio" name="rating" value="5" id="5-stars" />
                <label for="5-stars" aria-label="5 stars">&#9733;</label>
              </div>

              <!-- Text Review -->
              <label for="review_text">Review Text:</label>
              <textarea
                name="review_text"
                id="review_text"
                rows="4"
                required
              ></textarea
              ><br />

              <!-- Upload Review Image -->
              <label for="review_image">Upload Image (optional):</label>
              <input
                type="file"
                name="review_image"
                id="review_image"
                accept="image/jpeg, image/png, image/jpg"
              />

              <!-- Submit Review Button -->
              <button type="submit" class="submit-review">Submit Review</button>
            </form>
          <a
            href="all-reviews.php?id=<?php echo $product_id; ?>"
            class="reviews-link"
            >View All Reviews</a
          >
        </div>
      </div>
    </div>

    <script>
      let currentSlide = 0;
      const slides = document.querySelectorAll(".slider-images img");

      function changeSlide(direction) {
        slides[currentSlide].classList.remove("active");
        currentSlide =
          (currentSlide + direction + slides.length) % slides.length;
        slides[currentSlide].classList.add("active");
      }

      function addToCart(productId) {
          fetch("./user/add_to_cart.php", {
              method: "POST",
              headers: {
                  "Content-Type": "application/x-www-form-urlencoded",
              },
              body: `product_id=${productId}`,
          })
          .then((response) => response.json())
          .then((data) => {
              if (data.status === "success") {
                  alert(data.message); // Show success message
              } else {
                  alert("Error: " + data.message); // Show error message
              }
          })
          .catch((error) => {
              console.error("Error:", error);
          });
      }

      function buyNow() {
        const product = {
            id: <?php echo json_encode($product['id']); ?>,
            name: <?php echo json_encode($product['name']); ?>,
            price: <?php echo json_encode($product['price']); ?>,
            quantity: 1, // Default to 1, can be modified as per user input
        };

        // Send the product to buy_now.php
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "./paypal/buy_now.php";

        // Create a hidden input for the product
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "products[]";
        input.value = JSON.stringify(product);
        form.appendChild(input);

        document.body.appendChild(form);
        form.submit();
      }
    </script>
  </body>
</html>
