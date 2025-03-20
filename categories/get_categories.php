<?php
require '../db.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header('location:../login.php');
    exit;
}

$sql = "SELECT * FROM categories ORDER BY id DESC";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    echo "<div class='table-responsive'>
        <table id='categoriesTable' class='table table-striped table-bordered'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Action</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>";

    while ($row = $result->fetch_assoc()) {
        $statusChecked = ($row['status'] === 'active') ? 'checked' : '';
        echo "<tr>
            <td>" . htmlspecialchars($row["id"]) . "</td>
            <td>" . htmlspecialchars($row["category_name"]) . "</td>
            <td class='action-btn1 text-center'>
                <a href='#updateCategoryModal'

                data-id='" . htmlspecialchars($row['id']) . "'
                data-name='" . htmlspecialchars($row['category_name']) . "'
                class='btn btn-outline-warning' style='display: inline-block;margin-right: 10px;' data-bs-toggle='modal'>
                <i class='fas fa-edit fa-lg'></i></a>
                <a href='#deleteCategoryModal' data-id='" . htmlspecialchars($row['id']) . "' class='btn btn-outline-danger btn-sm' data-bs-toggle='modal'>
                <i class='fas fa-trash-alt fa-lg'></i></a>
            </td>
            <td>
                <label class='switch'>
                    <input type='checkbox' data-category-id='" . htmlspecialchars($row["id"]) . "' class='status-toggle' $statusChecked>
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