<?php
require 'db.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $_SESSION['error'] = 'Invalid post ID!';
    header('location:admin.php');
    exit;
}

$sql1 = "SELECT thumb_img FROM posts WHERE id=" . $id;

$result = $conn->query($sql1);


if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $thumb_img = $row['thumb_img'];

    if (!empty($thumb_img) && file_exists($thumb_img)) {
        if (!unlink($thumb_img)) {
            $_SESSION['error'] = "Failed to delete image.";
            header('location:admin.php');
            exit;
        }
    }
}


// var_dump($result);
// exit;


$sql2 = "DELETE FROM posts WHERE id=" . $id;

if ($conn->query($sql2)) {
    header('location:admin.php');
    exit;
} else {
    $_SESSION['error'] = "Failed to delete post.";
    header('location:admin.php');
    exit;
}