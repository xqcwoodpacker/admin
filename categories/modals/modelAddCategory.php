<div class="modal fade modal-sm" id="addCategoryModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form method="POST" id="addCategoryForm">

                    <div class="form-group">
                        <label for="category">Enter Category</label>
                        <input type="text" name="category" id="category" class="form-control"
                            placeholder="Enter Category">
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-success" value="Add" id="addCategoryBtn">
                </form>
            </div>
        </div>
    </div>
</div>