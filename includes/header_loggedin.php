<header class="header">
    <a href="home.php" class="logo">Coding & Graphics Club</a>

    <nav class="navbar">
        <a href="home.php" class="<?php echo navActive('home.php'); ?>">Home</a>
        <a href="projects.php" class="<?php echo navActive('projects.php'); ?>">Projects</a>
        <a href="news.php" class="<?php echo navActive('news.php'); ?>">News</a>
        <a href="challenges.php" class="<?php echo navActive('challenges.php'); ?>">Challenges</a>

        <div class="nav-dropdown">
            <a href="activities.php" class="<?php echo navActive('activities.php'); ?>">
                Activities <i class='bx bx-chevron-down'></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="club-schedule.php"><i class='bx bx-calendar'></i> Club Schedule</a>
                <a href="past-activities.php"><i class='bx bx-archive'></i> Past Activities</a>
            </div>
        </div>

        <div class="nav-dropdown">
            <a href="core.php" class="<?php echo navActive('core.php'); ?>">
                Core <i class='bx bx-chevron-down'></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="presidents-gallery.php"><i class='bx bx-history'></i> Presidents' Gallery</a>
            </div>
        </div>

        <a href="contact.php" class="<?php echo navActive('contact.php'); ?>">Contact</a>

        <div class="profile-menu">
            <a href="admin.php" class="nav-icon-link" title="Admin">
                <i class='bx bx-shield-alt-2'></i>
            </a>
        </div>
    </nav>
</header>