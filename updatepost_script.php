<?php

session_start();
if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//UDF section

function abort($message, $id)
{
    $_SESSION['error'] = $message;
    header('Location:updatepost.php?id=' . $id);
    exit;
}

function createSlug($title)
{
    $slug = strtolower($title);
    $slug = str_replace(' ', '-', $slug);
    $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} else {
    abort('Invalid post ID!', $id);
}

require 'db.php';

$sql = "SELECT * FROM posts WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
    } else {
        abort('Post not found!', $id);
    }
} else {
    abort('Database error occurred!', $id);
}


if (!$_SERVER['REQUEST_METHOD'] == 'POST') {
    abort('Invalid request method!', $id);
}

if (!empty($_POST['title'])) {
    $title = $_POST['title'];
} else {
    abort('title is required', $id);
}
if (($_POST['content']) == '<p><br></p>') {
    abort('content is required', $id);
} else {
    $content = $_POST['content'];
}
if (!empty($_POST['category'])) {
    $category = $_POST['category'];
} else {
    abort('category is required', $id);
}
if (!empty($_POST['tags'])) {
    $tags = implode(',', $_POST['tags']);
} else {
    abort('tags are required', $id);
}

if ($_FILES['thumb_image']['error'] == UPLOAD_ERR_OK) {

    $old_image = $post['thumb_img'];

    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/img'];
    if (!in_array($_FILES['thumb_image']['type'], $allowedTypes)) {
        abort('Invalid image type. Only JPG, PNG, JPEG and IMG files are allowed.', $id);
    } elseif (($imageInfo = getimagesize($_FILES["thumb_image"]["tmp_name"])) === false) {
        abort('The uploaded file is not a valid image.', $id);
    }

    if ($fileSize > 5000000) {
        abort('File is too large. Maximum size allowed is 5MB.', $id);
    }

    $target_dir = "uploads/";
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($_FILES["thumb_image"]["name"], PATHINFO_EXTENSION));

    // Generate a new filename using a timestamp and the correct extension
    $new_filename = time() . '_' . basename($_FILES["thumb_image"]["name"]);
    $target_file = $target_dir . $new_filename;

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["thumb_image"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars(basename($_FILES["thumb_image"]["name"])) . " has been uploaded.";
        $thumb_img = $target_file;
    } else {
        abort('file uploading error', $id);
    }
} else {
    $thumb_img = $post['thumb_img'];
}

$title = $_POST['title'];
$slug = createSlug($title);
$content = $_POST['content'];
$meta_description = $_POST['meta_description'];
$meta_keywords = $_POST['meta_keywords'];

$sql = "UPDATE posts SET slug = ?, title = ?, category=?, tags=?, content = ?, thumb_img = ?, meta_description = ?, meta_keywords = ? WHERE id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ssssssssi", $slug, $title, $category, $tags, $content, $thumb_img, $meta_description, $meta_keywords, $id);

    if ($stmt->execute()) {
        $stmt->close();
        header('Location: admin.php');
        exit;
    } else {
        abort('Database error!', $id);
    }
} else {
    abort('Database error!', $id);
}

