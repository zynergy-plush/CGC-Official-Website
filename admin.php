<?php
require 'config.php';
require 'includes/auth.php';

requireAdmin();

$stmt = $pdo->query("
SELECT *
FROM contact_messages
ORDER BY created_at DESC
");

$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                    <h3></h3>
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

                <button type="button" class="tab-btn" data-target="leaderboard">
                    <i class="bx bx-bar-chart"></i>
                    <span>Leaderboard</span>
                </button>
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
                            <i class="bx bx-check-circle"></i>
                        </div>

                        <h2>CGC Admin Panel</h2>

                        <p>Click an option from the sidebar to get started.</p>

                    </div>

            </section>
            <section id="news" class="profile-section">
                <h2>News</h2>
                <p>Here you can publish news about the club.</p>


                <form method="POST" class="news-form" enctype="multipart/form-data">
                    <input type="hidden" name="create_news" value="1">

                    <label for="news_title">Title</label>
                    <input type="text" id="news_title" name="title" required>

                    <label for="news_category">Category (optional)</label>
                    <input type="text" id="news_category" name="category" placeholder="e.g. Events, Coding, Design">

                    <label for="news_summary">Summary (optional)</label>
                    <textarea id="news_summary" name="summary" rows="2" placeholder="Short summary shown on cards"></textarea>

                    <label for="news_body">Body</label>
                    <textarea id="news_body" name="body" rows="6" required placeholder="Full news content"></textarea>

                    <label for="news_image">Image (optional)</label>
                    <input type="file" id="news_image" name="image_file" accept="image/*">

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
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th style="text-align:center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                                        </td>
                                        <td class="manage-actions">
                                                <form method="post" class="inline-form">
                                                    <input type="hidden" name="hide_news_id" value="">
                                                    <button type="submit" class="btn-small hide">Hide</button>
                                                </form>

                                                <form method="post" class="inline-form">
                                                    <input type="hidden" name="show_news_id" value="">
                                                    <button type="submit" class="btn-small show">Show</button>
                                                </form>
        

                                            <form method="post" class="inline-form" onsubmit="return confirm('Delete this news item?');">
                                                <input type="hidden" name="delete_news_id" value="">
                                                <button type="submit" class="btn-small delete">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
            </section>

            <section id="projects" class="profile-section">
                <h2>Projects</h2>
                <p>Here you can publish projects to show on the Projects page.</p>

                

                <form method="POST" class="news-form" enctype="multipart/form-data">
                    <input type="hidden" name="create_project" value="1">

                    <label for="project_title">Title</label>
                    <input type="text" id="project_title" name="project_title" required>

                    <label for="project_category">Category</label>
                    <select id="project_category" name="project_category" required>
                        <option value="">Select category</option>
                        <option value="designs">Designs</option>
                        <option value="coding">Coding</option>
                        <option value="3d_models">3D Models</option>
                    </select>

                    <label for="project_summary">Summary (optional)</label>
                    <textarea id="project_summary" name="project_summary" rows="2" placeholder="Short text shown on card"></textarea>

                    <label for="project_details">Details</label>
                    <textarea id="project_details" name="project_details" rows="6" required placeholder="Full project description"></textarea>

                    <label for="project_image">Image (optional)</label>
                    <input type="file" id="project_image" name="project_image" accept="image/*">

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

            
                    <div class="manage-news-table-wrapper">
                        <table class="manage-news-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th style="text-align:center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                        <td class="manage-actions">
                                                <form method="post" class="inline-form">
                                                    <input type="hidden" name="hide_project_id" value="">
                                                    <button type="submit" class="btn-small hide">Hide</button>
                                                </form>
                                                <form method="post" class="inline-form">
                                                    <input type="hidden" name="show_project_id" value="">
                                                    <button type="submit" class="btn-small show">Show</button>
                                                </form>
                      

                                            <form method="post" class="inline-form" onsubmit="return confirm('Delete this project?');">
                                                <input type="hidden" name="delete_project_id" value="">
                                                <button type="submit" class="btn-small delete">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                            </tbody>
                        </table>
                    </div>
            </section>

            <section id="leaderboard" class="profile-section">
                <h2>Leaderboard</h2>
                <p>Here you can update the Competitive Programming Leaderboard.</p>
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

                            data-subject="<?= htmlspecialchars($message["subject"]) ?>"
                            data-name="<?= htmlspecialchars($message["name"]) ?>"
                            data-email="<?= htmlspecialchars($message["email"]) ?>"
                            data-message="<?= htmlspecialchars($message["message"]) ?>"
                            data-date="<?= date("M d", strtotime($message["created_at"])) ?>"
                        >

                            <div>

                                <h3>
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

    </div>

</div>

    <script src="js/script-admin.js"></script>  
</body>
</html>