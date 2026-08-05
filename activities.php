<?php
require 'config.php';
require 'includes/auth.php';

$today = date("Y-m-d");

$stmt = $pdo->prepare("
    SELECT *
    FROM activities
    WHERE is_visible = 1
    AND end_date >= ?
    ORDER BY start_date ASC
");

$stmt->execute([$today]);

$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

$featuredActivity = null;
$otherActivities = [];

if(!empty($activities)){

    $featuredActivity = array_shift($activities);

    $otherActivities = $activities;

}
/* ===========================
   PAST ACTIVITIES
=========================== */

$stmt = $pdo->prepare("
    SELECT *
    FROM activities
    WHERE is_visible = 1
    AND end_date < ?
    ORDER BY end_date DESC
");

$stmt->execute([$today]);

$pastActivities = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt->execute([$today]);

$pastActivities = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activities | CGC</title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="css/style-global.css">
    <link rel="stylesheet" href="css/style-activities.css">

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

    <main class="activities-page">

        <!-- ===========================
             FEATURED ACTIVITY
        ============================ -->

        <section class="featured-activity">

            <?php if ($featuredActivity): ?>

                <div class="featured-card">

                    <div class="featured-media">

                        <?php if ($featuredActivity["media_type"] == "image"): ?>

                            <img
                                src="uploads/activities/<?= htmlspecialchars($featuredActivity["media"]) ?>"
                                alt="<?= htmlspecialchars($featuredActivity["title"]) ?>">

                        <?php else: ?>

                            <video autoplay muted loop playsinline>

                                <source
                                    src="uploads/activities/<?= htmlspecialchars($featuredActivity["media"]) ?>">

                            </video>

                        <?php endif; ?>

                    </div>

                    <div class="featured-content">

                        <span class="category-badge">

                            <?= strtotime($featuredActivity["start_date"]) > time()
                                ? "Upcoming Activity"
                                : "Ongoing Activity"; ?>

                        </span>

                        <h2>

                            <?= htmlspecialchars($featuredActivity["title"]) ?>

                        </h2>

                        <div class="activity-date">

                            <?= date("F j, Y", strtotime($featuredActivity["start_date"])) ?>

                            —

                            <?= date("F j, Y", strtotime($featuredActivity["end_date"])) ?>

                        </div>

                        <p>

                            <?= nl2br(htmlspecialchars($featuredActivity["summary"])) ?>

                        </p>

                        <div class="btn-box">

                            <a href="detail-activity.php?id=<?= $featuredActivity["id"] ?>">

                                View Details

                            </a>

                        </div>

                    </div>

                </div>

            <?php else: ?>

                <div class="featured-card no-activity">

                    <h2>No Activities Scheduled</h2>

                </div>

            <?php endif; ?>

        </section>

        <!-- ===========================
             SEARCH
        ============================ -->

        <section class="activity-search">

            <div class="search-box">

                <i class='bx bx-search'></i>

                <input
                    type="text"
                    id="activitySearch"
                    placeholder="Search activities...">

            </div>

        </section>

        <!-- ===========================
             UPCOMING / ONGOING
        ============================ -->

        <section
            class="activities-grid"
            id="activitiesGrid">

            <?php if (!empty($otherActivities)): ?>

                <?php foreach ($otherActivities as $activity): ?>

                    <div
                        class="activity-card"
                        data-title="<?= strtolower(htmlspecialchars($activity["title"])) ?>"
                        data-description="<?= strtolower(htmlspecialchars($activity["description"])) ?>">

                        <?php if ($activity["media_type"] == "image"): ?>

                            <img
                                src="uploads/activities/<?= htmlspecialchars($activity["media"]) ?>"
                                alt="<?= htmlspecialchars($activity["title"]) ?>">

                        <?php else: ?>

                            <video autoplay muted loop playsinline>

                                <source
                                    src="uploads/activities/<?= htmlspecialchars($activity["media"]) ?>">

                            </video>

                        <?php endif; ?>

                        <div class="activity-card-content">

                            <h3>

                                <?= htmlspecialchars($activity["title"]) ?>

                            </h3>

                            <div class="activity-date">

                                <?= date("M j", strtotime($activity["start_date"])) ?>

                                —

                                <?= date("M j, Y", strtotime($activity["end_date"])) ?>

                            </div>

                            <p>

                                <?= htmlspecialchars($activity["summary"]) ?>

                            </p>

                            <div class="btn-box">

                                <a href="detail-activity.php?id=<?= $activity["id"] ?>">

                                    View Details

                                </a>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="featured-card no-activity">

                    <h2>

                        No More Upcoming Activities

                    </h2>

                </div>

            <?php endif; ?>

        </section>

        <!-- ===========================
             PAST ACTIVITIES
        ============================ -->

        <section class="past-activities">

            <h2 class="timeline-title">

                Past Activities

            </h2>

            <?php if (!empty($pastActivities)): ?>

                <div class="timeline">

                    <?php foreach ($pastActivities as $activity): ?>

                        <div class="timeline-item">

                            <div class="timeline-dot"></div>

                            <div class="timeline-card">

                                <div class="timeline-header">

                                    <h3>

                                        <?= htmlspecialchars($activity["title"]) ?>

                                    </h3>

                                    <span class="activity-date">

                                        <?= date("F j, Y", strtotime($activity["start_date"])) ?>

                                        <?php if ($activity["start_date"] != $activity["end_date"]): ?>

                                            —

                                            <?= date("F j, Y", strtotime($activity["end_date"])) ?>

                                        <?php endif; ?>

                                    </span>

                                </div>

                                <?php if ($activity["media_type"] == "image"): ?>

                                    <img
                                        class="timeline-media"
                                        src="uploads/activities/<?= htmlspecialchars($activity["media"]) ?>"
                                        alt="<?= htmlspecialchars($activity["title"]) ?>">

                                <?php else: ?>

                                    <video
                                        class="timeline-media"
                                        autoplay
                                        muted
                                        loop
                                        playsinline>

                                        <source
                                            src="uploads/activities/<?= htmlspecialchars($activity["media"]) ?>">

                                    </video>

                                <?php endif; ?>

                                <p>

                                    <?= nl2br(htmlspecialchars($activity["description"])) ?>

                                </p>

                                <div class="btn-box">

                                    <a href="detail-activity.php?id=<?= $activity["id"] ?>">

                                        View Details

                                    </a>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="featured-card no-activity">

                    <h2>

                        No Past Activities Yet

                    </h2>

                </div>

            <?php endif; ?>

        </section>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script src="js/script-global.js"></script>
    <script src="js/script-activities.js"></script>

</body>
</html>