<?php
require 'config.php';
require 'includes/auth.php';
?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Core Members | CGC</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style-global.css">
    <link rel="stylesheet" href="css/style-core.css">
    <link rel="shortcut icon" href="images/Main Logo Circular.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
</head>
<body>

    <?php require 'includes/background.php'; ?>
    <?php if (isAdmin()): ?>
        <?php require 'includes/header_loggedin.php'; ?>
    <?php else: ?>
        <?php require 'includes/header.php'; ?>
    <?php endif; ?>

    <section class="hero">
        <div class="hero-glass">
            <h1>Meet Our Core Members</h1>
            <p>
                Scroll down to meet the individuals who drive CGC forward.
            </p>
        </div>
    </section>

    <section class="members-section">
        <div id="membersGrid" class="members-grid"></div>
    </section>
    <section class="presidents-section">

        <h2 class="timeline-title">Former Presidents</h2>

        <div class="timeline-wrapper">

            <div id="timeline" class="timeline"></div>

        </div>

        <div id="presidentDetails" class="president-details">

            <img id="presidentImage" src="" alt="President">

            <div class="details-text">
                <h3 id="presidentName"></h3>
                <h4 id="presidentYear"></h4>
                <p id="presidentDescription"></p>
            </div>

        </div>

    </section>




    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/papaparse@5.4.1/papaparse.min.js"></script>
    <script src="js/script-global.js"></script>
    <script src="js/script-core.js"></script>
</body>
</html>