<?php
session_start();
include 'config/db.php';

// Check if Google signup session exists
if (!isset($_SESSION['google_signup'])) {
    header("Location: login.php");
    exit;
}

$google_data = $_SESSION['google_signup'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = $google_data['email'];
    $google_id = $google_data['google_id'];
    $role = mysqli_real_escape_string($conn, $_POST['role']); // e.g., Buyer
    
    // Insert new user into database
    $insert_sql = "INSERT INTO users (username, email, google_id, role, is_verified) VALUES ('$username', '$email', '$google_id', '$role', 1)";
    
    if (mysqli_query($conn, $insert_sql)) {
        $_SESSION['user_id'] = mysqli_insert_id($conn);
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;

        // Clear temporary session data
        unset($_SESSION['google_signup']);

        // Redirect based on chosen role
        if ($role === 'Buyer') {
            header("Location: home.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    } else {
        $error = "Registration failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complete Registration</title>
</head>
<body>
    <h2>Complete Your Account</h2>
    <p>We found your Google account. Please confirm your details below to finish setting up.</p>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Email (Connected via Google):</label><br>
        <input type="email" value="<?php echo htmlspecialchars($google_data['email']); ?>" disabled><br><br>

        <label>Username:</label><br>
        <input type="text" name="username" value="<?php echo htmlspecialchars($google_data['username']); ?>" required><br><br>

        <label>Select Role:</label><br>
        <select name="role">
            <option value="Buyer">Buyer</option>
            <!-- Add other roles if applicable -->
        </select><br><br>

        <button type="submit">Complete Registration</button>
    </form>
</body>
</html>