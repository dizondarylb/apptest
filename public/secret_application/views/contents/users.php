<div class="container">
    <div class="py-4">
        <h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3">List of Users</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
            Add New User
        </button>
        <button id="deleteSelectedUser" type="button" class="btn btn-danger">
            Delete Selected User
        </button>
    </div>
    <table id="usersTable">
        <thead>
            <tr>
                <th><input type="checkbox" id="checkAll" class="form-check-input cursor-pointer"></th>
                <th>Username</th>
                <th>User Type</th>
                <th>Datetime Added</th>
                <th>Datetime Modified</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    
</div>

<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="createUserForm" action="<?php echo site_url('users/create') ?>" method="POST">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createUserModalLabel">Create User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-3" id="user_name" placeholder="Username">
                    <label for="user_name">Username</label>
                </div>
                <div class="mb-3 generated-password"></div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="user_password" placeholder="Password" aria-label="Password" aria-describedby="user_password">
                    <button class="btn btn-outline-secondary generate-password" type="button">Generate</button>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control rounded-3" id="confirm_password" placeholder="Confirm Password">
                    <label for="confirm_password">Confirm Password</label>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select" id="user_type" aria-label="Select User Type">
                        <option selected></option>
                        <option value="1">Super Admin</option>
                        <option value="2">Admin</option>
                    </select>
                    <label for="user_type">User Type</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Create</button>
            </div>
        </div>
    </form>
  </div>
</div>

<div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="updateUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="updateUserForm" action="<?php echo site_url('users/update') ?>" method="POST">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="updateUserModalLabel">Update User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="user_id">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-3" id="update_user_name" placeholder="Username">
                    <label for="update_user_name">Username</label>
                </div>
                <div class="mb-3 generated-password"></div>          
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="update_user_password" placeholder="Password" aria-label="Password" aria-describedby="user_password">
                    <button class="btn btn-outline-secondary generate-password" type="button">Generate</button>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control rounded-3" id="update_confirm_password" placeholder="Confirm Password">
                    <label for="update_confirm_password">Confirm Password</label>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select" id="update_user_type" aria-label="Select User Type">
                        <option selected></option>
                        <option value="1">Super Admin</option>
                        <option value="2">Admin</option>
                    </select>
                    <label for="update_user_type">User Type</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Save Changes</button>
            </div>
        </div>
    </form>
  </div>
</div>