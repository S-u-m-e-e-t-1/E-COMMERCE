<?php
    // Database connection
    include('./includes/connection.php');


    $showSuccessPopup = false;
    $showErrorPopup = false;
    $showAlreadyRegisteredPopup = false;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $dob = $_POST['date_of_birth'];
        $profileImage = $_FILES['profile_image'];
        $password = $_POST['password'];

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if email or phone number already exists
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ? OR mobile = ?");
        $stmt->bind_param("ss", $email, $mobile);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User already exists
            $showAlreadyRegisteredPopup = true;
        } else {
            // Handle profile image upload
            $targetDir = "C:/xampp/htdocs/E-Commerce-Website/images/user/";
            $fileName = basename($profileImage["name"]);
            $targetFile = $targetDir . $fileName;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if the file is an image
            $check = getimagesize($profileImage["tmp_name"]);
            if ($check === false) {
                $uploadOk = 0;
                $showErrorPopup = true;
            }

            // Upload file and save only the filename in the database
            if ($uploadOk == 1 && move_uploaded_file($profileImage["tmp_name"], $targetFile)) {
                // Insert data into the user table
                $stmt = $conn->prepare("INSERT INTO user (name, mobile, email, profile_image, address, date_of_birth, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssss", $name, $mobile, $email, $fileName, $address, $dob, $hashedPassword);

                if ($stmt->execute()) {
                    $showSuccessPopup = true;
                } else {
                    $showErrorPopup = true;
                }
            } else {
                $showErrorPopup = true;
            }
        }
        
        $stmt->close();
    }
    $conn->close();
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Sign Up</title>
        <link rel="stylesheet" href="./css/register.css">
    </head>
    <body>
        <div class="signup-card">
            <form id="signupForm" method="POST" enctype="multipart/form-data">
                <h2>Sign Up</h2>
                <img id="profilePreview" src="default-avatar.png" alt="Profile Preview"/>
                <input type="file" name="profile_image" id="profileImage" accept="image/*" onchange="previewImage();" required>
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="text" name="mobile" placeholder="Mobile" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="address" placeholder="Address" required>
                <input type="date" name="date_of_birth" placeholder="Date of Birth" required>
                <button type="submit">Register</button>
            </form>
        </div>

        <!-- Success Popup -->
        <div id="successPopup" class="popup">
            <div class="popup-content">
                <div class="loading-circle success-circle"></div>
                <span class="tick">&#10003;</span>
                <p>Registration Successful!</p>
            </div>
        </div>

        <!-- Error Popup -->
        <div id="errorPopup" class="popup">
            <div class="popup-content">
                <div class="loading-circle error-circle"></div>
                <span class="cross">&#10060;</span>
                <p>Registration Failed. Try Again!</p>
            </div>
        </div>

        <!-- Already Registered Popup -->
        <div id="alreadyRegisteredPopup" class="popup">
            <div class="popup-content">
                <div class="loading-circle error-circle"></div>
                <span class="info">&#8505;</span>
                <p>User is already registered. Redirecting to"index...</p>
            </div>
        </div>

        <script>
            // Show image preview
            function previewImage() {
                const file = document.getElementById("profileImage").files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById("profilePreview").src = e.target.result;
                }
                reader.readAsDataURL(file);
            }

            // Show success popup and redirect to"index
            function showSuccessPopup() {
                document.getElementById("successPopup").style.display = "flex";
                setTimeout(() => {
                    window.location.href = "index.php";
                }, 2000);
            }

            // Show error popup and redirect to sign-up page
            function showErrorPopup() {
                document.getElementById("errorPopup").style.display = "flex";
                setTimeout(() => {
                    window.location.href = "signup.php";
                }, 2000);
            }

            // Show already registered popup and redirect to"index
            function showAlreadyRegisteredPopup() {
                document.getElementById("alreadyRegisteredPopup").style.display = "flex";
                setTimeout(() => {
                    window.location.href = "index.php";
                }, 2000);
            }

            // PHP-driven JavaScript execution
            <?php if ($showSuccessPopup): ?>
                showSuccessPopup();
            <?php elseif ($showErrorPopup): ?>
                showErrorPopup();
            <?php elseif ($showAlreadyRegisteredPopup): ?>
                showAlreadyRegisteredPopup();
            <?php endif; ?>
        </script>
    </body>

</html>