<?php
session_start();
include 'config/db.php';

$error = '';
$success = '';
$valid_token = false;
$email = '';

// Check if token exists in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify token in the database and check expiration
    $stmt = mysqli_prepare($conn, "SELECT email, expires_at FROM password_resets WHERE token = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) == 1) {
        $reset_data = mysqli_fetch_assoc($result);
        $expires_at = $reset_data['expires_at'];

        // Check if token has expired
        if (strtotime($expires_at) > time()) {
            $valid_token = true;
            $email = $reset_data['email'];
        } else {
            $error = "This password reset link has expired. Please request a new one.";
        }
    } else {
        $error = "Invalid password reset link.";
    }
    mysqli_stmt_close($stmt);
} else {
    $error = "No password reset token provided.";
}

// Handle form submission for updating password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid_token) {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($password) || empty($confirm_password)) {
        $error = "Please fill in all required fields.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Hash the new password securely
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Update user's password
        $update_stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE email = ?");
        mysqli_stmt_bind_param($update_stmt, "ss", $hashed_password, $email);

        if (mysqli_stmt_execute($update_stmt)) {
            // Delete the used token so it cannot be reused
            $delete_stmt = mysqli_prepare($conn, "DELETE FROM password_resets WHERE email = ?");
            mysqli_stmt_bind_param($delete_stmt, "s", $email);
            mysqli_stmt_execute($delete_stmt);
            mysqli_stmt_close($delete_stmt);

            $success = "Your password has been successfully reset! You can now log in.";
            $valid_token = false; // Hide form after successful reset
        } else {
            $error = "Something went wrong. Please try again.";
        }
        mysqli_stmt_close($update_stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ardor Property Group - Reset Password</title>
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
    .form-group { margin-bottom: 16px; }
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
        <h1>Set your new password securely.</h1>
        <p>Choose a strong password to secure your real estate portal account.</p>
      </div>
      <div class="banner-footer">&copy; <?php echo date("Y"); ?> Ardor Property Group. All rights reserved.</div>
    </div>

    <div class="login-form-area">
      <div class="form-header">
        <h2>Reset password</h2>
        <p>Please enter your new password below</p>
      </div>

      <?php if (!empty($success)): ?>
          <div class="alert alert-success" role="alert">
              <?php echo htmlspecialchars($success); ?><br><br>
              <a href="index.php" style="color: #0f5132; font-weight: bold; text-decoration: underline;">Click here to log in</a>
          </div>
      <?php endif; ?>

      <?php if (!empty($error)): ?>
          <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <?php if ($valid_token): ?>
      <form action="reset-password.php?token=<?php echo htmlspecialchars($_GET['token']); ?>" method="POST">
        <div class="form-group">
          <label for="password">New Password</label>
          <div class="input-wrapper">
            <i data-feather="lock" class="input-icon"></i>
            <input type="password" id="password" name="password" placeholder="••••••••" required>
          </div>
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirm New Password</label>
          <div class="input-wrapper">
            <i data-feather="lock" class="input-icon"></i>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" required>
          </div>
        </div>

        <button type="submit" class="btn-primary">Update password</button>
      </form>
      <?php endif; ?>

      <div class="form-footer">
        <a href="index.php"><i data-feather="arrow-left" style="width: 14px; height: 14px; vertical-align: middle;"></i> Back to log in</a>
      </div>
    </div>
  </div>
  <script>feather.replace();</script>
</body>
</html>