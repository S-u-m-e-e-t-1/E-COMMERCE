<?php
    session_start();
    ob_start();

    include('./includes/connection.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (empty($username) || empty($password)) {
            echo "<script>alert('Please fill in all fields.');</script>";
        } else {
            // Use a prepared statement to prevent SQL injection
            $stmt = $conn->prepare("SELECT user_id, password FROM user WHERE name = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                if (password_verify($password, $row['password'])) {
                    // Successful login
                    session_regenerate_id(true); // Prevent session fixation
                    $_SESSION['username'] = $username;
                    $_SESSION['user_id'] = $row['user_id'];

                    header("Location: user/dashboard.php");
                    exit();
                } else {
                    // Generic error message
                    echo "<script>alert('Invalid credentials. Please try again.');</script>";
                }
            } else {
                // Generic error message
                echo "<script>alert('Invalid credentials. Please try again.');</script>";
            }
        }
    }
    if (isset($_GET['q'])) {
        $query = $conn->real_escape_string($_GET['q']);
        $output = "";
    
        if (!empty($query)) {
            $sql = "SELECT id, name FROM product WHERE name LIKE '%$query%' LIMIT 10";
            $result = $conn->query($sql);
    
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $output .= "<div onclick='redirectToProduct(" . $row['id'] . ")'>" . htmlspecialchars($row['name']) . "</div>";
                }
            } else {
                $output .= "<div>No suggestions found</div>";
            }
        }
    
        echo $output;
        exit; // Stop further execution after returning AJAX response
    }
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>My Website</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
        <link rel="stylesheet" href="css/style-nav-head.css" />
        <link rel="stylesheet" href="css/style-img-slide.css" />
        <link rel="stylesheet" href="css/style-product-cards.css" />
        <link rel="stylesheet" href="css/style-footer.css" />
        <link rel="stylesheet" href="css/style-login.css" />
        <style>
         
                .search-container {
                    position: relative;
                    width: 400px; 
                    max-width: 100%; 
                    margin: 0 auto; 
                }
                .search-container input[type="text"] {
                    width: 100%;
                    padding: 10px 40px 10px 10px; 
                    font-size: 16px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    box-sizing: border-box; 
                }
                .search-container button {
                    position: absolute;
                    top: 50%;
                    right: 10px;
                    transform: translateY(-50%);
                    background: none;
                    border: none;
                    cursor: pointer;
                    font-size: 18px;
                    color: #666;
                }
                .search-container button:hover {
                    color: #000;
                }
                #suggestions {
                    border: 1px solid #ccc;
                    max-height: 150px;
                    overflow-y: auto;
                    background-color: #fff;
                    position: absolute;
                    width: 100%;
                    color:black;
                    top: 50px; 
                    z-index: 1000;
                    border-radius: 5px;
                    box-sizing: border-box;
                }
                #suggestions div {
                    padding: 8px;
                    cursor: pointer;
                    font-size: 14px;
                #suggestions div:hover {
                    background-color: #f1f1f1;
                }
                @media (max-width: 768px) {
                    .search-container {
                        width: 100%;
                        padding: 0 10px; 
                    }

                    .search-container input[type="text"] {
                        font-size: 14px; 
                        padding: 8px 35px 8px 8px; 
                    }

                    .search-container button {
                        font-size: 16px; 
                        right: 8px;
                    }
                }
                @media (max-width: 480px) {
                    #suggestions div {
                        font-size: 12px; 
                        padding: 6px; 
                    }
                }

        </style>
    </head>
    <body>
        <header>
            <div class="logo">
                <img src="images/image.png" style="margin-left: -37px;
                    width: 15vw;
                    height: 5vh;
                    margin-right: 13px;"/>
            </div>
            <div class="search-container">
                <input type="text" id="searchBox" placeholder="Search for products..." onkeyup="showSuggestions(this.value)">
                <button><i class="fas fa-search"></i></button>
                <div id="suggestions"></div>
                <script src="js/search.js"></script>
              </div>
            <nav class="nav-links">
                <div class="login">
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- User is logged in, show dropdown menu -->
                    <a href="#l" id="loginBtn">
                        <i class="fas fa-user"></i> My Account <i class="fas fa-caret-down"></i>
                    </a>
                    <div class="dropdown">
                        <a href="./user/dashboard.php"><i class="fas fa-user"></i> My Profile</a>
                        <a href="#"><i class="fas fa-gift"></i> Rewards</a>
                        <a href="./user/view_all_orders.php"><i class="fas fa-box"></i> Orders</a>
                        <a href="./user/view_all_cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
                        <a href="#"><i class="fas fa-credit-card"></i> Gift Cards</a>
                        <a href="./user/logout.php" style="color: red; text-align: right">Logout</a>
                    </div>
                    <?php else: ?>
                    <!-- User is not logged in, show login and sign-up links -->
                    <a href="#l" id="loginBtn">
                        <i class="fas fa-user"></i> Login <i class="fas fa-caret-down"></i>
                    </a>
                    <div class="dropdown">
                        <a href="register.php" style="color: blue;">Sign Up</a>
                    </div>
                    <!-- Login Modal -->
                    <div id="loginModal" class="modal" style="display: none;">
                        <div class="modal-content">
                            <span class="close"
                                onclick="document.getElementById('loginModal').style.display='none'">&times;</span>
                            <h2>Welcome to Our Website</h2>
                            <form class="login-form" method="POST" action="">
                                <input type="text" name="username" placeholder="Username" required>
                                <input type="password" name="password" placeholder="Password" required>
                                <button type="submit">Login</button>
                                <h6><a href="forgot_password.php" style="color:black"> Forgot Password</a></h6>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>          
                <script src="js/login.js"></script>
            </nav>
        </header>
       

        <div class="categories">
            <?php
            $category_query = "SELECT id, name, image FROM categories"; // Assuming 'id' is the category ID
            $category_result = mysqli_query($conn, $category_query);

            if (mysqli_num_rows($category_result) > 0) {
                while ($row = mysqli_fetch_assoc($category_result)) {
                    $image_path = "http://localhost/E-Commerce-Website/images/categories/" . $row['image'];
                    $category_id = $row['id']; // Get category ID

                    echo '<div class="category">';
                    echo '<a href="category_products.php?category_id=' . $category_id . '" style="text-decoration: none; color: inherit;">'; // Inline styles to remove underline and inherit color
                    echo '<img src="' . $image_path . '" alt="' . htmlspecialchars($row['name'], ENT_QUOTES) . '" />';
                    echo '<span>' . htmlspecialchars($row['name'], ENT_QUOTES) . '</span>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo "<p>No categories available</p>";
            }
            ?>
        </div>

        <div class="slider">
            <div class="slides">
                <!-- Slide 1 -->
                <div class="slide">
                    <img src="images/image1.png" alt="Slide 1">
                    <div class="caption">Caption for Slide 1</div>
                </div>
                <!-- Slide 2 -->
                <div class="slide">
                    <img src="images/image2.png" alt="Slide 2">
                    <div class="caption">Caption for Slide 2</div>
                </div>
                <!-- Slide 3 -->
                <div class="slide">
                    <img src="images/image3.png" alt="Slide 3">
                    <div class="caption">Caption for Slide 3</div>
                </div>
                <!-- Slide 4 -->
                <div class="slide">
                    <img src="images/image4.png" alt="Slide 4">
                    <div class="caption">Caption for Slide 4</div>
                </div>
                <!-- Slide 5 -->
                <div class="slide">
                    <img src="images/image5.png" alt="Slide 5">
                    <div class="caption">Caption for Slide 5</div>
                </div>
            </div>

            <!-- Navigation arrows -->
            <div class="navigation">
                <span class="prev" onclick="plusSlides(-1)">&#10094;</span>
                <span class="next" onclick="plusSlides(1)">&#10095;</span>
            </div>

            <!-- Dots -->
            <div class="dots">
                <span class="dot" onclick="currentSlide(1)"></span>
                <span class="dot" onclick="currentSlide(2)"></span>
                <span class="dot" onclick="currentSlide(3)"></span>
                <span class="dot" onclick="currentSlide(4)"></span>
                <span class="dot" onclick="currentSlide(5)"></span>
            </div>
            <script src="js/js-slide-show.js"></script>
        </div>

        
        <div class="product-sections">
            <?php
            // Fetch all categories from the database
            $category_query = "SELECT id, name FROM categories";
            $category_result = mysqli_query($conn, $category_query);

            $categories = [];
            if (mysqli_num_rows($category_result) > 0) {
                while ($category = mysqli_fetch_assoc($category_result)) {
                    $categories[] = $category;
                }

                // Shuffle and pick only 5 random categories
                shuffle($categories);
                $categories = array_slice($categories, 0, 5);

                // Loop through the selected categories
                foreach ($categories as $category) {
                    $category_id = $category['id'];
                    $category_name = htmlspecialchars($category['name'], ENT_QUOTES);

                    echo '<div class="product-section">';
                    echo '<h3>' . $category_name . '</h3>';
                    echo '<div class="product-cards">';

                    // Fetch products for each category
                    $product_query = "SELECT id, name, image1, price FROM product WHERE category = $category_id LIMIT 10";
                    $product_result = mysqli_query($conn, $product_query);

                    if (mysqli_num_rows($product_result) > 0) {
                        while ($row = mysqli_fetch_assoc($product_result)) {
                            echo '<div class="product-card">';
                            echo '<a href="product_view.php?id=' . $row['id'] . '">';
                            echo '<img src="http://localhost/E-Commerce-Website/images/product/' . $row['image1'] . '" alt="' . htmlspecialchars($row['name'], ENT_QUOTES) . '" />';
                            echo '<p>' . htmlspecialchars($row['name'], ENT_QUOTES) . ' - $' . htmlspecialchars($row['price'], ENT_QUOTES) . '</p>';
                            echo '</a>';
                            echo '</div>';
                        }
                    } else {
                        echo "<p>No products available in this category</p>";
                    }

                    echo '</div>'; // End of product-cards
                    echo '</div>'; // End of product-section
                }
            } else {
                echo "<p>No categories available</p>";
            }
            ?>
        </div>

        <div class="product-sections">
            <?php
            // Fetch all brands from the database
            $brand_query = "SELECT id, name FROM brand";
            $brand_result = mysqli_query($conn, $brand_query);

            $brands = [];
            if (mysqli_num_rows($brand_result) > 0) {
                while ($brand = mysqli_fetch_assoc($brand_result)) {
                    $brands[] = $brand;
                }

                // Shuffle and pick only 5 random brands
                shuffle($brands);
                $brands = array_slice($brands, 0, 5);

                // Loop through the selected brands
                foreach ($brands as $brand) {
                    $brand_id = $brand['id'];
                    $brand_name = htmlspecialchars($brand['name'], ENT_QUOTES);

                    echo '<div class="product-section">';
                    echo '<h3>' . $brand_name . '</h3>';
                    echo '<div class="product-cards">';

                    // Fetch products for each brand
                    $product_query = "SELECT id, name, image1, price FROM product WHERE brand = $brand_id LIMIT 10";
                    $product_result = mysqli_query($conn, $product_query);

                    if (mysqli_num_rows($product_result) > 0) {
                        while ($row = mysqli_fetch_assoc($product_result)) {
                            echo '<div class="product-card">';
                            echo '<a href="product_view.php?id=' . $row['id'] . '">';
                            echo '<img src="http://localhost/E-Commerce-Website/images/product/' . $row['image1'] . '" alt="' . htmlspecialchars($row['name'], ENT_QUOTES) . '" />';
                            echo '<p>' . htmlspecialchars($row['name'], ENT_QUOTES) . ' - $' . htmlspecialchars($row['price'], ENT_QUOTES) . '</p>';
                            echo '</a>';
                            echo '</div>';
                        }
                    } else {
                        echo "<p>No products available in this brand</p>";
                    }

                    echo '</div>'; // End of product-cards
                    echo '</div>'; // End of product-section
                }
            } else {
                echo "<p>No brands available</p>";
            }
            ?>
        </div>

        <footer class="footer">
            <div class="footer-container">
                <!-- About Section -->
                <div class="footer-column">
                    <h3>ABOUT</h3>
                    <ul>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Flipkart Stories</a></li>
                        <li><a href="#">Press</a></li>
                        <li><a href="#">Corporate Information</a></li>
                    </ul>
                </div>
                <!-- Group Companies Section -->
                <div class="footer-column">
                    <h3>GROUP COMPANIES</h3>
                    <ul>
                        <li><a href="#">Myntra</a></li>
                        <li><a href="#">Cleartrip</a></li>
                        <li><a href="#">Shopsy</a></li>
                    </ul>
                </div>
                <!-- Help Section -->
                <div class="footer-column">
                    <h3>HELP</h3>
                    <ul>
                        <li><a href="#">Payments</a></li>
                        <li><a href="#">Shipping</a></li>
                        <li><a href="#">Cancellation & Returns</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Report Infringement</a></li>
                    </ul>
                </div>
                <!-- Consumer Policy Section -->
                <div class="footer-column">
                    <h3>CONSUMER POLICY</h3>
                    <ul>
                        <li><a href="#">Cancellation & Returns</a></li>
                        <li><a href="#">Terms Of Use</a></li>
                        <li><a href="#">Security</a></li>
                        <li><a href="#">Privacy</a></li>
                        <li><a href="#">Sitemap</a></li>
                        <li><a href="#">EPR Compliance</a></li>
                    </ul>
                </div>
                <!-- Contact Section -->
                <div class="footer-column">
                    <h3>Mail Us:</h3>
                    <p>
                        Flipkart Internet Private Limited,<br />
                        Buildings Alyssa, Begonia & Clove Embassy Tech Village,<br />
                        Outer Ring Road, Devarabeesanahalli Village, Bengaluru,<br />
                        Karnataka, 560103, India
                    </p>
                    <h3>Registered Office Address:</h3>
                    <p>
                        Flipkart Internet Private Limited,<br />
                        CIN: U51109KA2012PTC066107<br />
                        Telephone: <a href="tel:+04445614700">044-45614700</a> /
                        <a href="tel:+04467415800">044-67415800</a>
                    </p>
                    <!-- Social Media Icons -->
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </footer>

    </body>

</html>