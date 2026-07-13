<?php
require 'config.php';
require 'includes/auth.php';


if($_SERVER["REQUEST_METHOD"] == "POST"){

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    $stmt = $pdo->prepare("
        INSERT INTO contact_messages
        (name,email,subject,message)
        VALUES (?,?,?,?)
    ");

    $stmt->execute([
        $name,
        $email,
        $subject,
        $message
    ]);

    header("Location: contact.php?success=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | CGC</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style-global.css">
    <link rel="stylesheet" href="css/style-contact.css">
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
    <?php

    if(isset($_GET["success"])){

        echo '
        <div class="success-box">
            Message sent successfully!
        </div>
        ';

    }

    ?>

    <section class="discord-section">

        <div class="discord-container">

            <div class="discord-video">
                <video autoplay muted loop playsinline>
                    <source src="images/discord.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>

            <div class="discord-content">

                <h2>Join Our Discord Server</h2>

                <p>
                    Stay updated with club announcements, participate in discussions,
                    showcase your work, receive coding help, and collaborate with
                    fellow members.
                </p>

                <div class="btn-box">
                    <a href="https://discord.gg/YOURINVITE" target="_blank">
                        <i class='bx bxl-discord-alt'></i>
                        Join Now
                    </a>
                </div>

            </div>

        </div>

    </section>

    <section class="contact-section">

        <div class="contact-container">

            <div class="contact-left">

                <span class="contact-tag">
                    Contact
                </span>

                <h1>Get in touch</h1>

                <p>
                    We'd love to hear from you. Feel free to reach out through the
                    contact form or any of the methods below.
                </p>

                <a href="mailto:codingclub@email.com" class="contact-card">

                    <div class="card-icon">
                        <i class='bx bx-envelope'></i>
                    </div>

                    <div class="card-text">
                        <span>Email us</span>
                        <strong>codingclub@email.com</strong>
                    </div>

                    <div class="card-arrow">
                        <i class='bx bx-right-arrow-alt'></i>
                    </div>

                </a>

                <a href="https://instagram.com/glenrich.cgc" target="_blank" class="contact-card">

                    <div class="card-icon">
                        <i class='bx bxl-instagram'></i>
                    </div>

                    <div class="card-text">
                        <span>Instagram</span>
                        <strong>@glenrich.cgc</strong>
                    </div>

                    <div class="card-arrow">
                        <i class='bx bx-right-arrow-alt'></i>
                    </div>

                </a>

            </div>

            <div class="contact-right">

                <form action="contact.php" method="POST">

                    <input
                        type="text"
                        name="name"
                        placeholder="Name"
                        required>

                    <input
                        type="email"
                        name="email"
                        placeholder="Email"
                        required>

                    <input
                        type="text"
                        name="subject"
                        placeholder="Subject"
                        required>

                    <textarea
                        name="message"
                        placeholder="Message"
                        required></textarea>

                    <button type="submit">
                        Submit
                    </button>

                </form>

            </div>

        </div>

    </section>


    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="js/script-global.js"></script>
</body>
</html>