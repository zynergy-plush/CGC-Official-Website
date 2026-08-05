<?php
require 'config.php';
require 'includes/auth.php';

$stmt = $pdo->query("
    SELECT *
    FROM challenges
    WHERE is_visible = 1
    ORDER BY created_at DESC
");

$challenges = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenges | CGC</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style-global.css">
    <link rel="stylesheet" href="css/style-challenges.css">
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

    <div class="profile-layout">

        <!-- Sidebar -->
        <aside class="profile-sidebar">

            <div class="profile-menu">

                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="bx bx-code"></i>
                    </div>
                    <div class="profile-info">
                        <h3>Challenges</h3>
                    </div>
                </div>
                &nbsp;

                <button type="button" class="tab-btn" data-target="coding_challenges">
                    <i class="bx bx-code-alt"></i>
                    <span>Challenges</span>
                </button>

                <button type="button" class="tab-btn" data-target="leaderboard">
                    <i class="bx bx-trophy"></i>
                    <span>Leaderboard</span>
                </button>
                
                <!-- <button type="button" class="tab-btn" data-target="competitive_programming">
                    <i class="bx bx-bar-chart"></i>
                    <span>Competitive Programming</span>
                </button> -->
        
            </div>

        </aside>
        <!-- Main Content -->
        <main class="profile-content">

            <section id="dashboard" class="profile-section active">

                <div class="dashboard-home">

                    <div class="dashboard-icon">
                        <i class="bx bx-code"></i>
                    </div>

                    <h2>Challenges</h2>

                    <p>Select an option from the sidebar to get started.</p>

                </div>

            </section>

           <section id="coding_challenges" class="profile-section">
                <?php if (!empty($challenges)): ?>

                <div class="projects-carousel">

                    <div class="swiper challengeSwiper">

                        <div class="swiper-wrapper">

                            <?php foreach ($challenges as $challenge): ?>

                            <div class="swiper-slide">

                                <div class="challenge-card">

                                    <img
                                        src="uploads/challenges/<?= htmlspecialchars($challenge['image']) ?>"
                                        alt="<?= htmlspecialchars($challenge['title']) ?>">

                                    <div class="challenge-content">

                                        <h2>
                                            <?= htmlspecialchars($challenge['title']) ?>
                                        </h2>

                                         <div class="challenge-meta">

                                            <b><span class="tags">
                                                <?= htmlspecialchars($challenge['tags']) ?>
                                            </span></b>

                                        </div>

                                        <p class="challenge-desc">
                                            <?= nl2br(htmlspecialchars($challenge['description'])) ?>
                                        </p>

                                       
                                        <div class="btn-box">

                                            <a href="challenge.php?id=<?= $challenge['id'] ?>">
                                                Start Challenge
                                            </a>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <?php endforeach; ?>

                        </div>

                    </div>

                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>


                </div>

                <?php else: ?>

                <div class="no-projects">

                    <p>No challenges available yet.</p>

                </div>

                <?php endif; ?>

            </section>

            <section id="leaderboard" class="profile-section">

                <h2>Leaderboard</h2>

                <p>
                    Content goes here.
                </p>

            </section>

            <section id="competitive_programming" class="profile-section">

                <h2>Competitive Programming</h2>

                <p>
                    Content goes here.
                </p>

            </section>

        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="js/script-challenges.js"></script>
</body>
</html>