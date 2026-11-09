<?php
session_start();
include 'config/db.php';

// Include Composer's autoloader
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $error = "Please enter your email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Check if user exists in database
        $stmt = mysqli_prepare($conn, "SELECT id, username FROM users WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            // Generate a secure random token
            $token = bin2hex(random_bytes(32));
            $expiresAt = date("Y-m-d H:i:s", strtotime("+1 hour"));

            // Store token in password_resets table
            $insert_stmt = mysqli_prepare($conn, "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($insert_stmt, "sss", $email, $token, $expiresAt);
            
            if (mysqli_stmt_execute($insert_stmt)) {
                // Dynamically build the reset link
                $protocol = isset($_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                $host = $_SERVER['HTTP_HOST'];
                $reset_link = "$protocol://$host/HouseAndLotApp/reset-password.php?token=" . $token;

                // Send email using PHPMailer with Gmail SMTP
                $mail = new PHPMailer(true);

                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'brixsedillo@gmail.com'; // <-- REPLACE with your Gmail address
                    $mail->Password   = 'ekig aspn ztps eprq';         // <-- REPLACE with your 16-digit Google App Password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    // Recipients
                    $mail->setFrom('your-actual-gmail@gmail.com', 'Ardor Property Group');
                    $mail->addAddress($email, $user['username']);

                    // Content
                    $mail->isHTML(true);
$mail->Subject = 'Password Reset Request - Ardor Property Group';
$mail->Body    = "
    <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;'>
        <h2 style='color: #0f172a; margin-top: 0;'>Ardor Property Group</h2>
        <p>Hi <strong>" . htmlspecialchars($user['username']) . "</strong>,</p>
        <p>We received a request to reset your password. Click the button below to choose a new password:</p>
        
        <div style='text-align: center; margin: 30px 0;'>
            <a href='" . $reset_link . "' style='background-color: #0f172a; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: bold; font-size: 14px;'>Reset Password</a>
        </div>
        
        <p style='color: #64748b; font-size: 13px;'>This link will expire in 1 hour.</p>
        <p style='color: #64748b; font-size: 13px;'>If you didn't request this, you can safely ignore this email.</p>
    </div>";

$mail->AltBody = "Hi " . $user['username'] . ",\n\nWe received a request to reset your password. Open this link to reset it:\n" . $reset_link . "\n\nThis link will expire in 1 hour.";

                    $mail->send();
                    $success = "If an account with that email exists, we have sent a password reset link to your inbox.";
                } catch (Exception $e) {
                    $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                $error = "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($insert_stmt);
        } else {
            // Generic success message to prevent user enumeration security vulnerability
            $success = "If an account with that email exists, we have sent a password reset link to your inbox.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ardor Property Group - Forgot Password</title>
  <script src="https://unpkg.com/feather-icons"></script>
  <link rel="stylesheet" href="assets/css/styles.css">
  <style>
    body {
      font-family: var(--font-family);
      background-color: var(--bg-app);
      color: var(--text-main);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-container {
      display: flex;
      width: 100%;
      max-width: 1000px;
      min-height: 600px;
      background: var(--bg-card);
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
      overflow: hidden;
      margin: 20px;
      border: 1px solid var(--border-color);
    }
    .login-banner {
      flex: 1;
      background-color: var(--bg-sidebar);
      color: #ffffff;
      padding: 48px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .brand { display: flex; align-items: center; gap: 12px; }
    .logo { border: 1px solid rgba(255, 255, 255, 0.2); padding: 8px; border-radius: 8px; }
    .brand-text h2 { font-size: 20px; font-weight: 600; line-height: 1.2; }
    .brand-text span { font-size: 10px; letter-spacing: 1.5px; color: var(--text-light); }
    .banner-content h1 { font-size: 32px; font-weight: 700; line-height: 1.3; margin-bottom: 16px; }
    .banner-content p { color: var(--text-light); font-size: 14px; line-height: 1.6; }
    .banner-footer { font-size: 12px; color: var(--text-light); }
    .login-form-area { flex: 1; padding: 48px; display: flex; flex-direction: column; justify-content: center; }
    .form-header { margin-bottom: 24px; }
    .form-header h2 { font-size: 24px; color: var(--primary); margin-bottom: 6px; }
    .form-header p { font-size: 14px; color: var(--text-muted); }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 13px; font-weight: 500; color: var(--text-main); margin-bottom: 6px; }
    .input-wrapper { position: relative; display: flex; align-items: center; }
    .input-wrapper input { width: 100%; padding: 10px 12px 10px 38px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none; }
    .input-wrapper input:focus { border-color: var(--primary); }
    .input-icon { position: absolute; left: 12px; width: 18px; height: 18px; color: var(--text-light); }
    .btn-primary { width: 100%; background-color: var(--primary); color: #ffffff; border: none; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; }
    .btn-primary:hover { background-color: var(--bg-sidebar-hover); }
    .form-footer { text-align: center; margin-top: 24px; font-size: 13px; color: var(--text-muted); }
    .form-footer a { color: var(--accent-gold); text-decoration: none; font-weight: 600; }
    .alert { padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; }
    .alert-success { background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
    .alert-danger { background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-banner">
      <div class="brand">
        <div class="logo"><i data-feather="home"></i></div>
        <div class="brand-text">
          <h2>Ardor</h2>
          <span>PROPERTY GROUP</span>
        </div>
      </div>
      <div class="banner-content">
        <h1>Recover access to your account securely.</h1>
        <p>Enter your registered email address and we'll send you instructions to reset your password.</p>
      </div>
      <div class="banner-footer">&copy; <?php echo date("Y"); ?> Ardor Property Group. All rights reserved.</div>
    </div>

    <div class="login-form-area">
      <div class="form-header">
        <h2>Forgot password?</h2>
        <p>No worries, we'll send you reset instructions.</p>
      </div>

      <?php if (!empty($success)): ?>
          <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($success); ?></div>
      <?php endif; ?>

      <?php if (!empty($error)): ?>
          <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <form action="forgot-password.php" method="POST">
        <div class="form-group">
          <label for="email">Email address</label>
          <div class="input-wrapper">
            <i data-feather="mail" class="input-icon"></i>
            <input type="email" id="email" name="email" placeholder="name@company.com" required>
          </div>
        </div>
        <button type="submit" class="btn-primary">Reset password</button>
      </form>

      <div class="form-footer">
        <a href="index.php"><i data-feather="arrow-left" style="width: 14px; height: 14px; vertical-align: middle;"></i> Back to log in</a>
      </div>
    </div>
  </div>
  <script>feather.replace();</script>
</body>
</html>