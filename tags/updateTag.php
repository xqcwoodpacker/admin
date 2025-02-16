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

if (isset($_POST['editId']) && is_numeric($_POST['editId'])) {
    $id = $_POST['editId'];
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Tag ID!"]);
    exit;
}

if (empty($_POST['updateTag'])) {
    echo json_encode(["status" => "error", "message" => "Tag name is required!"]);
    exit;
} else {
    $tag = $_POST['updateTag'];
}

$sql = "SELECT * FROM tags WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
    } else {
        echo json_encode(["status" => "error", "message" => "Tag not found!"]);
        exit;
    }
} else {
    echo json_encode(["status" => "error", "message" => "Database error occurred!"]);
    exit;
}

// Check if the category name already exists in other records
$sql_check = "SELECT COUNT(*) FROM tags WHERE tag = ? AND id != ?";
if ($stmt_check = $conn->prepare($sql_check)) {
    $stmt_check->bind_param("si", $tag, $id);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($count > 0) {
        echo json_encode(["status" => "error", "message" => "Tag already exists."]);
        exit;
    }
}

$sql = "UPDATE tags SET tag = ? WHERE id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("si", $tag, $id);

    if ($stmt->execute()) {
        $stmt->close();
        echo json_encode(["status" => "success", "message" => "Tag Updated successfully"]);
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => "SQL error."]);
        exit;
    }
} else {
    echo json_encode(["status" => "error", "message" => "SQL error."]);
    exit;
}


