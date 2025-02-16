<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('location:../login.php');
    exit;
}

require '../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
    exit;
}

if (!empty($_POST['tag'])) {
    $tag = $_POST['tag'];
} else {
    echo json_encode(["status" => "error", "message" => "Tag is required"]);
    exit;
}

$sql_check = "SELECT COUNT(*) FROM tags WHERE tag = ?";
if ($stmt_check = $conn->prepare($sql_check)) {
    $stmt_check->bind_param("s", $tag);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($count > 0) {
        echo json_encode(["status" => "error", "message" => "Tag already exists."]);
        exit;
    }
}

$sql = "INSERT INTO tags (tag) VALUES (?)";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $tag);

    if ($stmt->execute()) {
        $stmt->close();
        echo json_encode(["status" => "success", "message" => "Tag added successfully"]);
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => "SQL error."]);
        exit;
    }
} else {
    echo json_encode(["status" => "error", "message" => "SQL error."]);
    exit;
}