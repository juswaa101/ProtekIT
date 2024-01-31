<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoleModalLabel">Add Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addRoleForm">
                <div class="modal-body">
                    <label class="form-label">Role Name: </label>
                    <input type="text" class="form-control" id="roleName" name="name"
                        placeholder="Enter Role Name">
                    <span class="text-danger" id="roleNameError"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveRoleBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
