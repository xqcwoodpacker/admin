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
    echo json_encode(["status" => "error", "message" => "Invalid Category ID!"]);
    exit;
}

$sql2 = "DELETE FROM categories WHERE id=" . $id;

if ($conn->query($sql2)) {
    echo json_encode(["status" => "success", "message" => "Category deleted successfully"]);
    exit;
} else {
    echo json_encode(["status" => "error", "message" => "Failed to delete Category"]);
    exit;
}