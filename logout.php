<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('location: login');
    exit;
}
session_destroy();
header('location: /');
