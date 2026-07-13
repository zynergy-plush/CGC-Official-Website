<?php
require 'config.php';
require 'includes/auth.php';

if (isAdmin()) {
    header('Location: home.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password']) && $user['is_admin'] == 1) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = true;
        header('Location: home.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | CGC</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style-global.css">
    <link rel="stylesheet" href="css/style-auth.css">
    <link rel="shortcut icon" href="images/Main Logo Circular.png" type="image/x-icon">
</head>
<body>
    <?php require 'includes/background.php'; ?>
    <?php require 'includes/header_locked.php'; ?>
    <main class="auth-page">
        <div class="auth-card">
            <h2>Admin Login</h2>
            <?php if ($error): ?>
                <p class="auth-error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="post">
                <label>Username</label>
                <input type="text" name="username" required>
                <label>Password</label>
                <input type="password" name="password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </main>
    <script src="js/script-global.js"></script>
</body>
</html>