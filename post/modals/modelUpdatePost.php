<?php
// var_dump($categories);
// var_dump($tags);
?>
<div class="modal fade modal-xl modal-dialog-scrollable" id="updatePostModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Update Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form method="POST" enctype="multipart/form-data" id="updatePostForm">
                    <input type="hidden" name="editId" id="editId">
                    <div class="form-group">
                        <label>Post Content</label>
                        <div id="editor-container-update"></div>
                        <!-- <input type="hidden" name="editContent" id="editContent"> -->
                        <!-- Hidden field to store Quill content -->

                        <textarea id="editContent" name="editContent"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="editTitle">Post Title</label>
                        <input type="text" name="editTitle" id="editTitle" class="form-control"
                            placeholder="Enter title">
                    </div>

                    <div class="form-group">
                        <label for="edit_slug">Post Slug</label>
                        <input type="text" name="edit_slug" id="edit_slug" class="form-control"
                            placeholder="Enter slug">
                    </div>

                    <div class="form-group">
                        <label for="editCategory">Category</label>
                        <select name="editCategory" id="editCategory" class="form-select">
                            <option selected disabled></option>
                            <?php echo getCategoriesOptions($conn); ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tags-update">Tags</label>
                        <select class="form-multi-select" id="tags-update" multiple data-placeholder="Select tags..."
                            name="editTags[]">
                            <?php echo getTagsOptions($conn); ?>
                        </select>
                    </div>

                    <div class="form-group mt-4">
                        <label for="edit_meta_description">Meta Description</label>
                        <input type="text" name="edit_meta_description" id="edit_meta_description" class="form-control"
                            placeholder="Meta description">
                    </div>

                    <div class="form-group">
                        <label for="edit_meta_keywords">Meta Keywords</label>
                        <input type="text" name="edit_meta_keywords" id="edit_meta_keywords" class="form-control"
                            placeholder="Enter meta keywords">
                    </div>

                    <div class="form-group">
                        <label for="edit_faq">Post FAQ Schema</label>
                        <textarea type="text" name="edit_faq" id="edit_faq" class="form-control"
                            placeholder="Enter FAQ schema"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="edit_thumb_image">Thumbnail Image</label>
                        <input type="file" name="edit_thumb_image" id="edit_thumb_image" class="form-control">
                    </div>
                    <div id="preview_image"></div>

                    <div class="form-group">
                        <label for="edit_alt_tag">Alt Tag</label>
                        <input type="text" name="edit_alt_tag" id="edit_alt_tag" class="form-control"
                            placeholder="Enter Alt Tag">
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-success" value="Update" id="updatePostBtn">
                </form>
            </div>
        </div>
    </div>
</div>