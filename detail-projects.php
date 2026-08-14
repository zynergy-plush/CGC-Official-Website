<?php
require 'config.php';
require 'includes/auth.php';

$stmt = $pdo->prepare("
    SELECT
        id,
        title,
        category,
        summary,
        details,
        media,
        media_type,
        created_at
    FROM projects
    WHERE id = ?
    AND is_visible = 1
");

$stmt->execute([$_GET["id"]]);

$project = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$project){
    header("Location: projects.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= htmlspecialchars($project["title"]) ?> | <?= ucwords(str_replace("_"," ",$project["category"])) ?> | CGC
    </title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style-global.css">
    <link rel="stylesheet" href="css/style-detail-projects.css">
    <link rel="shortcut icon" href="images/Main Logo Circular.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
</head>
<body>

    <?php require 'includes/background.php'; ?>
    <?php if (isAdmin()): ?>
        <?php require 'includes/header_locked.php'; ?>
    <?php else: ?>
        <?php require 'includes/header_locked.php'; ?>
    <?php endif; ?>

    <main class="project-detail-page">

        <article class="project-detail-card">

            <h1 class="project-title">
                <?= htmlspecialchars($project["title"]) ?>
            </h1>

            <div class="project-meta">
                Published: <?= htmlspecialchars($project["created_at"]) ?>
                •
                Category:
                <?= ucwords(str_replace("_"," ",$project["category"])) ?>
            </div>

            <div class="project-media">

                <?php if($project["media_type"]==="image"): ?>

                    <img
                        src="uploads/projects/<?= htmlspecialchars($project["media"]) ?>"
                        alt="<?= htmlspecialchars($project["title"]) ?>">

                <?php else: ?>

                    <video controls autoplay>

                        <source
                            src="uploads/projects/<?= htmlspecialchars($project["media"]) ?>"
                            type="video/mp4">

                    </video>

                <?php endif; ?>

            </div>

            <h3 class="summary-heading">
                <?= htmlspecialchars($project["summary"]) ?>
            </h3>

            <div class="project-body">
                <?= nl2br(htmlspecialchars($project["details"])) ?>
            </div>

            <div class="btn-box">

                <?php if (isset($_GET["from"]) && $_GET["from"] === "home"): ?>

                    <a href="home.php">
                        ← Back to Home
                    </a>

                <?php else: ?>

                    <a href="view-projects.php?category=<?= urlencode($project["category"]) ?>">
                        ← Back to
                        <?= ucwords(str_replace("_"," ",$project["category"])) ?>
                    </a>

                <?php endif; ?>

            </div>

        </article>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="js/script-global.js"></script>
</body>
</html>