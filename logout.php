<?php
require 'config.php';
session_destroy();
header('Location: home.php');
exit;