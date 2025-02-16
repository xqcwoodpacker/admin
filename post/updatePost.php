<?php

// print_r($_POST);
// exit;

session_start();
if (!isset($_SESSION['admin'])) {
    header('location:../login.php');
    exit;
}

require '../db.php';

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//UDF

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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
    exit;
}

if (isset($_POST['editId']) && is_numeric($_POST['editId'])) {
    $id = $_POST['editId'];
} else {
    echo json_encode(["status" => "error", "message" => "Invalid post ID!"]);
    exit;
}

$sql = "SELECT * FROM posts WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
    } else {
        echo json_encode(["status" => "error", "message" => "Post not found!"]);
        exit;
    }
} else {
    echo json_encode(["status" => "error", "message" => "Database error occurred!"]);
    exit;
}

if (($_POST['editContent']) == '<p><br></p>') {
    echo json_encode(["status" => "error", "message" => "Content is required"]);
    exit;
} else {
    $content = $_POST['editContent'];
}

if (!empty($_POST['editTitle'])) {
    $title = $_POST['editTitle'];
} else {
    echo json_encode(["status" => "error", "message" => "Title is required"]);
    exit;
}

if (!empty($_POST['editCategory'])) {
    $category = $_POST['editCategory'];
} else {
    echo json_encode(["status" => "error", "message" => "Category is required"]);
    exit;
}

if (!empty($_POST['editTags'])) {
    $tags = $_POST['editTags'];
} else {
    echo json_encode(["status" => "error", "message" => "Tags are required"]);
    exit;
}

if ($_FILES['edit_thumb_image']['error'] == UPLOAD_ERR_OK) {

    $old_image = $post['thumb_img'];

    if (!empty($old_image) && file_exists($old_image)) {
        unlink($old_image);  // Delete the old image file from the server
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/img'];
    if (!in_array($_FILES['edit_thumb_image']['type'], $allowedTypes)) {
        echo json_encode(["status" => "error", "message" => "Invalid image type. Only JPG and PNG files are allowed."]);
        exit;
    } elseif (($imageInfo = getimagesize($_FILES["edit_thumb_image"]["tmp_name"])) === false) {
        echo json_encode(["status" => "error", "message" => "The uploaded file is not a valid image."]);
        exit;
    }

    $fileSize = $_FILES["edit_thumb_image"]["size"]; // Define file size
    if ($fileSize > 5000000) {
        echo json_encode(["status" => "error", "message" => "File is too large. Maximum size allowed is 5MB."]);
        exit;
    }

    $target_dir = "../uploads/";
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($_FILES["edit_thumb_image"]["name"], PATHINFO_EXTENSION));

    // Generate a new filename using a timestamp and the correct extension
    $new_filename = time() . '_' . basename($_FILES["edit_thumb_image"]["name"]);
    $target_file = $target_dir . $new_filename;

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["edit_thumb_image"]["tmp_name"], $target_file)) {
        // echo "The file " . htmlspecialchars(basename($_FILES["thumb_image"]["name"])) . " has been uploaded.";
        $thumb_img = $target_file;
    } else {
        echo json_encode(["status" => "error", "message" => "File uploading error"]);
        exit;
    }
} else {
    $thumb_img = $post['thumb_img'];
}

$slug = createSlug($title);
$meta_description = $_POST['edit_meta_description'];
$meta_keywords = $_POST['edit_meta_keywords'];

$sql = "UPDATE posts SET slug = ?, title = ?, category = ?, content = ?, thumb_img = ?, meta_description = ?, meta_keywords = ? WHERE id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("sssssssi", $slug, $title, $category, $content, $thumb_img, $meta_description, $meta_keywords, $id);

    if ($stmt->execute()) {

        // First, delete existing tag associations for the post
        $deleteTagsSql = "DELETE FROM posts_tags WHERE post_id = ?";
        if ($deleteStmt = $conn->prepare($deleteTagsSql)) {
            $deleteStmt->bind_param("i", $id);
            $deleteStmt->execute();
            $deleteStmt->close();
        }

        // Now, associate new tags with the post
        if (!empty($tags)) {
            if (is_string($tags)) {
                // Split the tag IDs into an array
                $tagIds = explode(',', $tags);
            } elseif (is_array($tags)) {
                // If it's already an array, just use it as is
                $tagIds = $tags;
            }

            foreach ($tagIds as $tagId) {
                $tagId = trim($tagId);

                // Check if the tag ID exists in the tags table
                $checkTagSql = "SELECT id FROM tags WHERE id = ?";
                if ($checkStmt = $conn->prepare($checkTagSql)) {
                    $checkStmt->bind_param("i", $tagId);
                    $checkStmt->execute();
                    $checkStmt->store_result();

                    // If the tag exists, insert the tag association into the posts_tags table
                    if ($checkStmt->num_rows > 0) {
                        $insertPostTagSql = "INSERT INTO posts_tags (post_id, tag_id) VALUES (?, ?)";
                        if ($insertStmt = $conn->prepare($insertPostTagSql)) {
                            $insertStmt->bind_param("ii", $id, $tagId);
                            $insertStmt->execute();
                            $insertStmt->close();
                        }
                    }

                    $checkStmt->close();
                }
            }
        }

        // Close the main statement after all tag operations are done
        $stmt->close();
        echo json_encode(["status" => "success", "message" => "Post Updated successfully"]);
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => "SQL error while updating post."]);
        exit;
    }
} else {
    echo json_encode(["status" => "error", "message" => "SQL error while preparing statement."]);
    exit;
}
