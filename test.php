<?php
// Example password
$password = '$2y$10$wFZgPF3Ovo/FQSl/HHAqNuWcbrSVbP/8Vals/w7ooZ85.tHu5c93y';


// Simulate user input for password verification
$userInputPassword = "12345"; // Change this to test different inputs

// Verify the user input against the hashed password
if (password_verify($userInputPassword, $password)) {
    echo "Password is valid!";
} else {
    echo "Invalid password.";
}

?>