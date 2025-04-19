<!-- <div class="modal fade modal-xl modal-dialog-scrollable" id="addPostModal"> -->
<div class="modal-dialog modal-fullscreen-sm-down" id="addPostModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Add Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" id="addPostForm">
                    <div class="form-group">
                        <label>Post Content</label>
                        <!-- <div id="editor-container-add"></div> -->
                        <textarea id="postContent" name="content"></textarea>  
                    </div>
                    <div class="form-group">
                        <label for="title">Post Title</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Enter title">
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <select name="category" id="category" class="form-select">
                            <option selected disabled></option>
                            <?php echo getCategoriesOptions($conn); ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tags">Tags</label>
                        <select class="form-multi-select" id="tags-add" multiple data-placeholder="Select tags..."
                            name="tags[]">
                            <?php echo getTagsOptions($conn); ?>
                        </select>
                    </div>

                    <div class="form-group mt-4">
                        <label for="meta_description">Meta Description</label>
                        <input type="text" name="meta_description" id="meta_description" class="form-control"
                            placeholder="Meta description">
                    </div>

                    <div class="form-group">
                        <label for="meta_keywords">Meta Keywords</label>
                        <input type="text" name="meta_keywords" id="meta_keywords" class="form-control"
                            placeholder="Enter meta keywords">
                    </div>

                    <div class="form-group">
                        <label for="thumb_image">Thumbnail Image</label>
                        <input type="file" name="thumb_image" id="thumb_image" class="form-control">
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-success" value="Add" id="addPostBtn">
                </form>
            </div>
        </div>
    </div>
</div>