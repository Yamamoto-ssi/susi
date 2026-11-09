<?php
session_start();
include 'config/db.php';

// Fix 1: Consistent role-based redirection for already logged-in users
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'Administrator') {
        header("Location: admin/admin_dashboard.php");
    } elseif ($_SESSION['role'] === 'Staff') {
        header("Location: staff/staff_dashboard.php");
    } elseif ($_SESSION['role'] === 'Buyer' || $_SESSION['role'] === 'Client') {
        header("Location: home.php");
    }
}

$error = '';
$success = '';

if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = "Registration successful! Please check your email to verify your account.";
} elseif (isset($_GET['verified']) && $_GET['verified'] == 1) {
    $success = "Email verified successfully! You can now log in.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please fill in all required fields.";
    } else {
        // Fix 2: Upgraded to Secure Prepared Statements
        $stmt = mysqli_prepare($conn, "SELECT id, username, email, password, role, is_verified FROM users WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            // Check if email is verified
            if ($user['is_verified'] == 0) {
                $error = "Please verify your email address before logging in. Check your inbox for the verification link.";
            } elseif (password_verify($password, $user['password'])) {
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'Administrator') {
                    header("Location: admin/admin_dashboard.php");
                } elseif ($user['role'] === 'Staff') {
                    header("Location: staff/staff_dashboard.php");
                } elseif ($user['role'] === 'Buyer' || $user['role'] === 'Client') {
                    header("Location: home.php");
                } else {
                    header("Location: logout.php");
                }
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
        mysqli_stmt_close($stmt);
    }
}

// Google Client Configuration (Replace with your credentials)
$client_id = '44077547718-4nesakkabijeqadjsse1fq82knq0rm1n.apps.googleusercontent.com';
$client_secret = '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';
$redirect_uri = 'http://localhost/HouseAndLotApp/google-callback.php';
$google_login_url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
    'client_id' => $client_id,
    'redirect_uri' => $redirect_uri,
    'response_type' => 'code',
    'scope' => 'email profile',
    'access_type' => 'offline'
]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ardor Property Group - Login</title>
  <!-- Feather Icons CDN -->
  <script src="https://unpkg.com/feather-icons"></script>

  <!-- External CSS -->
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

/* Login Wrapper Layout */
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

/* Left Hero Branding Section */
.login-banner {
  flex: 1;
  background-color: var(--bg-sidebar);
  color: #ffffff;
  padding: 48px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  position: relative;
}

.brand {
  display: flex;
  align-items: center;
  gap: 12px;
}

.logo {
  border: 1px solid rgba(255, 255, 255, 0.2);
  padding: 8px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.brand-text h2 {
  font-size: 20px;
  font-weight: 600;
  line-height: 1.2;
}

.brand-text span {
  font-size: 10px;
  letter-spacing: 1.5px;
  color: var(--text-light);
}

.banner-content h1 {
  font-size: 32px;
  font-weight: 700;
  line-height: 1.3;
  margin-bottom: 16px;
}

.banner-content p {
  color: var(--text-light);
  font-size: 14px;
  line-height: 1.6;
}

.banner-footer {
  font-size: 12px;
  color: var(--text-light);
}

/* Right Form Section */
.login-form-area {
  flex: 1;
  padding: 48px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.form-header {
  margin-bottom: 24px;
}

.form-header h2 {
  font-size: 24px;
  color: var(--primary);
  margin-bottom: 6px;
}

.form-header p {
  font-size: 14px;
  color: var(--text-muted);
}

/* Google Sign-In Button */
.btn-google {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  background-color: #ffffff;
  border: 1px solid var(--border-color);
  padding: 10px 16px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  color: var(--text-main);
  cursor: pointer;
  transition: background-color 0.2s, border-color 0.2s;
  margin-bottom: 20px;
}

.btn-google:hover {
  background-color: #f9fafb;
  border-color: #d0d5dd;
}

.google-icon {
  width: 18px;
  height: 18px;
}

/* Divider Line */
.divider {
  display: flex;
  align-items: center;
  text-align: center;
  margin-bottom: 20px;
  color: var(--text-light);
  font-size: 12px;
}

.divider::before,
.divider::after {
  content: '';
  flex: 1;
  border-bottom: 1px solid var(--border-color);
}

.divider span {
  padding: 0 10px;
}

/* Form Fields */
.form-group {
  margin-bottom: 16px;
}

.form-group label {
  display: block;
  font-size: 13px;
  font-weight: 500;
  color: var(--text-main);
  margin-bottom: 6px;
}

.input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.input-wrapper input {
  width: 100%;
  padding: 10px 12px 10px 38px;
  border: 1px solid var(--border-color);
  border-radius: 8px;
  font-size: 14px;
  outline: none;
  transition: border-color 0.2s;
}

.input-wrapper input:focus {
  border-color: var(--primary);
}

.input-icon {
  position: absolute;
  left: 12px;
  width: 18px;
  height: 18px;
  color: var(--text-light);
}

.form-options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 13px;
  margin-bottom: 24px;
}

.remember-me {
  display: flex;
  align-items: center;
  gap: 8px;
  color: var(--text-muted);
  cursor: pointer;
}

.forgot-password {
  color: var(--accent-gold);
  text-decoration: none;
  font-weight: 500;
}

.forgot-password:hover {
  text-decoration: underline;
}

/* Primary Button */
.btn-primary {
  width: 100%;
  background-color: var(--primary);
  color: #ffffff;
  border: none;
  padding: 12px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
}

.btn-primary:hover {
  background-color: var(--bg-sidebar-hover);
}

.form-footer {
  text-align: center;
  margin-top: 24px;
  font-size: 13px;
  color: var(--text-muted);
}

.form-footer a {
  color: var(--accent-gold);
  text-decoration: none;
  font-weight: 600;
}

.form-footer a:hover {
  text-decoration: underline;
}
  </style>
</head>
<body>

  <div class="login-container">
    <!-- Left Hero Branding Section -->
    <div class="login-banner">
      <div class="brand">
        <div class="logo">
          <i data-feather="home"></i>
        </div>
        <div class="brand-text">
          <h2>Ardor</h2>
          <span>PROPERTY GROUP</span>
        </div>
      </div>

      <div class="banner-content">
        <h1>Manage your real estate portfolio effortlessly.</h1>
        <p>Access real-time bidding updates, pending approvals, and comprehensive reports across all properties.</p>
      </div>

      <div class="banner-footer">
        &copy; <?php echo date("Y"); ?> Ardor Property Group. All rights reserved.
      </div>
    </div>

    <!-- Right Login Form Section -->
    <div class="login-form-area">
      <div class="form-header">
        <?php if (!empty($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fa-solid fa-circle-check me-2"></i><?php echo htmlspecialchars($success); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fa-solid fa-circle-exclamation me-2"></i><?php echo htmlspecialchars($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
        <h2>Welcome back</h2>
        <p>Please enter your details to sign in</p>
      </div>

      <!-- Google Sign-In Button -->
       <a href="<?php echo $google_login_url; ?>" style="text-decoration: none;" class="btn btn-outline-dark w-100 py-2 shadow-sm d-flex align-items-center justify-content-center gap-2">
      <button type="button" class="btn-google">
        <svg class="google-icon" viewBox="0 0 24 24">
          <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/>
          <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.29v3.15C3.26 21.3 7.31 24 12 24z"/>
          <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.29C.47 8.21 0 10.05 0 12s.47 3.79 1.29 5.42l3.99-3.15z"/>
          <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.31 0 3.26 2.7 1.29 6.58l3.99 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
        </svg>
        
                            <i class="fa-brands fa-google text-danger"></i> Sign In with Google
                        
      </button></a>

      <div class="divider">
        <span>or sign in with email</span>
      </div>

      <!-- Main Login Form -->
      <form action="index.php" method="POST">
        <div class="form-group">
          <label for="email">Email address</label>
          <div class="input-wrapper">
            <i data-feather="mail" class="input-icon"></i>
            <input type="email" id="email" name="email" placeholder="name@company.com" required>
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrapper">
            <i data-feather="lock" class="input-icon"></i>
            <input type="password" id="password" name="password" placeholder="••••••••" required>
          </div>
        </div>

        <div class="form-options">
          <label class="remember-me">
            <input type="checkbox" name="remember">
            <span>Remember for 30 days</span>
          </label>
          <a href="forgot-password.php" class="forgot-password">Forgot password?</a>
        </div>

        <button type="submit" class="btn-primary">Sign in</button>
      </form>

      <div class="form-footer">
        Don't have an account? <a href="#">Sign up</a>
      </div>
    </div>
  </div>

  <script>
    feather.replace();
  </script>
</body>
</html>