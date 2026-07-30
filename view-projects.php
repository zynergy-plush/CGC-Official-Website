<?php
require 'config.php';
require 'includes/auth.php';

$allowedCategories = [

    "coding",
    "designs",
    "3d_models",
    "video_editing"

];

$category = $_GET["category"] ?? "";

if(!in_array($category,$allowedCategories)){

    header("Location: projects.php");

    exit;

}

$stmt = $pdo->prepare("

    SELECT *

    FROM projects

    WHERE category = ?

    AND is_visible = 1

    ORDER BY created_at DESC

");

$stmt->execute([$category]);

$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

$latestProject = null;

$olderProjects = [];

if(!empty($projects)){

    $latestProject = array_shift($projects);

    $olderProjects = $projects;

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ucwords(str_replace("_", " ", $category)) ?> | CGC</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style-global.css">
    <link rel="stylesheet" href="css/style-view-projects.css">
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

    <main class="view-projects">



        <section class="featured-project">

            <?php if($latestProject): ?>

            <div class="featured-card">

                <div class="featured-media">

                    <?php if($latestProject["media_type"]==="image"): ?>

                        <img
                            src="uploads/projects/<?= htmlspecialchars($latestProject["media"]) ?>"
                            alt="<?= htmlspecialchars($latestProject["title"]) ?>">

                    <?php else: ?>

                        <video autoplay muted loop playsinline>
                            <source
                                src="uploads/projects/<?= htmlspecialchars($latestProject["media"]) ?>"
                                type="video/mp4">
                        </video>

                    <?php endif; ?>

                </div>

                <div class="featured-content">

                    <span class="category-badge">
                        <?= ucwords(str_replace("_"," ",$latestProject["category"])) ?>
                    </span>

                    <h2>
                        <?= htmlspecialchars($latestProject["title"]) ?>
                    </h2>

                    <div class="project-date">
                        Published: <?= htmlspecialchars($latestProject["created_at"]) ?>
                    </div>

                    <p>
                        <?= htmlspecialchars($latestProject["summary"]) ?>
                    </p>

                    <div class="btn-box">
                        <a href="detail-projects.php?id=<?= urlencode($latestProject["id"]) ?>">
                            Visit Project
                        </a>
                    </div>

                </div>

            </div>

            <?php else: ?>

                <div class="featured-card no-projects">

                    <h2>No Projects Available</h2>

                </div>

            <?php endif; ?>

        </section>



        <section class="project-search">

            <div class="search-box">

                <i class='bx bx-search'></i>

                <input

                    type="text"

                    id="projectSearch"

                    placeholder="Search Projects..."

                >

            </div>

        </section>



        <section class="older-projects" id="projectsGrid">

            <?php foreach($olderProjects as $project): ?>

            <div
                class="project-card"
                data-title="<?= strtolower(htmlspecialchars($project["title"])) ?>"
                data-summary="<?= strtolower(htmlspecialchars($project["summary"])) ?>"
            >

                <?php if($project["media_type"]==="image"): ?>

                    <img src="uploads/projects/<?= htmlspecialchars($project["media"]) ?>">

                <?php else: ?>

                    <video autoplay muted loop playsinline>
                        <source
                            src="uploads/projects/<?= htmlspecialchars($project["media"]) ?>"
                            type="video/mp4">
                    </video>

                <?php endif; ?>

                <div class="project-card-content">

                    <h3><?= htmlspecialchars($project["title"]) ?></h3>

                    <div class="project-date">
                        <?= htmlspecialchars($project["created_at"]) ?>
                    </div>

                    <p><?= htmlspecialchars($project["summary"]) ?></p>

                    <div class="btn-box">
                        <a href="detail-projects.php?id=<?= urlencode($project["id"]) ?>">
                            Visit Project
                        </a>
                    </div>

                </div>

            </div>

            <?php endforeach; ?>
        </section>

    </main>


    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="js/script-global.js"></script>
    <script src="js/script-view-projects.js"></script>
</body>
</html>