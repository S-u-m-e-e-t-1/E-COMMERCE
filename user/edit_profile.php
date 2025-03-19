<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }

    include('../includes/connection.php');
    // Fetch current user details from database
    $user_id = $_SESSION['user_id'];
    $query = $conn->prepare("SELECT * FROM user WHERE user_id = ?");
    $query->bind_param("i", $user_id);
    $query->execute();
    $user = $query->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit Profile</title>
        
    </head>
    <body onload="document.getElementById('confirmationPopup').style.display = 'none';">

        <div class="edit-profile-container">
            <form id="editProfileForm" action="" method="POST" enctype="multipart/form-data">
                <!-- Existing form fields -->
                <label>Profile Image:</label>
                <div class="image-preview">
                    <img id="profileImagePreview" src="<?php echo 'http://localhost/E-Commerce-Website/images/user/' . $user['profile_image']; ?>" alt="Current Profile Image">
                </div>
                <input type="file" name="new_profile_image" id="newProfileImage" accept="image/*">

                <label>Name:</label>
                <input type="text" name="name" value="<?php echo $user['name']; ?>" required>

                <label>Mobile:</label>
                <input type="text" name="mobile" value="<?php echo $user['mobile']; ?>" required>

                <label>Email:</label>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" required>

                <label>Address:</label>
                <input type="text" name="address" value="<?php echo $user['address']; ?>" required>

                <label>Date of Birth:</label>
                <input type="date" name="date_of_birth" value="<?php echo $user['date_of_birth']; ?>" required>

                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" required>

                <button type="button" onclick="showConfirmationPopup()">Submit</button>
                <a href="index.php" class="back-home">Back to Home</a>
            </form>
        </div>

        <!-- Confirmation Popup -->
        <div id="confirmationPopup" class="popup">
            <div class="popup-content">
                <p>Are you sure you want to save the changes?</p>
                <button onclick="submitForm()">Yes</button>
                <button onclick="closePopup()">Cancel</button>
            </div>
        </div>

        <script>
            document.getElementById('newProfileImage').addEventListener('change', function(event) {
                const reader = new FileReader();
                reader.onload = function() {
                    document.getElementById('profileImagePreview').src = reader.result;
                };
                reader.readAsDataURL(event.target.files[0]);
            });

            function showConfirmationPopup() {
                document.getElementById('confirmationPopup').style.display = 'flex';
            }

            function closePopup() {
                document.getElementById('confirmationPopup').style.display = 'none';
            }

            function submitForm() {
                document.getElementById('editProfileForm').submit();
            }
        </script>
    </body>
</html>

<?php
    // Handle form submission if POST request
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $date_of_birth = $_POST['date_of_birth'];
        $confirm_password = $_POST['confirm_password'];

        // Check if entered password matches user's current password
        if ($confirm_password !== $user['password']) {
            echo "<script>alert('Incorrect password!'); window.location.href = 'dashboard.php';</script>";
            exit();
        }

        // Image handling
        if (isset($_FILES['new_profile_image']) && $_FILES['new_profile_image']['name']) {
            // Delete old image
            $old_image_path = "http://localhost/E-Commerce-Website/images/user/" . $user['profile_image'];
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }

            // Upload new image
            $new_image_name = basename($_FILES['new_profile_image']['name']);
            $new_image_path = "localhost/E-Commerce-Website/images/user/" . $new_image_name;
            move_uploaded_file($_FILES['new_profile_image']['tmp_name'], $new_image_path);
        } else {
            $new_image_name = $user['profile_image'];
        }

        // Update user details in the database
        $update_query = $conn->prepare("UPDATE users SET name=?, mobile=?, email=?, address=?, date_of_birth=?, profile_image=? WHERE user_id=?");
        $update_query->bind_param("ssssssi", $name, $mobile, $email, $address, $date_of_birth, $new_image_name, $user_id);

        if ($update_query->execute()) {
            echo "<script>alert('Profile updated successfully!'); window.location.href = 'dashboard.php';</script>";
        } else {
            echo "<script>alert('Error updating profile!'); window.location.href = 'dashboard.php';</script>";
        }
    }
?>
