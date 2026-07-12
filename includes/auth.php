<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && !empty($_SESSION['is_admin']);
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: login.php');
        exit;
    }
}

function navActive($page) {
    return basename($_SERVER['PHP_SELF']) === $page ? 'active' : '';
}