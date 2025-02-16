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
    echo json_encode(["status" => "error", "message" => "Invalid Tag ID!"]);
    exit;
}

$sql2 = "DELETE FROM tags WHERE id=" . $id;

if ($conn->query($sql2)) {
    echo json_encode(["status" => "success", "message" => "Tag deleted successfully"]);
    exit;
} else {
    echo json_encode(["status" => "error", "message" => "Failed to delete Tag"]);
    exit;
}