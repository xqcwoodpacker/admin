<?php
require '../db.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit;
}

$sql = "
    SELECT p.*, GROUP_CONCAT(pt.tag_id ORDER BY t.tag) AS tag_ids 
    FROM posts p
    LEFT JOIN posts_tags pt ON p.id = pt.post_id
    LEFT JOIN tags t ON pt.tag_id = t.id
    GROUP BY p.id
    ORDER BY p.id DESC
";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='table-responsive'>
        <table id='postsTable' class='table table-striped table-bordered'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Thumbnail</th>
                <th>Action</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>";

    while ($row = $result->fetch_assoc()) {
        $statusChecked = ($row['status'] === 'active') ? 'checked' : '';
        
        // Prepare the tags for the data-tags attribute
        // $tags = $row['tags'] ? $row['tags'] : ''; // If tags exist, use them; otherwise, use an empty string
        $tag_ids = $row['tag_ids'] ? $row['tag_ids'] : '';
        
        echo "<tr>
            <td>" . htmlspecialchars($row["id"]) . "</td>
            <td>" . htmlspecialchars($row["title"]) . "</td>
            <td>" . htmlspecialchars($row["category"]) . "</td>
            <td><img src='" . htmlspecialchars($row["thumb_img"]) . "' alt='Thumbnail' class='img-thumbnail' width='50'></td>

            <td class='action-btn1 text-center'>
                <a href='#updatePostModal'

                data-id='" . htmlspecialchars($row['id']) . "'
                data-content='" . htmlspecialchars($row['content']) . "'
                data-title='" . htmlspecialchars($row['title']) . "'
                data-category='" . htmlspecialchars($row['category']) . "'
                data-tags='" . htmlspecialchars($tag_ids) . "'
                data-meta_description='" . htmlspecialchars($row['meta_description']) . "'
                data-meta_keywords='" . htmlspecialchars($row['meta_keywords']) . "'
                data-thumb_image='" . $row['thumb_img'] . "'

                class='btn btn-outline-warning' style='display: inline-block;margin-right: 10px;' data-bs-toggle='modal'>
                <i class='fas fa-edit fa-lg'></i></a>
                <a href='#deletePostModal' data-id='" . htmlspecialchars($row['id']) . "' class='btn btn-outline-danger btn-sm' data-bs-toggle='modal'>
                <i class='fas fa-trash-alt fa-lg'></i></a>
            </td>
            <td>
                <label class='switch'>
                    <input type='checkbox' data-post-id='" . htmlspecialchars($row["id"]) . "' class='status-toggle' $statusChecked>
                    <span class='slider'></span>
                </label>
            </td>
        </tr>";
    }

    echo "</tbody></table>
    </div>";
} else {
    echo "<div class='alert alert-warning'>No posts found.</div>";
}
?>
