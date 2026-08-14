<?php
require 'config.php';
require 'includes/auth.php';

$topProjectsStmt = $pdo->prepare("
    SELECT
        id,
        title,
        summary,
        details,
        media,
        media_type,
        category
    FROM projects
    WHERE is_top_project = 1
    AND is_visible = 1
    ORDER BY created_at DESC
");

$topProjectsStmt->execute();

$topProjects = $topProjectsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | CGC</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style-home.css">
    <link rel="stylesheet" href="css/style-projects.css">
    <link rel="stylesheet" href="css/style-auth.css">
    <link rel="stylesheet" href="css/style-global.css">
    <link rel="shortcut icon" href="images/Main Logo Circular.png" type="image/x-icon">
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
/>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</head>
<body>

    <?php require 'includes/background.php'; ?>
    <?php if (isAdmin()): ?>
        <?php require 'includes/header_loggedin.php'; ?>
    <?php else: ?>
        <?php require 'includes/header.php'; ?>
    <?php endif; ?>
    <!-- MAIN PART -->
    <div id="cgc-react-home">

    </div>



    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="js/script-home.js"></script>
    <script src="js/script-global.js"></script>
    <script type="module">
        import RefreshRuntime from 'http://localhost:5173/@react-refresh';

        RefreshRuntime.injectIntoGlobalHook(window);

        window.$RefreshReg$ = () => {};
        window.$RefreshSig$ = () => (type) => type;
        window.__vite_plugin_react_preamble_installed__ = true;
    </script>
    <script>
        window.topProjects = <?= json_encode($topProjects) ?>;
    </script>
    <script type="module" src="http://localhost:5173/src/main.jsx"></script>
</body>
</html></html>