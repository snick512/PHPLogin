<?php
require 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $pdo = connect_db();

    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND token_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE id = ?");
            if ($stmt->execute([$new_password, $user['id']])) {
                echo "Password reset successfully!";
            }
        }
    } else {
        echo "Invalid or expired token!";
    }
}
?>

<form method="POST">
    <input type="password" name="password" placeholder="Enter new password" required>
    <button type="submit">Reset Password</button>
</form>
