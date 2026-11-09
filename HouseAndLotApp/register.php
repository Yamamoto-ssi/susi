<?php
session_start();
include 'config/db.php'; // Uses your $conn connection

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['role'] === 'administrator' ? 'admin_dashboard.php' : 'dashboard.php'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = trim($_POST['role'] ?? 'buyer');

    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Hash the password securely
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Escape inputs for safe direct queries
        $safe_username = mysqli_real_escape_string($conn, $username);
        $safe_email = mysqli_real_escape_string($conn, $email);
        $safe_role = mysqli_real_escape_string($conn, $role);
        $safe_password = mysqli_real_escape_string($conn, $hashed_password);

        $sql = "INSERT INTO users (username, email, password, role) VALUES ('$safe_username', '$safe_email', '$safe_password', '$safe_role')";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: login.php?success=1");
            exit;
        } else {
            $error = "Registration failed: Username or email may already exist.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Mendoza Real Estate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fa-solid fa-house-chimney text-warning me-2"></i>Mendoza Real Estate
            </a>
            <div class="ms-auto">
                <a href="index.php" class="btn btn-outline-light btn-sm me-2">Home</a>
                <a href="login.php" class="btn btn-warning btn-sm text-dark fw-bold">Login</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-lg rounded-3">
                    <div class="card-body p-4 p-sm-5">
                        <div class="text-center mb-4">
                            <div class="text-warning fs-1"><i class="fa-solid fa-user-plus"></i></div>
                            <h2 class="fw-bold mt-2">Create Account</h2>
                            <p class="text-muted small">Join Mendoza Real Estate today</p>
                        </div>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fa-solid fa-circle-exclamation me-2"></i><?php echo htmlspecialchars($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form action="register.php" method="POST" novalidate>
                            <div class="mb-3">
                                <label for="username" class="form-label fw-semibold">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                    <input type="text" name="username" id="username" class="form-control" placeholder="johndoe" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="name@example.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="role" class="form-label fw-semibold">Register As</label>
                                <select name="role" id="role" class="form-select">
                                    <option value="buyer">Buyer</option>
                                    <option value="staff">Staff</option>
                                    <option value="administrator">Administrator</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-warning w-100 text-dark fw-bold py-2 shadow-sm">
                                <i class="fa-solid fa-user-check me-2"></i>Register
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="text-muted small mb-0">Already have an account? <a href="login.php" class="text-warning fw-bold text-decoration-none">Sign in</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>