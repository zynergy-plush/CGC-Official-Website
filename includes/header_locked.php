<header class="header">

    <a href="home.php" class="logo">
        Coding & Graphics Club
    </a>

    <nav class="navbar">

        <?php

        $returnLink = 'home.php';

        /*
        * DETAIL PROJECT PAGE
        */
        if (basename($_SERVER['PHP_SELF']) === 'detail-projects.php') {

            if (
                isset($_GET['from']) &&
                $_GET['from'] === 'home'
            ) {

                $returnLink = 'home.php';

            } elseif (isset($project['category'])) {

                $returnLink =
                    'view-projects.php?category=' .
                    urlencode($project['category']);

            }

        /*
        * DETAIL NEWS PAGE
        */
        } elseif (basename($_SERVER['PHP_SELF']) === 'detail-news.php') {

            $returnLink = 'news.php';

        /*
        * VIEW PROJECTS PAGE
        */
        } elseif (
            basename($_SERVER['PHP_SELF']) === 'view-projects.php' &&
            isset($category) &&
            $category !== ''
        ) {

            $returnLink =
                'projects.php#' .
                urlencode($category);

        /*
        * EVERYTHING ELSE
        */
        } else {

            $returnLink = 'home.php';

        }

        ?>

        <a href="<?= htmlspecialchars($returnLink) ?>">
            <button class="return-button" type="button">
                Return
            </button>
        </a>

    </nav>

</header>