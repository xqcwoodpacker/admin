<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit;
}

require 'db.php';

$postCountQuery = "SELECT COUNT(*) AS total_posts FROM posts";
$postCountResult = $conn->query($postCountQuery);
$totalPosts = $postCountResult->fetch_assoc()['total_posts'];

$categoryCountQuery = "SELECT COUNT(*) AS total_categories FROM categories";
$categoryCountResult = $conn->query($categoryCountQuery);
$totalCategories = $categoryCountResult->fetch_assoc()['total_categories'];

$tagCountQuery = "SELECT COUNT(*) AS total_tags FROM tags";
$tagCountResult = $conn->query($tagCountQuery);
$totalTags = $tagCountResult->fetch_assoc()['total_tags'];

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

    <!-- custom css -->
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">

</head>

<body>
    <!-- alert -->
    <div id="messageContainer" class="alert-container"></div>

    <div class="sidebar" id="sidebar">
        <h4 id="dashboard_link">Admin Panel</h4>
        <a href="post/post.php">Posts</a>
        <a href="categories/categories.php">Categories</a>
        <a href="tags/tags.php">Tags</a>
    </div>
    <div class="content" id="content">
        <div class="topbar">
            <div class="toggle-sidebar-btn" id="toggleSidebarBtn"><i class="fa fa-bars"></i></div>
            <h2 class="mb-0">Dashboard</h2>
            <div class="admin-profile">
                <a href="logout.php" class="btn btn-danger btn-sm me-2">Logout</a>
                <span>Admin</span>
                <i class="fa-solid fa-user-circle"></i>
            </div>
        </div>

        <div id="mainDiv">
            <div class="row">
                <!-- Total Posts -->
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Posts</h5>
                            <p class="card-text"><?php echo $totalPosts; ?></p>
                        </div>
                    </div>
                </div>
                <!-- Total Categories -->
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Categories</h5>
                            <p class="card-text"><?php echo $totalCategories; ?></p>
                        </div>
                    </div>
                </div>
                <!-- Total Tags -->
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Tags</h5>
                            <p class="card-text"><?php echo $totalTags; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>


    <!-- jQuery -->
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        //sidebar toggle
        $('#toggleSidebar, #toggleSidebarBtn').click(function () {
            $('#sidebar').toggleClass('closed');
            $('#content').toggleClass('expanded');
        });

        $('#dashboard_link').click(function () {
            window.location.href = 'dashboard.php';
        });
    </script>

</body>

</html>