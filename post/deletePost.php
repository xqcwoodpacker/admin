<?php
require '../db.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header('location:../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
    exit;
}

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = $_POST['id'];
} else {
    echo json_encode(["status" => "error", "message" => "Invalid post ID!"]);
    exit;
}

$sql1 = "SELECT thumb_img FROM posts WHERE id=" . $id;

$result = $conn->query($sql1);


if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $thumb_img = $row['thumb_img'];

    if (!empty($thumb_img) && file_exists($thumb_img)) {
        if (!unlink($thumb_img)) {
            echo json_encode(["status" => "error", "message" => "Failed to delete image."]);
            exit;
        }
    }
}


// var_dump($result);
// exit;


$sql2 = "DELETE FROM posts WHERE id=" . $id;

if ($conn->query($sql2)) {
    echo json_encode(["status" => "success", "message" => "Post deleted successfully"]);
    exit;
} else {
    echo json_encode(["status" => "error", "message" => "Failed to delete post"]);
    exit;
}