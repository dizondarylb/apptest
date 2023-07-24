<div class="container">
    <div class="py-4">
        <h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3">Employee Time Records</h1>
        <button type="button" class="btn btn-primary" id="scanQRCode">
            Scan QR Code
        </button>
        <button type="button" id="inputEmployeeNo" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#inputTimeInModal">
            Input Employee No.
        </button>
    </div>
    <table id="employeeTimeRecordsTable">
        <thead>
            <tr>
                <th>Employee No.</th>
                <th>Employee Name</th>
                <th>Admin Username</th>
                <th>Date Added</th>
                <th>Time In</th>
                <th>Time Out</th>
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


<div class="modal fade modal-lg" id="scanQRModal" tabindex="-1" aria-labelledby="scanQRModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="scanQRModalLabel">Time In via QR Code</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <video id="preview"></video>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>


<div class="modal fade" id="inputTimeInModal" tabindex="-1" aria-labelledby="inputTimeInModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="inputTimeInForm" action="<?php echo site_url('employee-time-records/create') ?>" method="POST">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="inputTimeInModalLabel">Time In via Employee No.</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-3" id="qr_code" placeholder="Ex. EN-0001">
                    <label for="qr_code">Employee No</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Time In</button>
            </div>
        </div>
    </form>
  </div>
</div>

<div class="modal fade" id="confirmTimeOutModal" tabindex="-1" aria-labelledby="confirmTimeOutModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="confirmTimeOutForm" action="<?php echo site_url('employee-time-records/update') ?>" method="POST">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="confirmTimeOutModalLabel">Confirm to Time Out</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="employee_time_record_id">
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="employee_qr_code" value="" aria-label="Employee No." readonly>
                    <label for="employee_qr_code">Employee No.</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="employee_name" value="" aria-label="Employee Name" readonly>
                    <label for="employee_name">Employee Name</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="employee_time_in" value="" aria-label="Time In" readonly>
                    <label for="employee_time_in">Time In</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Time Out</button>
            </div>
        </div>
    </form>
  </div>
</div>