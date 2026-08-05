<?php
require 'config.php';
require 'includes/auth.php';

requireAdmin();



function jsonResponse($success, $message, $data = [])
{
    header('Content-Type: application/json');

    echo json_encode(array_merge([
        "success" => $success,
        "message" => $message
    ], $data));

    exit();
}


function getActivityStatus($startDate,$endDate){

    $today = strtotime(date("Y-m-d"));

    $start = strtotime($startDate);

    $end = strtotime($endDate);

    if($today < $start){

        return "Upcoming";

    }

    if($today > $end){

        return "Past";

    }

    return "Ongoing";

}


/* Delete a message */
if (isset($_POST["delete_message_id"])) {

    $stmt = $pdo->prepare("
        DELETE
        FROM contact_messages
        WHERE id = ?
    ");

    $stmt->execute([
        (int)$_POST["delete_message_id"]
    ]);

    echo "success";
    exit();
}

/* Mark message as read */
if (isset($_POST["mark_read_id"])) {

    $stmt = $pdo->prepare("
        UPDATE contact_messages
        SET is_read = 1
        WHERE id = ?
    ");

    $stmt->execute([
        (int)$_POST["mark_read_id"]
    ]);

    exit();
}
// Load MSGS

$stmt = $pdo->query("
SELECT *
FROM contact_messages
ORDER BY created_at DESC
");

$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// LOAD NEWS

$stmt = $pdo->query("
SELECT *
FROM news
ORDER BY created_at DESC
");



$news = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Delete news */

if(isset($_POST["delete_news_id"])){

    $id = (int)$_POST["delete_news_id"];

    $stmt = $pdo->prepare("
        SELECT media
        FROM news
        WHERE id=?
    ");

    $stmt->execute([$id]);

    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if($item && !empty($item["media"])){

        $file = "uploads/news/".$item["media"];

        if(file_exists($file)){
            unlink($file);
        }

    }

    $stmt = $pdo->prepare("
        DELETE
        FROM news
        WHERE id=?
    ");

    $stmt->execute([$id]);

    jsonResponse(true,"News deleted successfully.");

}

/* Hide news */

if(isset($_POST["hide_news_id"])){

    $stmt = $pdo->prepare("
        UPDATE news
        SET is_visible=0
        WHERE id=?
    ");

    $stmt->execute([
        (int)$_POST["hide_news_id"]
    ]);

    jsonResponse(true,"News hidden.");

}

/* Show news */

if(isset($_POST["show_news_id"])){

    $stmt = $pdo->prepare("
        UPDATE news
        SET is_visible=1
        WHERE id=?
    ");

    $stmt->execute([
        (int)$_POST["show_news_id"]
    ]);

    jsonResponse(true,"News is now visible.");

}

/* Create news */

if(isset($_POST["create_news"])){

    $title = trim(strip_tags($_POST["title"]));
    $category = trim(strip_tags($_POST["category"]));
    $summary = trim(strip_tags($_POST["summary"]));
    $details = trim($_POST["body"]);

    $mediaName = null;
    $mediaType = null;

    if(
        isset($_FILES["news_media"]) &&
        $_FILES["news_media"]["error"] === 0
    ){

        $folder = "uploads/news/";

        if(!is_dir($folder)){
            mkdir($folder, 0777, true);
        }

        $extension = strtolower(
            pathinfo(
                $_FILES["news_media"]["name"],
                PATHINFO_EXTENSION
            )
        );

        $imageTypes = [
            "jpg",
            "jpeg",
            "png",
            "gif",
            "webp",
            "bmp",
            "avif"
        ];

        $videoTypes = [
            "mp4",
            "mov",
            "webm",
            "avi",
            "mkv"
        ];

        if(in_array($extension, $imageTypes)){

            $mediaType = "image";

        }elseif(in_array($extension, $videoTypes)){

            $mediaType = "video";

        }else{

            jsonResponse(false, "Unsupported file type.");

        }

        $mediaName = uniqid("news_", true) . "." . $extension;

        move_uploaded_file(
            $_FILES["news_media"]["tmp_name"],
            $folder . $mediaName
        );

    }else{

        jsonResponse(false, "Please upload an image or video.");

    }

    $stmt = $pdo->prepare("
        INSERT INTO news
        (
            title,
            category,
            summary,
            details,
            media,
            media_type
        )
        VALUES (?,?,?,?,?,?)
    ");

    $stmt->execute([
        $title,
        $category,
        $summary,
        $details,
        $mediaName,
        $mediaType
    ]);

    jsonResponse(
        true,
        "News published successfully!",
        [
            "news" => [
                "id" => $pdo->lastInsertId(),
                "title" => $title,
                "category" => $category,
                "created_at" => date("Y-m-d H:i:s"),
                "is_visible" => 1
            ]
        ]
    );

}


/* Delete project */

if(isset($_POST["delete_project_id"])){

    $id = (int)$_POST["delete_project_id"];

    $stmt = $pdo->prepare("
        SELECT media
        FROM projects
        WHERE id=?
    ");

    $stmt->execute([$id]);

    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    if($project && !empty($project["media"])){

        $file = "uploads/projects/".$project["media"];

        if(file_exists($file)){
            unlink($file);
        }

    }

    // Delete database row
    $stmt = $pdo->prepare("
        DELETE
        FROM projects
        WHERE id=?
    ");

    $stmt->execute([$id]);

    jsonResponse(true, "Project deleted successfully.");
    exit();

}

/* Hide project */

if(isset($_POST["hide_project_id"])){

    $stmt = $pdo->prepare("
        UPDATE projects
        SET is_visible = 0
        WHERE id = ?
    ");

    $stmt->execute([
        (int)$_POST["hide_project_id"]
    ]);

    jsonResponse(true, "Project hidden.");
    exit();

}

/* Show project */

if(isset($_POST["show_project_id"])){

    $stmt = $pdo->prepare("
        UPDATE projects
        SET is_visible = 1
        WHERE id = ?
    ");

    $stmt->execute([
        (int)$_POST["show_project_id"]
    ]);

    jsonResponse(true, "Project is now visible.");
    exit();

}

/* Create project */

if(isset($_POST["create_project"])){

    $title = trim(strip_tags($_POST["project_title"]));
    $category = trim(strip_tags($_POST["project_category"]));
    $summary = trim(strip_tags($_POST["project_summary"]));
    $details = trim($_POST["project_details"]);


    $mediaName = null;
    $mediaType = null;

    if (
        isset($_FILES["project_media"]) &&
        $_FILES["project_media"]["error"] === 0
    ) {

        $folder = "uploads/projects/";

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $extension = strtolower(
            pathinfo($_FILES["project_media"]["name"], PATHINFO_EXTENSION)
        );

        $imageTypes = [
            "jpg",
            "jpeg",
            "png",
            "gif",
            "webp",
            "bmp",
            "avif"
        ];

        $videoTypes = [
            "mp4",
            "mov",
            "webm",
            "avi",
            "mkv"
        ];

        if (in_array($extension, $imageTypes)) {

            $mediaType = "image";

        } elseif (in_array($extension, $videoTypes)) {

            $mediaType = "video";

        } else {

            jsonResponse(false, "Unsupported file type.");

        }

        // Validate category AFTER detecting file type
        if (
            $category === "video_editing" &&
            $mediaType !== "video"
        ) {
            jsonResponse(false, "Video Editing projects must use a video.");
        }

        if (
            $category !== "video_editing" &&
            $mediaType !== "image"
        ) {
            jsonResponse(false, "Only Video Editing projects may upload videos.");
        }

        $mediaName = uniqid("project_", true) . "." . $extension;

        move_uploaded_file(
            $_FILES["project_media"]["tmp_name"],
            $folder . $mediaName
        );

    } else {

        jsonResponse(false, "Please upload an image or video.");

    }

    $top = isset($_POST["is_top_project"]) ? 1 : 0;
    $stmt=$pdo->prepare("
        INSERT INTO projects
        (
        title,
        category,
        summary,
        details,
        media,
        media_type,
        is_top_project
        )
        VALUES(?,?,?,?,?,?,?)
    ");

    $stmt->execute([
        $title,
        $category,
        $summary,
        $details,
        $mediaName,
        $mediaType,
        $top
    ]);


    jsonResponse(true, "Project published successfully!", [

    "project" => [

        "id" => $pdo->lastInsertId(),

        "title" => $title,

        "category" => $category,

        "created_at" => date("Y-m-d H:i:s"),

        "is_visible" => 1

    ]

]);

}

/* LOAD CHALLENGES */

$stmt = $pdo->query("
    SELECT *
    FROM challenges
    ORDER BY created_at DESC
");

$challenges = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Create Challenge */

if(isset($_POST["create_challenge"])){

    $title = trim(strip_tags($_POST["challenge_title"]));
    $description = trim($_POST["challenge_description"]);
    $difficulty = trim($_POST["challenge_difficulty"]);
    $tags = trim(strip_tags($_POST["challenge_tags"]));

    $imageName = null;

    if(
        isset($_FILES["challenge_image"]) &&
        $_FILES["challenge_image"]["error"] === 0
    ){

        $folder = "uploads/challenges/";

        if(!is_dir($folder)){
            mkdir($folder,0777,true);
        }

        $extension = strtolower(
            pathinfo(
                $_FILES["challenge_image"]["name"],
                PATHINFO_EXTENSION
            )
        );

        $allowed = [
            "jpg",
            "jpeg",
            "png",
            "gif",
            "webp",
            "bmp",
            "avif"
        ];

        if(!in_array($extension,$allowed)){
            jsonResponse(false,"Only image files are allowed.");
        }

        $imageName =
            uniqid("challenge_",true).".".$extension;

        move_uploaded_file(
            $_FILES["challenge_image"]["tmp_name"],
            $folder.$imageName
        );

    }else{

        jsonResponse(false,"Please upload an image.");

    }

    $stmt = $pdo->prepare("
        INSERT INTO challenges
        (
            title,
            description,
            image,
            difficulty,
            tags
        )
        VALUES
        (
            ?,?,?,?,?
        )
    ");

    $stmt->execute([
        $title,
        $description,
        $imageName,
        $difficulty,
        $tags
    ]);

    jsonResponse(
        true,
        "Challenge published successfully!",
        [
            "challenge"=>[
                "id"=>$pdo->lastInsertId(),
                "title"=>$title,
                "image"=>$imageName,
                "difficulty"=>$difficulty,
                "tags"=>$tags,
                "created_at"=>date("Y-m-d H:i:s"),
                "is_visible"=>1
            ]
        ]
    );

}

/* Hide challenge */
if (isset($_POST["hide_challenge_id"])) {

    $stmt = $pdo->prepare("
        UPDATE challenges
        SET is_visible = 0
        WHERE id = ?
    ");

    $stmt->execute([
        (int)$_POST["hide_challenge_id"]
    ]);

    jsonResponse(true, "Challenge hidden.");
}

/* Show challenge */
if (isset($_POST["show_challenge_id"])) {

    $stmt = $pdo->prepare("
        UPDATE challenges
        SET is_visible = 1
        WHERE id = ?
    ");

    $stmt->execute([
        (int)$_POST["show_challenge_id"]
    ]);

    jsonResponse(true, "Challenge is now visible.");
}

/* Delete challenge */
if (isset($_POST["delete_challenge_id"])) {

    $id = (int)$_POST["delete_challenge_id"];

    $stmt = $pdo->prepare("
        SELECT image
        FROM challenges
        WHERE id = ?
    ");

    $stmt->execute([$id]);

    $challenge = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($challenge && !empty($challenge["image"])) {

        $file = "uploads/challenges/" . $challenge["image"];

        if (file_exists($file)) {
            unlink($file);
        }
    }

    $stmt = $pdo->prepare("
        DELETE
        FROM challenges
        WHERE id = ?
    ");

    $stmt->execute([$id]);

    jsonResponse(true, "Challenge deleted successfully.");
}

/* ===========================
   LOAD ACTIVITIES
=========================== */

$stmt = $pdo->query("
    SELECT *
    FROM activities
    ORDER BY start_date ASC
");

$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
/* ===========================
   CREATE ACTIVITY
=========================== */

if(isset($_POST["create_activity"])){

    $title = trim($_POST["activity_title"]);
    $summary = trim($_POST["activity_summary"]);
    $description = trim($_POST["activity_description"]);
    $startDate = $_POST["activity_start_date"];
    $endDate = $_POST["activity_end_date"];

    if($title===""){
        jsonResponse(false,"Please enter a title.");
    }

    if($summary===""){
        jsonResponse(false,"Please enter a summary.");
    }

    if($description===""){
        jsonResponse(false,"Please enter a description.");
    }

    if(empty($startDate) || empty($endDate)){
        jsonResponse(false,"Please select both dates.");
    }

    $media = "";
    $mediaType = "";

    if(
        isset($_FILES["activity_media"]) &&
        $_FILES["activity_media"]["error"]===0
    ){

        $folder = "uploads/activities/";

        if(!is_dir($folder)){
            mkdir($folder,0777,true);
        }

        $ext = strtolower(pathinfo(
            $_FILES["activity_media"]["name"],
            PATHINFO_EXTENSION
        ));

        $imageExt = [
            "jpg",
            "jpeg",
            "png",
            "gif",
            "webp"
        ];

        $videoExt = [
            "mp4",
            "webm",
            "mov"
        ];

        if(in_array($ext,$imageExt)){

            $mediaType = "image";

        }elseif(in_array($ext,$videoExt)){

            $mediaType = "video";

        }else{

            jsonResponse(false,"Invalid media type.");

        }

        $media = uniqid("activity_").".".$ext;

        move_uploaded_file(
            $_FILES["activity_media"]["tmp_name"],
            $folder.$media
        );

    }else{

        jsonResponse(false,"Please upload a media file.");

    }

    $stmt = $pdo->prepare("
        INSERT INTO activities
        (
            title,
            summary,
            description,
            start_date,
            end_date,
            media,
            media_type,
            is_visible
        )
        VALUES
        (
            ?,?,?,?,?,?,?,?
        )
    ");

    

    $stmt->execute([
        $title,
        $summary,
        $description,
        $startDate,
        $endDate,
        $media,
        $mediaType,
        1
    ]);

   jsonResponse(
        true,
        "Activity published successfully!",
        [
            "activity" => [
                "id" => $pdo->lastInsertId(),
                "title" => $title,
                "summary" => $summary,
                "description" => $description,
                "start_date" => $startDate,
                "end_date" => $endDate,
                "media" => $media,
                "media_type" => $mediaType,
                "created_at" => date("Y-m-d H:i:s"),
                "is_visible" => 1,
                "status" => getActivityStatus($startDate, $endDate)
            ]
        ]
    );

}

if(isset($_POST["hide_activity_id"])){

    $stmt=$pdo->prepare("
        UPDATE activities
        SET is_visible=0
        WHERE id=?
    ");

    $stmt->execute([
        (int)$_POST["hide_activity_id"]
    ]);

    jsonResponse(true,"Activity hidden.");

}

if(isset($_POST["show_activity_id"])){

    $stmt=$pdo->prepare("
        UPDATE activities
        SET is_visible=1
        WHERE id=?
    ");

    $stmt->execute([
        (int)$_POST["show_activity_id"]
    ]);

    jsonResponse(true,"Activity is now visible.");

}

if(isset($_POST["delete_activity_id"])){

    $id = (int)$_POST["delete_activity_id"];

    $stmt = $pdo->prepare("
        SELECT media
        FROM activities
        WHERE id=?
    ");

    $stmt->execute([$id]);

    $activity = $stmt->fetch(PDO::FETCH_ASSOC);

    if($activity){

        $file = "uploads/activities/".$activity["media"];

        if(file_exists($file)){
            unlink($file);
        }

    }

    $stmt = $pdo->prepare("
        DELETE
        FROM activities
        WHERE id=?
    ");

    $stmt->execute([$id]);

    jsonResponse(true,"Activity deleted.");

}



/* Prevent browser caching */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | CGC</title>
    <link rel="shortcut icon" href="images/Main Logo Circular.png" type="image/x-icon">
    <link rel="stylesheet" href="css/style-global.css">
    <link rel="stylesheet" href="css/style-admin.css">
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.boxicons.com/3.0.8/fonts/filled/boxicons-filled.min.css" rel="stylesheet">
    <link href="https://cdn.boxicons.com/3.0.8/fonts/brands/boxicons-brands.min.css" rel="stylesheet">
</head>
<body>
  <?php require 'includes/background.php'; ?>
  <?php require 'includes/header_locked.php'; ?>

    <div class="profile-layout">
        <aside class="profile-sidebar">
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="bx bx-shield-alt-2"></i>
                </div>
                <div class="profile-info">
                    <h3><?= htmlspecialchars($_SESSION['username']) ?></h3>
                    <p>CGC Admin</p>
                </div>
            </div>

            <div class="profile-menu">
                <div class="menu-group">
                  <button type="button" class="menu-toggle">
                      <span>
                          <i class="bx bx-news"></i>
                          News
                      </span>
                      <i class="bx bx-chevron-down arrow"></i>
                  </button>

                  <div class="submenu">
                      <button type="button" class="tab-btn" data-target="news">
                          <i class="bx bx-plus-circle"></i>
                          <span>Create News</span>
                      </button>

                      <button type="button" class="tab-btn" data-target="manage_news">
                          <i class="bx bx-edit"></i>
                          <span>Manage News</span>
                      </button>
                  </div>
              </div>

              <div class="menu-group">
                  <button type="button" class="menu-toggle">
                      <span>
                          <i class="bx bx-folder"></i>
                          Projects
                      </span>
                      <i class="bx bx-chevron-down arrow"></i>
                  </button>

                  <div class="submenu">
                      <button type="button" class="tab-btn" data-target="projects">
                          <i class="bx bx-folder-plus"></i>
                          <span>Create Project</span>
                      </button>

                      <button type="button" class="tab-btn" data-target="manage_projects">
                          <i class="bx bx-folder-open"></i>
                          <span>Manage Projects</span>
                      </button>
                  </div>
              </div>

              <!-- <div class="menu-group">
                <button type="button" class="menu-toggle">
                    <span>
                        <i class="bx bx-computer"></i>
                        Challenges
                    </span>
                    <i class="bx bx-chevron-down arrow"></i>
                </button>

                <div class="submenu">

                    Competitive Programming
                    <button type="button" class="tab-btn" data-target="competitive_programming">
                        <i class="bx bx-bar-chart"></i>
                        <span>Competitive Programming</span>
                    </button>

                    Nested Dropdown
                    <div class="menu-group nested-menu">

                        <button type="button" class="menu-toggle">
                            <span>
                                <i class="bx bx-message-circle-question-mark"></i>
                                Questions
                            </span>
                            <i class="bx bx-chevron-down arrow"></i>
                        </button>

                        <div class="submenu">

                            <button type="button" class="tab-btn" data-target="new_challenge">
                                <i class="bx bx-plus-circle"></i>
                                <span>New Challenge</span>
                            </button>

                            <button type="button" class="tab-btn" data-target="manage_challenges">
                                <i class="bx bx-edit"></i>
                                <span>Manage Challenges</span>
                            </button>

                        </div>

                    </div>

                    Leaderboard
                    <button type="button" class="tab-btn" data-target="leaderboard">
                        <i class="bx bx-trophy"></i>
                        <span>Leaderboard</span>
                    </button>

                </div>
            </div> -->
                <div class="menu-group">
                    <button type="button" class="menu-toggle">
                        <span>
                            <i class="bx bx-calendar-alt"></i>
                            Activities
                        </span>
                        <i class="bx bx-chevron-down arrow"></i>
                    </button>

                    <div class="submenu">

                        <button
                            type="button"
                            class="tab-btn"
                            data-target="activities">

                            <i class="bx bx-calendar-plus"></i>
                            <span>Publish Activity</span>

                        </button>

                        <!-- <button
                            type="button"
                            class="tab-btn"
                            data-target="manage_activities">

                            <i class="bx bx-calendar-event"></i>
                            <span>Published Activities</span>

                        </button> -->

                        <!-- <button
                            type="button"
                            class="tab-btn"
                            data-target="past_activities"> 
                            <i class="bx bx-history"></i>
                            <span>Past Activities</span>
                        </button> -->

                    </div>
                </div>
                <button type="button" class="tab-btn" data-target="messages">
                    <i class="bx bx-message-circle-reply"></i>
                    <span>Messages</span>
                </button>
                  <button type="button" class="tab-btn logout-btn" onclick="window.location.href='logout.php'">
                  <i class="bx bx-arrow-out-left-square-half"></i>
                  <span>Log Out</span>
                </button>
            </div>
        </aside>

        <main class="profile-content">
            <section id="dashboard" class="profile-section active">

                    <div class="dashboard-home">

                        <div class="dashboard-icon">
                            <i class="bx bx-shield-alt-2"></i>
                        </div>

                        <h2>CGC Admin Panel</h2>

                        <p>Click an option from the sidebar to get started.</p>

                    </div>

            </section>
            <section id="news" class="profile-section">
                <h2>News</h2>
                <p>Here you can publish news about the club.</p>


                <form
                id="newsForm"
                method="POST"
                class="news-form"
                enctype="multipart/form-data">
                    <input type="hidden" name="create_news" value="1">

                    <label for="news_title">Title</label>
                    <input type="text" id="news_title" name="title" required>

                    <label for="news_category">Category (Required)</label>
                    <input type="text" id="news_category" name="category" placeholder="e.g. Events, Coding, Design" required>

                    <label for="news_summary">Summary (Required)</label>
                    <textarea id="news_summary" name="summary" rows="2" placeholder="Short summary shown on cards" required></textarea>

                    <label for="news_body">Details</label>
                    <textarea id="news_body" name="body" rows="6" required placeholder="Full news content"></textarea>

                    <label id="newsMediaLabel" for="news_media">
                    Media (Required)
                    </label>

                    <input
                    type="file"
                    id="news_media"
                    name="news_media"
                    accept="image/*,video/*"
                    required>

                    <button type="submit">Publish news</button>
                </form>
            </section>

            <section id="manage_news" class="profile-section">
                <h2>Manage News</h2>
                <p>Here you can edit, hide, or delete existing news items.</p>

            
                    <div class="manage-news-table-wrapper">
                        <table class="manage-news-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody id="newsTableBody">

                            <?php foreach($news as $item): ?>

                            <tr data-news-id="<?= $item["id"] ?>">

                                <td><?= htmlspecialchars($item["title"]) ?></td>

                                <td><?= htmlspecialchars($item["category"]) ?></td>

                                <td class="status-cell">

                                    <?php if($item["is_visible"]): ?>

                                        <span class="status-badge status-visible">
                                            Visible
                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge status-hidden">
                                            Hidden
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?= date("M d, Y", strtotime($item["created_at"])) ?>

                                </td>

                                <td>

                                    <div class="project-actions">

                                        <?php if($item["is_visible"]): ?>

                                            <form class="inline-form hide-news-form">

                                                <input
                                                    type="hidden"
                                                    name="hide_news_id"
                                                    value="<?= $item["id"] ?>">

                                                <button class="btn-small hide">
                                                    Hide
                                                </button>

                                            </form>

                                        <?php else: ?>

                                            <form class="inline-form show-news-form">

                                                <input
                                                    type="hidden"
                                                    name="show_news_id"
                                                    value="<?= $item["id"] ?>">

                                                <button class="btn-small show">
                                                    Show
                                                </button>

                                            </form>

                                        <?php endif; ?>

                                        <form class="inline-form delete-news-form">

                                            <input
                                                type="hidden"
                                                name="delete_news_id"
                                                value="<?= $item["id"] ?>">

                                            <button class="btn-small delete">
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                            <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
            </section>

            <section id="projects" class="profile-section">
                <h2>Projects</h2>
                <p>Here you can publish projects to show on the Projects page.</p>

                

                <form
                id="projectForm"
                method="POST"
                class="news-form"
                enctype="multipart/form-data">
                    <input type="hidden" name="create_project" value="1">

                    <label for="project_title">Title</label>
                    <input type="text" id="project_title" name="project_title" required>

                    <label for="project_category">Category</label>
                    <select id="project_category" name="project_category" required>
                        <option value="">Select category</option>
                        <option value="coding">Coding</option>
                        <option value="designs">Designs</option>
                        <option value="3d_models">3D Models</option>
                        <option value="video_editing">Video Editing</option>
                    </select>

                    <label for="project_summary">Summary (Required)</label>
                    <textarea id="project_summary" name="project_summary" rows="2" required placeholder="Short text shown on card"></textarea>

                    <label for="project_details">Details (Required)</label>
                    <textarea id="project_details" name="project_details" rows="6" required placeholder="Full project description"></textarea>

                    <label for="project_media">Media (Required)</label>
                    <input
                    type="file"
                    id="project_media"
                    name="project_media"
                    accept="image/*,video/*"
                    required>

                    <label>
                        <input type="checkbox" name="is_top_project" value="1">
                        Mark as Top Project
                    </label>

                    <button type="submit">Publish project</button>
                </form>
            </section>

            <section id="manage_projects" class="profile-section">
                <h2>Manage Projects</h2>
                <p>Here you can hide, show, or delete existing projects.</p>

            
                    <div id="projectsTableWrapper" class="manage-projects-table-wrapper">
                        <table class="manage-projects-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <?php
                                $stmt = $pdo->query("
                                    SELECT *
                                    FROM projects
                                    ORDER BY created_at DESC
                                ");

                                $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>  

                            <tbody id="projectsTableBody">

                            <?php foreach($projects as $project): ?>

                            <tr data-project-id="<?= $project["id"] ?>">

                                <td><?= htmlspecialchars($project["title"]) ?></td>

                                <td><?= htmlspecialchars($project["category"]) ?></td>

                                <td class="status-cell">

                                    <?php if($project["is_visible"]): ?>

                                        <span class="status-badge status-visible">
                                            Visible
                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge status-hidden">
                                            Hidden
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?= date("M d, Y", strtotime($project["created_at"])) ?>

                                </td>

                                <td>

                                    <div class="project-actions">

                                        <?php if($project["is_visible"]): ?>

                                            <form class="inline-form hide-project-form">

                                                <input
                                                    type="hidden"
                                                    name="hide_project_id"
                                                    value="<?= $project["id"] ?>">

                                                <button class="btn-small hide">
                                                    Hide
                                                </button>

                                            </form>

                                        <?php else: ?>

                                            <form class="inline-form show-project-form">

                                                <input
                                                    type="hidden"
                                                    name="show_project_id"
                                                    value="<?= $project["id"] ?>">

                                                <button class="btn-small show">
                                                    Show
                                                </button>

                                            </form>

                                        <?php endif; ?>

                                        <form class="inline-form delete-project-form">

                                            <input
                                                type="hidden"
                                                name="delete_project_id"
                                                value="<?= $project["id"] ?>">

                                            <button class="btn-small delete">
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                            <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
            </section>

            <section id="competitive_programming" class="profile-section">
                <h2>Competitive Programming</h2>
                <p>Here you can update the Competitive Programming Section.</p>
            </section>

            <section id="new_challenge" class="profile-section">
                <h2>New Challenge</h2>

                <p>
                    Here you can add a new challenge to the
                    <b><a href="challenges.php">Challenges</a></b> page.
                </p>

                <form
                    id="challengeForm"
                    class="news-form"
                    method="POST"
                    enctype="multipart/form-data">

                    <input type="hidden" name="create_challenge" value="1">

                    <label for="challenge_title">
                        Challenge Title
                    </label>

                    <input
                        type="text"
                        id="challenge_title"
                        placeholder="Enter the Challenge Title"
                        name="challenge_title"
                        required>

                    
                    <label for="challenge_tags">
                        Tags
                    </label>

                    <input
                        type="text"
                        id="challenge_tags"
                        name="challenge_tags"
                        placeholder="Example: Arrays, Beginner, DP"
                        required>

                    <small class="form-note">
                        Separate multiple tags with commas.
                    </small>
                        
                    <label for="challenge_description">
                        Description
                    </label>

                    <textarea id="challenge_description" name="challenge_description" placeholder="Describe the challenge" required></textarea>


                    <label for="challenge_difficulty">Difficulty</label>

                    <select id="challenge_difficulty" name="challenge_difficulty" required>
                        <option value="Easy">Easy</option>
                        <option value="Medium">Medium</option>
                        <option value="Hard">Hard</option>
                    </select>

                    <label for="challenge_image">
                        Challenge Image
                    </label>

                    <input
                        type="file"
                        id="challenge_image"
                        name="challenge_image"
                        accept="image/*"
                        required>

                    

                    <button type="submit">
                        Publish Challenge
                    </button>

                </form>
            </section>

            <!-- <section id="manage_challenges" class="profile-section">
                <h2>Manage Challenges</h2>  
                <p>Here you can manage existing challenges, shown in the <b><a href="challenges.php">Challenges</a></b> Page.</p>
            </section> -->

            <section id="manage_challenges" class="profile-section">

                <h2>Manage Challenges</h2>
                <p>Here you can manage existing challenges, shown in the <b><a href="challenges.php">Challenges</a></b> Page.</p>
                &nbsp;

                <?php
                $stmt = $pdo->query("
                    SELECT *
                    FROM challenges
                    ORDER BY created_at DESC
                ");

                $challenges = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <?php if(isset($_GET['challenge_message'])): ?>

                    <p class="message">
                        <?= htmlspecialchars($_GET['challenge_message']) ?>
                    </p>

                <?php endif; ?>

                <div class="manage-projects-table-wrapper">

                    <table class="manage-projects-table">

                        <thead>

                            <tr>

                                <th>Title</th>
                                <th>Difficulty</th>
                                <th>Tags</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>

                            </tr>

                        </thead>

                        <tbody id="challengesTableBody">

                        <?php foreach($challenges as $challenge): ?>

                            <tr>


                                <td><?= htmlspecialchars($challenge['title']) ?></td>

                                <td><?= htmlspecialchars($challenge['difficulty']) ?></td>

                                <td><?= htmlspecialchars($challenge['tags']) ?></td>

                                <td class="status-cell">
                                    <?php if($challenge['is_visible']): ?>

                                        <span class="status-badge status-visible">
                                            Visible
                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge status-hidden">
                                            Hidden
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?= date("M d, Y", strtotime($challenge['created_at'])) ?>

                                </td>

                                <td>

                                    <div class="project-actions">

                                        <?php if($challenge['is_visible']): ?>

                                            <form class="inline-form hide-challenge-form">

                                                <input
                                                    type="hidden"
                                                    name="hide_challenge_id"
                                                    value="<?= $challenge['id'] ?>">

                                                <button class="btn-small hide">
                                                    Hide
                                                </button>

                                            </form>

                                        <?php else: ?>

                                            <form class="inline-form show-challenge-form">

                                                <input
                                                    type="hidden"
                                                    name="show_challenge_id"
                                                    value="<?= $challenge['id'] ?>">

                                                <button class="btn-small show">
                                                    Show
                                                </button>

                                            </form>

                                        <?php endif; ?>

                                        <form class="inline-form delete-challenge-form">

                                            <input
                                                type="hidden"
                                                name="delete_challenge_id"
                                                value="<?= $challenge['id'] ?>">

                                            <button class="btn-small delete">
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </section>

            <section id="leaderboard" class="profile-section">
                <h2>Leaderboard</h2>
                <p>Here you can update the Leaderboard.</p>
            </section>

            <section id="activities" class="profile-section">

                <h2>Activities</h2>

                <p>
                    Here you can publish club activities that will appear on the Activities page.
                </p>

                <form
                    id="activityForm"
                    method="POST"
                    class="news-form"
                    enctype="multipart/form-data">

                    <input
                        type="hidden"
                        name="create_activity"
                        value="1">

                    <label for="activity_title">
                        Activity Title
                    </label>

                    <input
                        type="text"
                        id="activity_title"
                        name="activity_title"
                        placeholder="Enter activity title"
                        required>

                    

                    <label for="activity_start_date">
                        Start Date
                    </label>

                    <input
                        type="date"
                        id="activity_start_date"
                        name="activity_start_date"
                        required>

                    <label for="activity_end_date">
                        End Date
                    </label>

                    <input
                        type="date"
                        id="activity_end_date"
                        name="activity_end_date"
                        required>

                    <label for="activity_summary">
                        Summary
                    </label>

                    <textarea
                        id="activity_summary"
                        name="activity_summary"
                        rows="1"
                        placeholder="A short summary shown on the Activities page..."
                        required></textarea>

                    <label for="activity_description">
                        Description
                    </label>

                    <textarea
                        id="activity_description"
                        name="activity_description"
                        rows="6"
                        placeholder="Describe the activity..."
                        required></textarea>



                    <label for="activity_media">
                        Media (Image or Video)
                    </label>

                    <input
                        type="file"
                        id="activity_media"
                        name="activity_media"
                        accept="image/*,video/*"
                        required>

                    <div
                        id="activityPreview"
                        style="display:none;margin-top:15px;">
                    </div>

                    <button type="submit">
                        Publish Activity
                    </button>

                </form>

            </section>

            <section id="manage_activities" class="profile-section">

                <h2>Published Activities</h2>

                <p>
                    Manage all upcoming and ongoing activities.
                </p>

                <div class="manage-activities-table-wrapper">

                    <table class="manage-activities-table">

                        <thead>

                            <tr>

                                <th>Media</th>
                                <th>Title</th>
                                <th>Dates</th>
                                <th>Status</th>
                                <th>Visibility</th>
                                <th>Actions</th>

                            </tr>

                        </thead>

                        <tbody id="activitiesTableBody">

                            <?php foreach($activities as $activity): ?>

                                <?php

                                    if(strtotime($activity["end_date"]) < strtotime(date("Y-m-d"))){

                                        continue;

                                    }

                                    $status = getActivityStatus(
                                        $activity["start_date"],    
                                        $activity["end_date"]
                                    );

                                ?>

                                <tr data-activity-id="<?= $activity["id"] ?>">

                                    <td>

                                        <?php if($activity["media_type"]==="image"): ?>

                                            <img
                                                src="uploads/activities/<?= htmlspecialchars($activity["media"]) ?>"
                                                style="
                                                    width:70px;
                                                    height:45px;
                                                    object-fit:cover;
                                                    border-radius:8px;
                                                ">

                                        <?php else: ?>

                                            <video
                                                width="70"
                                                height="45"
                                                muted>

                                                <source
                                                    src="uploads/activities/<?= htmlspecialchars($activity["media"]) ?>">

                                            </video>

                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <?= htmlspecialchars($activity["title"]) ?>

                                    </td>

                                    <td>

                                        <?= date("M j, Y",strtotime($activity["start_date"])) ?>

                                        <br>

                                        <small>

                                            to

                                            <?= date("M j, Y",strtotime($activity["end_date"])) ?>

                                        </small>

                                    </td>

                                    <td>

                                        <span class="status-badge">

                                            <?= $status ?>

                                        </span>

                                    </td>

                                    <td class="status-cell">

                                        <?php if($activity["is_visible"]): ?>

                                            <span class="status-badge status-visible">

                                                Visible

                                            </span>

                                        <?php else: ?>

                                            <span class="status-badge status-hidden">

                                                Hidden

                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <div class="manage-actions">

                                            <?php if($activity["is_visible"]): ?>

                                                <form
                                                    class="inline-form hide-activity-form"
                                                    method="post">

                                                    <input
                                                        type="hidden"
                                                        name="hide_activity_id"
                                                        value="<?= $activity["id"] ?>">

                                                    <button
                                                        class="btn-small hide">

                                                        Hide

                                                    </button>

                                                </form>

                                            <?php else: ?>

                                                <form
                                                    class="inline-form show-activity-form"
                                                    method="post">

                                                    <input
                                                        type="hidden"
                                                        name="show_activity_id"
                                                        value="<?= $activity["id"] ?>">

                                                    <button
                                                        class="btn-small show">

                                                        Show

                                                    </button>

                                                </form>

                                            <?php endif; ?>

                                            <form
                                                class="inline-form delete-activity-form"
                                                method="post">

                                                <input
                                                    type="hidden"
                                                    name="delete_activity_id"
                                                    value="<?= $activity["id"] ?>">

                                                <button
                                                    class="btn-small delete">

                                                    Delete

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </section>

            <section id="messages" class="profile-section">

                <h2>Messages</h2>

                <p>
                    Here you can check messages given through the
                    <b><a href="contact.php">Contact</a></b>
                    page.
                </p>

                <div class="messages-list">

                    <?php foreach ($messages as $message): ?>

                        <div
                            class="message-card"

                            data-id="<?= $message["id"] ?>"

                            data-subject="<?= htmlspecialchars($message["subject"]) ?>"
                            data-name="<?= htmlspecialchars($message["name"]) ?>"
                            data-email="<?= htmlspecialchars($message["email"]) ?>"
                            data-message="<?= htmlspecialchars($message["message"]) ?>"
                            data-date="<?= date("M d, Y h:i A", strtotime($message["created_at"])) ?>"
                        >

                            <div>

                                <h3>
                                    <?php if(!$message["is_read"]): ?>
                                        <span class="unread-dot"></span>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($message["subject"]) ?>
                                </h3>

                                <p>
                                    <strong>
                                        <?= htmlspecialchars($message["name"]) ?>
                                    </strong>
                                    &lt;<?= htmlspecialchars($message["email"]) ?>&gt;
                                </p>

                            </div>

                            <span>
                                <?= date("M d", strtotime($message["created_at"])) ?>
                            </span>

                        </div>

                    <?php endforeach; ?>

                </div>

            </section>



        </main>
    </div>
    <div class="message-modal">

        <div class="modal-box">

            <button class="close-modal">
                &times;
            </button>

            <h2 id="modalSubject"></h2>

            <p>
                <strong>From:</strong>
                <span id="modalName"></span>
            </p>

            <p>
                <strong>Email:</strong>
                <span id="modalEmail"></span>
            </p>

            <p>
                <strong>Date:</strong>
                <span id="modalDate"></span>
            </p>

            <hr>

            <p id="modalMessage"></p>

            <div class="modal-actions">

                <button id="replyBtn">
                    <i class="bx bx-copy"></i>
                    <span>Reply</span>
                </button>

                <button id="deleteBtn">
                    <i class="bx bx-trash"></i>
                    Delete
                </button>

            </div>

        </div>

    </div>

    <div id="toast"></div>
    <script src="js/script-admin.js"></script>  
</body>
</html>