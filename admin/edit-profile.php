<?php
// Include database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start session to access logged-in admin data
session_start();

// Get admin's current data
$admin_id = $_SESSION['id']; // assuming admin_id is stored in the session after login

$sql = "SELECT * FROM admin WHERE id = '$admin_id'";
$result = mysqli_query($conn, $sql);
$admin = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // hash if needed
    $date_of_birth = $_POST['date_of_birth'];
    $phone_number = $_POST['phone_number'];

    // File upload for profile image
    $target_dir = "C:/xampp/htdocs/E-Commerce-Website/admin/profile/";
    $profile_image = $_FILES['profile_image']['name'];
    $target_file = $target_dir . basename($profile_image);

    // Only upload image if a new one is provided
    if (!empty($profile_image)) {
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file);

        // Update admin data with profile image
        $sql = "UPDATE admin SET 
                name = '$name', 
                email = '$email', 
                password = '$password', 
                date_of_birth = '$date_of_birth', 
                phone_number = '$phone_number', 
                image = '$profile_image' 
                WHERE id = '$admin_id'";
    } else {
        // Update without changing the profile image
        $sql = "UPDATE admin SET 
                name = '$name', 
                email = '$email', 
                password = '$password', 
                date_of_birth = '$date_of_birth', 
                phone_number = '$phone_number' 
                WHERE id = '$admin_id'";
    }

    if (mysqli_query($conn, $sql)) {
        // Profile update success
        echo "<script>
            alert('Profile updated successfully! Logging out...');
            window.location.href = 'index.php';
        </script>";
    

        session_start(); 
        session_unset();
        session_destroy();
    
        exit();
    } else {
        // Profile update failure
        echo "<script>
            alert('Error updating profile: " . mysqli_error($conn) . "');
            window.location.href = 'admin-panel.php'; // Redirect back to the admin panel or another page
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
            display: block;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #28a745;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .profile-preview {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .profile-preview img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }

        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Profile</h2>
    <form action="edit-profile.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $admin['name']; ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $admin['email']; ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo $admin['password']; ?>" required>
        </div>

        <div class="form-group">
            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo $admin['date_of_birth']; ?>" required>
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" value="<?php echo $admin['phone_number']; ?>" required>
        </div>

        <div class="form-group">
            <label for="profile_image">Profile Image:</label>
            <input type="file" id="profile_image" name="profile_image" onchange="previewImage(event)">
        </div>

        <!-- Profile Image Preview -->
        <div class="profile-preview">
            <img id="profile_image_preview" src="http://localhost/E-Commerce-Website/admin/profile/<?php echo $admin['image']; ?>" alt="Profile Image">
        </div>

        <input type="submit" value="Update Profile">
    </form>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('profile_image_preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

</body>
</html>
