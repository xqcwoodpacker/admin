<div class="modal fade modal-sm" id="addTagModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Add Tag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form method="POST" id="addTagForm">

                    <div class="form-group">
                        <label for="tag">Enter Tag</label>
                        <input type="text" name="tag" id="tag" class="form-control" placeholder="Enter Tag">
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-success" value="Add" id="addTagBtn">
                </form>
            </div>
        </div>
    </div>
</div>