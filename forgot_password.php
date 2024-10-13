<?php
require 'db.php';
require 'send_email.php';  // This will contain SMTP email sending logic

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $pdo = connect_db();

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?");
        if ($stmt->execute([$token, $expiry, $email])) {
            $reset_link = "http://example.com/reset_password.php?token=$token";
            send_email($email, "Password Reset", "Click here to reset your password: $reset_link");
            echo "Reset link sent!";
        }
    } else {
        echo "No user found with that email!";
    }
}
?>

<form method="POST">
    <input type="email" name="email" placeholder="Enter your email" required>
    <button type="submit">Send Reset Link</button>
</form>
