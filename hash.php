<?php
$hash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_DEFAULT);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Hash Generator</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style-global.css">
    <link rel="stylesheet" href="css/style-auth.css">
</head>
<body>
    <?php require 'includes/background.php'; ?>
    <main class="auth-page">
        <div class="auth-card">
            <h2>Password Hash Generator</h2>
            <form method="post">
                <label>Password</label>
                <input type="text" name="password" required>
                <button type="submit">Generate Hash</button>
            </form>
            <?php if ($hash): ?>
                <label>Hash</label>
                <input type="text" value="<?php echo htmlspecialchars($hash); ?>" readonly onclick="this.select()">
            <?php endif; ?>
        </div>
    </main>
</body>
</html>