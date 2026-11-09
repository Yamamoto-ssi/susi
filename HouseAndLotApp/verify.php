<?php
include 'config/db.php';

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);

    $sql = "SELECT id FROM users WHERE verification_token = '$token' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $user_id = $user['id'];

        // Update user to verified and clear the token
        $update_sql = "UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = $user_id";
        if (mysqli_query($conn, $update_sql)) {
            header("Location: login.php?verified=1");
            exit;
        }
    }
}

// Invalid token handling
echo "Invalid or expired verification link.";
?>