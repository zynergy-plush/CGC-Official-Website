<?php
http_response_code(404);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 | CGC</title>
    <link rel="shortcut icon" href="images/Main Logo Circular.png" type="image/x-icon">
    <link rel="stylesheet" href="css/style-global.css">
    <link rel="stylesheet" href="css/style-404.css">

    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
</head>

<body>

<?php require 'includes/background.php'; ?>

<div class="error-code">404</div>

<div class="error-page">
    <h1>404</h1>
    <h2>Page Not Found</h2>

    <p>
        The page you're looking for doesn't exist, has been moved,
        or the URL is incorrect.
    </p>

    <a href="home.php" class="btn">
        <i class="bx bx-arrow-back"></i>
        Return Home
    </a>
</div>

<script src="js/script-404.js"></script>
</body>
</html>