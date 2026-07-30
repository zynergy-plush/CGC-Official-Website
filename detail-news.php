<?php
require 'config.php';
require 'includes/auth.php';

$stmt = $pdo->prepare("
    SELECT
        id,
        title,
        summary,
        details,
        media,
        media_type,
        created_at
    FROM news
    WHERE id = ?
    AND is_visible = 1
");

$stmt->execute([$_GET["id"]]);

$news = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$news){
    header("Location: news.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= htmlspecialchars($news["title"]) ?> | News | CGC
    </title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style-global.css">
    <link rel="stylesheet" href="css/style-detail-news.css">
    <link rel="shortcut icon" href="images/Main Logo Circular.png" type="image/x-icon">
</head>

<body>

    <?php require 'includes/background.php'; ?>

    <?php if(isAdmin()): ?>
        <?php require 'includes/header_locked.php'; ?>
    <?php else: ?>
        <?php require 'includes/header_locked.php'; ?>
    <?php endif; ?>

    <main class="news-detail-page">

        <article class="news-detail-card">

            <h1 class="news-title">
                <?= htmlspecialchars($news["title"]) ?>
            </h1>

            <div class="news-meta">
                Published: <?= htmlspecialchars($news["created_at"]) ?>
            </div>

            <div class="news-media">

                <?php if($news["media_type"] === "image"): ?>

                    <img
                        src="uploads/news/<?= htmlspecialchars($news["media"]) ?>"
                        alt="<?= htmlspecialchars($news["title"]) ?>">

                <?php else: ?>

                    <video controls autoplay>

                        <source
                            src="uploads/news/<?= htmlspecialchars($news["media"]) ?>"
                            type="video/mp4">

                    </video>

                <?php endif; ?>

            </div>

            <h3 class="summary-heading">
                <?= htmlspecialchars($news["summary"]) ?>
            </h3>

            <div class="news-body">
                <?= nl2br(htmlspecialchars($news["details"])) ?>
            </div>

            <div class="btn-box">

                <a href="news.php">

                    ← Back to News

                </a>

            </div>

        </article>

    </main>

    <script src="js/script-global.js"></script>

</body>
</html>