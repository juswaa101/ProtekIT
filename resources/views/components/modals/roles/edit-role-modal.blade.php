<!-- Add Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel">Update Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editRoleForm">
                <div class="modal-body">
                    <input type="hidden" id="editRoleId" name="roleId">
                    <label class="form-label">Role Name: </label>
                    <input type="text" class="form-control" id="editRoleName" name="name"
                        placeholder="Enter Role Name">
                    <span class="text-danger" id="editRoleNameError"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateRoleBtn">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
