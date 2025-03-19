<?php

    include('./includes/connection.php');

    $product_id = $_GET['id'];
    $sql = "SELECT r.rating, r.review_text, r.review_image, u.name AS user_name 
            FROM reviews r 
            JOIN user u ON r.user_id = u.user_id 
            WHERE r.product_id = ? 
            ORDER BY r.review_date DESC";
   
    $stmt1 = $conn->prepare($sql);
    $stmt1->bind_param("i", $product_id);
    $stmt1->execute();
    $result = $stmt1->get_result();
    $reviews = $result->fetch_all(MYSQLI_ASSOC);
    $stmt1->close();
    $sql = "SELECT name FROM product WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    $product_name = $product ? htmlspecialchars($product['name']) : "Unknown Product";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>All Reviews</title>
        <link rel="stylesheet" href="./css/all-reviews.css">
       
    </head>
    <body>
        <div class="review-section">
        <h2>All Reviews for Product: <?php echo $product_name; ?></h2>
            
            <div class="review-columns">
                <div class="column">
                    <h3>Ratings</h3>
                    <div class="slider">
                        <?php foreach ($reviews as $review): ?>
                            <div class="slide">
                                <p><?php echo str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="column">
                    <h3>Text Reviews</h3>
                    <div class="slider">
                        <?php foreach ($reviews as $review): ?>
                            <div class="slide">
                                <p><?php echo htmlspecialchars($review['review_text']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="column">
                    <h3>Review Images</h3>
                    <div class="slider">
                        <?php foreach ($reviews as $review): ?>
                            <div class="slide">
                                <?php if ($review['review_image']): ?>
                                    <img src="http://localhost/E-Commerce-Website/images/reviews<?php echo htmlspecialchars($review['review_image']); ?>" alt="Review Image" class="review-img">
                                <?php else: ?>
                                    <p>No image uploaded</p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <script >
                document.addEventListener('DOMContentLoaded', function() {
                    const sliders = document.querySelectorAll('.slider');
                    sliders.forEach(slider => {
                        let index = 0;
                        const slides = slider.querySelectorAll('.slide');
                        slides[index].classList.add('active');

                        // Function to change slide
                        function changeSlide() {
                            slides[index].classList.remove('active');
                            index = (index + 1) % slides.length; // Loop back to the first slide
                            slides[index].classList.add('active');
                        }

                        setInterval(changeSlide, 3000); // Change slide every 3 seconds
                    });
                });

        </script>
    </body>
</html>
