<?php

require "config.php";
require "includes/auth.php";

requireAdmin();

$id = (int)$_POST["id"];

$stmt = $pdo->prepare("
    UPDATE contact_messages
    SET is_read = 1
    WHERE id = ?
");

$stmt->execute([$id]);