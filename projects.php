<?php

require 'config.php';
require 'includes/auth.php';


$stmt = $pdo->query("
    SELECT *
    FROM projects
    WHERE is_visible = 1
    ORDER BY created_at DESC
");


$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);


function getProjectsByCategory($projects, $category){

    return array_filter($projects, function($project) use ($category){

        return $project["category"] === $category;

    });

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects | CGC</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="css/style-projects.css">
        <link rel="stylesheet" href="css/style-global.css">
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

    <section class="projects-hero">
        <div class="btn-box project-filter">

            <a href="#coding" data-category="Coding">
                Coding
            </a>

            <a href="#designs" data-category="Designs">
                Designs
            </a>

            <a href="#models" data-category="3D Models">
                3D Models
            </a>

            <a href="#video" data-category="Video Editing">
                Video Editing
            </a>

        </div>

        <h1 class="projects-heading" id="coding">Coding</h1>

        <div class="projects-carousel">

            <div class="swiper projectsSwiper">

                <div class="swiper-wrapper">
                    <?php foreach(getProjectsByCategory($projects,"coding") as $project): ?>

                    <div class="swiper-slide"

                    data-title="<?= htmlspecialchars($project["title"]) ?>">


                    <?php if($project["media_type"] === "image"): ?>

                    <img src="uploads/projects/<?= htmlspecialchars($project["media"]) ?>">


                    <?php elseif($project["media_type"] === "video"): ?>

                    <video autoplay muted loop>
                        <source src="uploads/projects/<?= htmlspecialchars($project["media"]) ?>">
                    </video>


                    <?php endif; ?>


                    </div>

                    <?php endforeach; ?>
                </div>
            </div>

            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

            <div class="project-info">

                <h2 class="projectTitle"></h2>

                <p class="projectDescription"></p>

            </div>

            <div class="btn-box">

                <a href="view-projects.php?category=coding">
                    See More
                </a>

            </div>
        </div>

        <h1 class="projects-heading" id="designs">Designs</h1>

        <div class="projects-carousel">

            <div class="swiper projectsSwiper">

                <div class="swiper-wrapper">

                    <?php foreach(getProjectsByCategory($projects,"designs") as $project): ?>

                    <div class="swiper-slide"

                    data-title="<?= htmlspecialchars($project["title"]) ?>">


                    <?php if($project["media_type"] === "image"): ?>

                    <img src="uploads/projects/<?= htmlspecialchars($project["media"]) ?>">


                    <?php elseif($project["media_type"] === "video"): ?>

                    <video autoplay muted loop>
                        <source src="uploads/projects/<?= htmlspecialchars($project["media"]) ?>">
                    </video>

                    <?php endif; ?>


                    </div>

                    <?php endforeach; ?>

                </div>
            </div>
            
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

            <div class="project-info">

                <h2 class="projectTitle"></h2>

                <p class="projectDescription"></p>

            </div>

            <div class="btn-box">

                <a href="view-projects.php?category=designs">
                    See More
                </a>

            </div>
        </div>

        <h1 class="projects-heading" id="models">3D Models</h1>

        <div class="projects-carousel">

            <div class="swiper projectsSwiper">

                <div class="swiper-wrapper">

                    <?php foreach(getProjectsByCategory($projects,"3d_models") as $project): ?>

                    <div class="swiper-slide"

                    data-title="<?= htmlspecialchars($project["title"]) ?>">


                    <?php if($project["media_type"] === "image"): ?>

                    <img src="uploads/projects/<?= htmlspecialchars($project["media"]) ?>">


                    <?php elseif($project["media_type"] === "video"): ?>

                    <video autoplay muted loop>
                        <source src="uploads/projects/<?= htmlspecialchars($project["media"]) ?>">
                    </video>

                    <?php endif; ?>


                    </div>

                    <?php endforeach; ?>

                </div>
            </div>

            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

            <div class="project-info">

                <h2 class="projectTitle"></h2>

                <p class="projectDescription"></p>

            </div>

            <div class="btn-box">

                <a href="view-projects.php?category=3d_models">
                    See More
                </a>

            </div>
        </div>

    <h1 class="projects-heading" id="video">Video Editing</h1>

    <div class="projects-carousel">

        <div class="swiper projectsSwiper">

            <div class="swiper-wrapper">

                <?php foreach(getProjectsByCategory($projects,"video_editing") as $project): ?>

                <div class="swiper-slide"

                data-title="<?= htmlspecialchars($project["title"]) ?>">


                    <?php if($project["media_type"] === "image"): ?>

                        <img src="uploads/projects/<?= htmlspecialchars($project["media"]) ?>">


                    <?php elseif($project["media_type"] === "video"): ?>

                        <video
                            autoplay
                            muted
                            loop
                            playsinline
                            preload="auto"
                        >
                            <source
                                src="uploads/projects/<?= htmlspecialchars($project["media"]) ?>"
                                type="video/mp4">
                        </video>

                    <?php endif; ?>


                </div>

                <?php endforeach; ?>

            </div>

        </div>

            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

            <div class="project-info">

                <h2 class="projectTitle"></h2>

                <p class="projectDescription"></p>

            </div>

            <div class="btn-box">

                <a href="view-projects.php?category=video_editing">
                    See More
                </a>

            </div>
        </div>

    </section>


    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="js/script-projects.js"></script>
    
    <script src="js/script-global.js"></script>
</body>
</html>