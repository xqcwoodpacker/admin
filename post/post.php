<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['admin'])) {
    header('location:../login.php');
    exit;
}

require '../db.php';

////////////////////////////////////////////////////////////////////////////////////////////////////////

function getCategoriesOptions($conn)
{
    $sql = "SELECT * FROM categories";
    $result = $conn->query($sql);

    // Initialize an empty string to store the options
    $options = '';

    if ($result->num_rows > 0) {
        // Loop through the categories and append each option to the string
        while ($row = $result->fetch_assoc()) {
            $options .= "<option value='" . $row['id'] . "'>" . $row['category_name'] . "</option>";
        }
    } else {
        // If no categories found, append the disabled option
        $options .= "<option disabled>No categories found</option>";
    }

    return $options; // Return the options as a string
}

function getTagsOptions($conn)
{
    $sql2 = "SELECT * FROM tags";
    $tags = $conn->query($sql2);

    $options = '';

    if ($tags->num_rows > 0) {
        while ($row = $tags->fetch_assoc()) {
            $options .= "<option value='" . $row['id'] . "'>" . $row['tag'] . "</option>";
        }
    } else {
        $options .= "<option disabled>No tags found</option>";
    }

    return $options; // Return the options as a string
}

////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

    <!-- font-awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- choice -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <!-- quill -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" /> -->

    <!-- custom css -->
    <link rel="stylesheet" type="text/css" href="../assets/css/main.css">

</head>

<body>

    <!-- alert -->
    <div id="messageContainer" class="alert-container"></div>

    <div class="sidebar" id="sidebar">
        <h4 id="dashboard_link">Admin Panel</h4>
        <a href="post.php">Posts</a>
        <a href="../categories/categories.php">Categories</a>
        <a href="../tags/tags.php">Tags</a>
    </div>
    <div class="content" id="content">
        <div class="topbar">
            <div class="toggle-sidebar-btn" id="toggleSidebarBtn"><i class="fa fa-bars"></i></div>
            <h2 class="mb-0">Manage Posts</h2>
            <div class="admin-profile">
                <a href="../logout.php" class="btn btn-danger btn-sm me-2">Logout</a>
                <span>Admin</span>
                <i class="fa-solid fa-user-circle"></i>
            </div>
        </div>

        <a href="addpost.php" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPostModal">Add
            Post</a>

        <div id="mainDiv"></div>

        <!-- Add Post HTML-->
        <?php require 'modals/modelAddPost.php'; ?>

        <!-- update Post HTML-->
        <?php require 'modals/modelUpdatePost.php'; ?>

        <!-- delete Post HTML-->
        <?php require 'modals/modelDeletePost.php'; ?>

    </div>


    <!-- jQuery -->
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <!-- quill -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script> -->

    <!-- tinymce -->
    <script src="https://cdn.tiny.cloud/1/tdq7d3va68zwwsfmfrfcj8ki7ljetiyszvrv6geh3hl4v2e6/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- custom css -->
    <script type="text/javascript" charset="utf8" src="post.js"></script>

</body>

</html>