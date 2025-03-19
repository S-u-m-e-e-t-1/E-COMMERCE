<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loginSuccess = false; // New flag to track login success

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check user
    $sql = "SELECT * FROM admin WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $row['name'];
        $_SESSION['image'] = $row['image'];  // store image path
        $_SESSION['id']= $row['id'];
        $loginSuccess = true;  // Set success flag
    } else {
        $loginSuccess = false; // Login failed
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your styles in a separate file -->
    <style>
        /* Basic styling for the login form */
        .popup {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
        }
        .popup h2 {
            text-align: center;
        }
        .popup input[type="email"], .popup input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .popup button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .popup button:hover {
            background-color: #218838;
        }
        /* Success and Failure Popups */
        #success-pop, #failure-pop {
            display: none;
            width: 300px;
            text-align: center;
            padding: 20px;
        }
        #success-pop .icon, #failure-pop .icon {
            font-size: 50px;
            margin-bottom: 20px;
        }
        #success-pop .icon {
            color: #28a745;
            animation: rotateTick 1s ease-in-out infinite;
        }
        #failure-pop .icon {
            color: #dc3545;
        }
        @keyframes rotateTick {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<!-- Login Popup -->
<div class="popup" id="login-popup">
    <h2>Login</h2>
    <form action="index.php" method="POST" onsubmit="return validateForm()">
        <input type="email" name="email" placeholder="Enter Email" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit">Login</button>
    </form>
</div>

<!-- Success Popup -->
<div class="popup" id="success-pop">
    <div class="icon">✔</div>
    <h2>Login Successful!</h2>
</div>

<!-- Failure Popup -->
<div class="popup" id="failure-pop">
    <div class="icon">☹</div>
    <h2>Login Failed!</h2>
</div>

<script>
// Show login popup when the page loads
window.onload = function() {
    document.getElementById('login-popup').style.display = 'block';
};

// Validate form
function validateForm() {
    var email = document.querySelector('[name="email"]').value;
    var password = document.querySelector('[name="password"]').value;

    if (email == "" || password == "") {
        alert("Please fill in all fields.");
        return false;
    }
    return true;
}

<?php if ($loginSuccess): ?>
    // Success popup and redirect
    document.getElementById('success-pop').style.display = 'block';
    setTimeout(function() {
        window.location.href = 'admin-panel.php'; // Redirect to admin panel
    }, 2000);
<?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
    // Failure popup
    document.getElementById('failure-pop').style.display = 'block';
<?php endif; ?>

</script>

</body>
</html>
