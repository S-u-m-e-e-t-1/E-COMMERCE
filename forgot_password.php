<?php
    session_start();
    require 'smtp/smtp/PHPMailerAutoload.php'; 

    include('./includes/connection.php');
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $action = $_POST['action'];

        if ($action == 'send_otp') {
            $method = $_POST['method'];
            $value = $_POST['value'];
            $otp = rand(1000, 9999);
            $_SESSION['otp'] = $otp;
            $_SESSION['method'] = $method;
            $_SESSION['value'] = $value;

            if ($method == 'phone') {
                // Check if phone number exists
                $stmt = $conn->prepare("SELECT user_id FROM user WHERE mobile = ?");
                $stmt->bind_param("s", $value);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    // Normally, you would send the OTP via SMS here
                    echo json_encode(['status' => 'success', 'otp' => $otp]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Phone number not found']);
                }
            } elseif ($method == 'email') {
                // Check if email exists
                $stmt = $conn->prepare("SELECT user_id FROM user WHERE email = ?");
                $stmt->bind_param("s", $value);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    // Send OTP via email using PHPMailer
                    $subject = "Your OTP Code for Password Reset";
                    $msg = "Your OTP code is: $otp";
                    $mailStatus = smtp_mailer($value, $subject, $msg); // Call the smtp_mailer function

                    if ($mailStatus === 'Sent') {
                        echo json_encode(['status' => 'success']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to send email']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Email not found']);
                }
            }
            exit();
        }

        if ($action == 'verify_otp') {
            $enteredOtp = $_POST['otp'];
            $storedOtp = $_SESSION['otp'] ?? '';

            if ($enteredOtp == $storedOtp) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid OTP']);
            }
            exit();
        }

        if ($action == 'reset_password') {
            $newPassword = $_POST['newPassword'];
            $method = $_SESSION['method'] ?? '';
            $value = $_SESSION['value'] ?? '';

            if ($method && $value && $newPassword) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                if ($method == 'phone') {
                    $stmt = $conn->prepare("UPDATE user SET password = ? WHERE mobile = ?");
                    $stmt->bind_param("ss", $hashedPassword, $value);
                } elseif ($method == 'email') {
                    $stmt = $conn->prepare("UPDATE user SET password = ? WHERE email = ?");
                    $stmt->bind_param("ss", $hashedPassword, $value);
                }

                if ($stmt->execute()) {
                    unset($_SESSION['otp'], $_SESSION['method'], $_SESSION['value']);
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to reset password']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
            }
            exit();
        }
    }

    function smtp_mailer($to, $subject, $msg) {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        //$mail->SMTPDebug = 2;

        $mail->Username = "sumeetpanigrahy494@gmail.com";
        $mail->Password = "wlsl gnsq sbbw tldz"; 
        $mail->SetFrom("sumeetpanigrahy494@gmail.com", "Ecommerce Service"); 
        
        $mail->Subject = $subject;
        $mail->Body = $msg;
        $mail->AddAddress($to);
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => false
            )
        );
        
        if (!$mail->send()) {
            return $mail->ErrorInfo; // Display error info if the email fails to send
        } else {
            return 'Sent'; // Return 'Sent' if successful
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Forgot Password</title>
        <link rel="stylesheet" href="./css/forgot_password.css">
    </head>
    <body>
        <div class="container">
            <div class="card" id="verificationChoice">
                <h2>Forgot Password</h2>
                <p>Choose verification method:</p>
                <button onclick="chooseVerification('phone')">Verify by Phone</button>
                <button onclick="chooseVerification('email')">Verify by Email</button>
            </div>

            <div class="card" id="phoneForm" style="display: none;">
                <h2>Phone Verification</h2>
                <label for="phone">Enter Phone Number:</label>
                <input type="text" id="phone" required>
                <button onclick="sendOtp('phone')">Send OTP</button>
            </div>

            <div class="card" id="emailForm" style="display: none;">
                <h2>Email Verification</h2>
                <label for="email">Enter Email:</label>
                <input type="email" id="email" required>
                <button onclick="sendOtp('email')">Send OTP</button>
            </div>

            <div class="card" id="otpForm" style="display: none;">
                <h2>Verify OTP</h2>
                <label for="otp">Enter OTP:</label>
                <input type="text" id="otp" required>
                <button onclick="verifyOtp()">Verify OTP</button>
            </div>

            <div class="card" id="resetPasswordForm" style="display: none;">
                <h3>Reset Your Password</h3>
                <label for="newPassword">Enter New Password:</label>
                <input type="password" id="newPassword" required>
                <button onclick="resetPassword()">Reset Password</button>
            </div>

            <div class="card" id="errorMessage" style="display: none;">
                <p>Something went wrong. Please try again later.</p>
            </div>
        </div>

        <script>
            function chooseVerification(method) {
                document.getElementById('verificationChoice').style.display = 'none';
                document.getElementById(method === 'phone' ? 'phoneForm' : 'emailForm').style.display = 'block';
            }

            function sendOtp(method) {
                const value = document.getElementById(method).value;
                fetch('forgot_password.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ action: 'send_otp', method, value })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(method === 'phone' ? `OTP sent to phone: ${data.otp}` : "OTP sent to your email.");
                        document.getElementById(method + 'Form').style.display = 'none';
                        document.getElementById('otpForm').style.display = 'block';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => alert("An error occurred. Please try again."));
            }

            function verifyOtp() {
                const enteredOtp = document.getElementById('otp').value;
                fetch('forgot_password.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ action: 'verify_otp', otp: enteredOtp })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('otpForm').style.display = 'none';
                        document.getElementById('resetPasswordForm').style.display = 'block';
                    } else {
                        alert(data.message);
                    }
                });
            }

            function resetPassword() {
                const newPassword = document.getElementById('newPassword').value;
                if (newPassword.length < 6) {
                    alert("Password must be at least 6 characters long.");
                    return;
                }
                fetch('forgot_password.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ action: 'reset_password', newPassword })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert("Password reset successfully!");
                        window.location.href = 'index.php';
                    } else {
                        alert(data.message);
                    }
                });
            }
        </script>
    </body>
</html>
