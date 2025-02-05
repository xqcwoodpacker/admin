<?php

// var_dump($_POST['tags']);
// echo $_POST['tags'][0];
// exit;

// var_dump($_FILES['thumb_image']);
// exit;

session_start();
if (!isset($_SESSION['admin'])) {
    header('location:login.php');
}

require 'db.php';

////////////////////////////////////////////////////////////////////////////////////////////////////////////
//UDF section
function abort($message)
{
    $_SESSION['error'] = $message;
    header('Location:addpost.php');
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
////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!empty($_POST['title'])) {
        $title = $_POST['title'];
    } else {
        abort('title is required');
    }
    if (($_POST['content']) == '<p><br></p>') {
        abort('content is required');
    } else {
        $content = $_POST['content'];
    }
    if (!empty($_POST['category'])) {
        $category = $_POST['category'];
    } else {
        abort('category is required');
    }
    if (!empty($_POST['tags'])) {
        $tags = implode(',', $_POST['tags']);
        // echo $tags;
    } else {
        abort('tags are required');
    }

    //image validation
    if (!empty($_FILES["thumb_image"]["name"])) {

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/img'];
        if (!in_array($_FILES['thumb_image']['type'], $allowedTypes)) {
            abort('Invalid image type. Only JPG and PNG files are allowed.');
        } elseif (($imageInfo = getimagesize($_FILES["thumb_image"]["tmp_name"])) === false) {
            abort('The uploaded file is not a valid image.');
        }

        if ($fileSize > 5000000) {
            abort('File is too large. Maximum size allowed is 5MB.');
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
            abort('file uploading error');
        }
    } else {
        abort('Thumbnail image is required');
    }


    $slug = createSlug($title);
    $meta_description = $_POST['meta_description'];
    $meta_keywords = $_POST['meta_keywords'];

    $sql = "INSERT INTO posts (slug, title,category, tags, content, thumb_img, meta_description, meta_keywords) 
            VALUES (?,?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssss", $slug, $title, $category, $tags, $content, $thumb_img, $meta_description, $meta_keywords);

        // var_dump($stmt);
        // exit;

        if ($stmt->execute()) {
            $stmt->close();
            header('Location: admin.php');
        } else {
            unlink($thumb_img);
            abort('SQL error.');
        }
    } else {
        unlink($thumb_img);
        abort('SQL error.');
    }
}
