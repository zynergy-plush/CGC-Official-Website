<?php
require 'config.php';
require 'includes/auth.php';

$stmt = $pdo->query("
    SELECT *
    FROM news
    WHERE is_visible = 1
    ORDER BY created_at DESC
");

$news = $stmt->fetchAll(PDO::FETCH_ASSOC);

$latestNews = null;
$olderNews = [];

if(!empty($news)){
    $latestNews = array_shift($news);
    $olderNews = $news;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News | CGC</title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style-global.css">
    <link rel="stylesheet" href="css/style-news.css">
    <link rel="shortcut icon" href="images/Main Logo Circular.png" type="image/x-icon">
</head>

<body>

    <?php require 'includes/background.php'; ?>

    <?php if(isAdmin()): ?>
        <?php require 'includes/header_loggedin.php'; ?>
    <?php else: ?>
        <?php require 'includes/header.php'; ?>
    <?php endif; ?>

    <main class="view-news">

        <section class="featured-news">

            <?php if($latestNews): ?>

            <div class="featured-card">

                <div class="featured-media">

                    <?php if($latestNews["media_type"]=="image"): ?>

                        <img
                            src="uploads/news/<?= htmlspecialchars($latestNews["media"]) ?>"
                            alt="<?= htmlspecialchars($latestNews["title"]) ?>">

                    <?php else: ?>

                        <video autoplay muted loop playsinline>
                            <source
                                src="uploads/news/<?= htmlspecialchars($latestNews["media"]) ?>"
                                type="video/mp4">
                        </video>

                    <?php endif; ?>

                </div>

                <div class="featured-content">

                    <span class="category-badge">
                        Latest News
                    </span>

                    <h2><?= htmlspecialchars($latestNews["title"]) ?></h2>

                    <div class="news-date">
                        Published: <?= htmlspecialchars($latestNews["created_at"]) ?>
                    </div>

                    <p><?= htmlspecialchars($latestNews["summary"]) ?></p>

                    <div class="btn-box">
                        <a href="detail-news.php?id=<?= urlencode($latestNews["id"]) ?>">
                            Read More
                        </a>
                    </div>

                </div>

            </div>

            <?php else: ?>

            <div class="featured-card no-news">

                <h2>No News Available Yet</h2>

            </div>

            <?php endif; ?>

        </section>

        <section class="news-search">

            <div class="search-box">

                <i class='bx bx-search'></i>

                <input
                    type="text"
                    id="newsSearch"
                    placeholder="Search News...">

            </div>

        </section>

        <section class="older-news" id="newsGrid">

            <?php foreach($olderNews as $item): ?>

            <div
                class="news-card"
                data-title="<?= strtolower(htmlspecialchars($item["title"])) ?>"
                data-summary="<?= strtolower(htmlspecialchars($item["summary"])) ?>"
            >

                <?php if($item["media_type"]=="image"): ?>

                    <img
                        src="uploads/news/<?= htmlspecialchars($item["media"]) ?>"
                        alt="<?= htmlspecialchars($item["title"]) ?>">

                <?php else: ?>

                    <video autoplay muted loop playsinline>
                        <source
                            src="uploads/news/<?= htmlspecialchars($item["media"]) ?>"
                            type="video/mp4">
                    </video>

                <?php endif; ?>

                <div class="news-card-content">

                    <h3><?= htmlspecialchars($item["title"]) ?></h3>

                    <div class="news-date">
                        <?= htmlspecialchars($item["created_at"]) ?>
                    </div>

                    <p><?= htmlspecialchars($item["summary"]) ?></p>

                    <div class="btn-box">
                        <a href="detail-news.php?id=<?= urlencode($item["id"]) ?>">
                            Read More
                        </a>
                    </div>

                </div>

            </div>

            <?php endforeach; ?>

        </section>

    </main>

<script src="js/script-global.js"></script>
<script src="js/script-news.js"></script>

</body>
</html>