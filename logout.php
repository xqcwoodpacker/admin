<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('location: login');
}
session_destroy();
header('location: /');
