<div class="modal fade modal-sm" id="updateCategoryModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Update Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form method="POST" id="updateCategoryForm">

                <input type="hidden" name="editId" id="editId">
                    <div class="form-group">
                        <label for="category">Enter Category</label>
                        <input type="text" name="updateCategory" id="updateCategory" class="form-control"
                            placeholder="Enter Category">
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-success" value="Update" id="updateCategoryBtn">
                </form>
            </div>
        </div>
    </div>
</div>