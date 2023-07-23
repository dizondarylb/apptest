<div class="container">
    <div class="py-4">
        <h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3">List of Employees</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEmployeeModal">
            Add New Employee
        </button>
        <button id="deleteSelectedEmployee" type="button" class="btn btn-danger">
            Delete Selected Employee
        </button>
    </div>
    <table id="employeesTable">
        <thead>
            <tr>
                <!-- <th>ID Code</th> -->
                <th><input type="checkbox" id="checkAll" class="form-check-input cursor-pointer"></th>
                <th>Employee No.</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Created By</th>
                <th>Datetime Added</th>
                <th>Datetime Updated</th>
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
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    
</div>

<div class="modal fade" id="createEmployeeModal" tabindex="-1" aria-labelledby="createEmployeeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="createEmployeeForm" action="<?php echo site_url('employees/create') ?>" method="POST">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createEmployeeModalLabel">Create Employee</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-3" id="qr_code" placeholder="Ex. EN-0001">
                    <label for="qr_code">Employee No</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-3" id="first_name" placeholder="First Name">
                    <label for="first_name">First Name</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-3" id="last_name" placeholder="Last Name">
                    <label for="last_name">Last Name</label>
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

<div class="modal fade" id="updateEmployeeModal" tabindex="-1" aria-labelledby="updateEmployeeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="updateEmployeeForm" action="<?php echo site_url('employees/update') ?>" method="POST">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="updateEmployeeModalLabel">Update Employee</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="employee_id">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-3" id="update_qr_code" placeholder="Ex. EN-0001">
                    <label for="update_qr_code">Employee No</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-3" id="update_first_name" placeholder="First Name">
                    <label for="update_first_name">First Name</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-3" id="update_last_name" placeholder="Last Name">
                    <label for="update_last_name">Last Name</label>
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

<div class="modal fade" id="downloadQRModal" tabindex="-1" aria-labelledby="downloadQRModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="downloadQRModalLabel">Download QR Code</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="qrcode"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button id="downloadQRCode" type="button" class="btn btn-success">Download</button>
        </div>
    </div>
  </div>
</div>