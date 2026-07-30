<header class="header">

    <a href="home.php" class="logo">
        Coding & Graphics Club
    </a>

    <nav class="navbar">

        <?php
        if (isset($project)) {

            // Detail project page
            $returnLink = 'view-projects.php?category=' . urlencode($project['category']);

        } elseif (isset($category) && $category !== '') {

            // View-projects page
            $returnLink = 'projects.php#' . urlencode($category);

        } else {

            // All other pages
            $returnLink = 'home.php';

        }
        ?>

        <a href="<?= $returnLink ?>">
            <button class="return-button">
                Return
            </button>
        </a>

    </nav>

</header>   