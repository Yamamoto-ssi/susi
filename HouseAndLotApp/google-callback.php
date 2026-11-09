<?php
session_start();
include 'config/db.php';

$client_id = '44077547718-4nesakkabijeqadjsse1fq82knq0rm1n.apps.googleusercontent.com';
$client_secret = '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';
$redirect_uri = 'http://localhost/HouseAndLotApp/google-callback.php';

// 1. Check if Google returned an explicit error
if (isset($_GET['error'])) {
    echo "<h3>Google OAuth Error:</h3>";
    echo "<p style='color: red; font-weight: bold;'>" . htmlspecialchars($_GET['error']) . "</p>";
    echo "<p>Check your Google Cloud Console settings (such as Authorized Redirect URIs).</p>";
    exit;
}

// 2. Check if the code exists
if (isset($_GET['code'])) {
    // Exchange authorization code for access token
    $token_url = 'https://oauth2.googleapis.com/token';
    $post_data = [
        'code' => $_GET['code'],
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => $redirect_uri,
        'grant_type' => 'authorization_code'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $token_data = json_decode($response, true);

    if (isset($token_data['access_token'])) {
        // Get user info from Google
        $user_info_url = 'https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . $token_data['access_token'];
        $user_info = json_decode(file_get_contents($user_info_url), true);

        $google_id = $user_info['id'];
        $email = $user_info['email'];
        $username = $user_info['name'] ?? explode('@', $email)[0];

        $safe_email = mysqli_real_escape_string($conn, $email);
        $sql = "SELECT * FROM users WHERE email = '$safe_email' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Link Google ID and mark verified if user exists
            mysqli_query($conn, "UPDATE users SET google_id = '$google_id', is_verified = 1 WHERE id = " . $user['id']);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($_SESSION['role'] === 'Buyer') {
                header("Location: home.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;
        } else {
            // Save details in session and redirect to profile completion for new users
            $_SESSION['google_signup'] = [
                'google_id' => $google_id,
                'email' => $email,
                'username' => $username
            ];

            header("Location: complete-registration.php");
            exit;
        }
    } else {
        echo "<h3>Token Exchange Failed:</h3>";
        echo "<pre>" . htmlspecialchars($response) . "</pre>";
        exit;
    }
} else {
    echo "<h3>No Authorization Code Found</h3>";
    echo "<p>The URL did not contain a 'code' parameter or a Google error. Current GET parameters:</p>";
    echo "<pre>";
    print_r($_GET);
    echo "</pre>";
    exit;
}
?>