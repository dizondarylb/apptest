function deleteItem(id, dataTable) {
    const url = `${baseUrl}${pathUrl}/delete`;
    const method = 'POST';
    const data = {
        'id': id,
    };

    ajaxRequest(
        url, 
        method, 
        data, 
        function (response) {
            showToast(response.message);
            if(response.status) {
                dataTable.ajax.reload();
            }
        },
        function () {
            showToast(`An error occurred during deleting ${pathUrl}/s.`);
        },
    );
}

function setConfirmTimeOut(data) {
    const modal = $('#confirmTimeOutModal');

    modal.modal('show');
    const employee_time_record_id = $('#confirmTimeOutModal #employee_time_record_id');
    const employee_qr_code = $('#confirmTimeOutModal #employee_qr_code');
    const employee_name = $('#confirmTimeOutModal #employee_name');
    const employee_time_in = $('#confirmTimeOutModal #employee_time_in');

    employee_time_record_id.val(data.id);
    employee_qr_code.val(data.qr_code);
    employee_name.val(`${data.first_name} ${data.last_name}`);
    const time_in = data.time_in ? datetimeUtcToLocal(data.time_in) : null;
    employee_time_in.val(time_in);
}

function scanQR(qr_code, dataTable) {
    const modal = $('#scanQRModal');

    const url = baseUrl + 'employee-time-records/create';
    const method = 'POST';
    const data = {
        'qr_code': qr_code,
    };

    ajaxRequest(
        url, 
        method, 
        data, 
        function (response) {
            showToast(response.message);
            modalHide(modal);

            if(response.data) {
                setConfirmTimeOut(response.data);
            } else if (response.status) {
                dataTable.ajax.reload();
            }
        },
        function () {
            showToast("An error occurred during time in via qr code.");
            modalHide(modal);
        },
    );
}

async function initQRScanner(dataTable) {
    const codeReader = new ZXingBrowser.BrowserQRCodeReader();
    const videoInputDevices = await ZXingBrowser.BrowserCodeReader.listVideoInputDevices();

    const selectedDeviceId = videoInputDevices[0].deviceId;
    const previewElem = document.querySelector('#preview');

    const controls = await codeReader.decodeFromVideoDevice(selectedDeviceId, previewElem, (result, error, controls) => {
        if (result) {
            if(result.text) {
                scanQR(result.text, dataTable);
                controls.stop();
            }
        }
        if (error && error != 'e') {
            console.log('Error decoding QR code:', error);
        }
    });

    const scanQRModal = document.getElementById('scanQRModal')
    scanQRModal.addEventListener('hidden.bs.modal', event => {
        controls.stop();
    })
    // setTimeout(() => controls.stop(), 60000);
}

function initEmployee() {
    const employeesTable = $('#employeesTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 10,
        paging: true,
        ajax: {
            url: baseUrl + 'employees/read',
            type: 'POST',
        },
        columnDefs: [
            { targets: [0], sortable: false }
        ],
        columns: [
            { 
                data: null,
                render: function (data, type, row) {
                    return '<input type="checkbox" data-id="' + data.id + '" class="form-check-input cursor-pointer">';
                }
            },
            { data: 'qr_code' },
            { data: 'first_name' },
            { data: 'last_name' },
            { 
                data: 'user_name',
                render: function (data) {
                    return data == null ? "<del>Deleted User<del>" : data;
                }
            },
            { 
                data: 'datetime_added',
                render: function (data) {
                    return datetimeUtcToLocal(data);
                }
            },
            { 
                data: 'datetime_updated',
                render: function (data) {
                    return datetimeUtcToLocal(data);
                }
            },
            { 
                data: null,
                render: function (data, type, row) {
                    const updateBtn = '<button class="btn btn-info update-btn" data-id="' + data.id + '" data-first-name="' + data.first_name + '" data-last-name="' + data.last_name + '" data-qr-code="' + data.qr_code + '">Update</button> ';
                    const downloadQRBtn = '<button class="btn btn-warning download-qr-btn" data-qr-code="' + data.qr_code + '">Download QR</button> ';
                    const deleteBtn = '<button class="btn btn-danger delete-btn" data-id="' + data.id + '">Delete</button> ';

                    return updateBtn + downloadQRBtn + deleteBtn;
                }
            }
        ],
        order: [[ 6, "desc" ]],
    });

    employeesTable.on('click', '.update-btn, .delete-btn, .download-qr-btn', function (e) {
        e.preventDefault();

        var id = $(this).data('id');
        var qr_code = $(this).data('qr-code');

        if ($(this).hasClass('update-btn')) {
            var first_name = $(this).data('first-name');
            var last_name = $(this).data('last-name');

            $('#updateEmployeeModal #employee_id').val(id);
            $('#updateEmployeeModal #update_qr_code').val(qr_code);
            $('#updateEmployeeModal #update_first_name').val(first_name);
            $('#updateEmployeeModal #update_last_name').val(last_name);
            $('#updateEmployeeModal').modal('show');

        } else if ($(this).hasClass('download-qr-btn')) {
            if (qr_code) {
                $("#qrcode").attr('data-qr-code', qr_code);
                $("#qrcode").html('');

                new QRCode(document.getElementById("qrcode"), { 
                    text: qr_code,
                    width: 256,
                    height: 256,
                    correctLevel: QRCode.CorrectLevel.H
                });
                $("#downloadQRModal").modal('show');
            } else {
                showToast('Employee No. is empty!');
            }

        } else if ($(this).hasClass('delete-btn')) {
            var id = $(this).data('id');
            deleteItem(id, employeesTable);
        }
    });

    $('#checkAll').on('change', function () {
        $(':checkbox', employeesTable.rows().nodes()).prop('checked', this.checked);
    });

    $('#deleteSelectedEmployee').on('click', function (e) {
        e.preventDefault();

        var idSelected = [];
        $(':checkbox:checked', employeesTable.rows().nodes()).each(function () {
            var row = $(this).closest('tr');
            var rowData = employeesTable.row(row).data();
            idSelected.push(rowData.id);
        });

        if(idSelected.length > 0) {
            deleteItem(idSelected, employeesTable)
        } else {
            showToast('No employee selected.');
        }
    });

    $('#createEmployeeForm').submit(function (e) {
        e.preventDefault();

        const modal = $('#createEmployeeModal');
        const qr_code = $('#createEmployeeModal #qr_code');
        const first_name = $('#createEmployeeModal #first_name');
        const last_name = $('#createEmployeeModal #last_name');

        const url = $(this).attr('action');
        const method = $(this).attr('method');
        const data = {
            'qr_code': qr_code.val(),
            'first_name': first_name.val(),
            'last_name': last_name.val(),
        };

        ajaxRequest(
            url, 
            method, 
            data, 
            function (response) {
                showToast(response.message);
                if(response.status) {
                    modalHide(modal, [qr_code, first_name, last_name]);
                    employeesTable.ajax.reload();
                }
            },
            function () {
                modalHide(modal, [qr_code, first_name, last_name]);
                showToast("An error occurred during creating employee.");
            },
        );
    });

    $('#updateEmployeeForm').submit(function (e) {
        e.preventDefault();

        const modal = $('#updateEmployeeModal');
        const employee_id = $('#updateEmployeeModal #employee_id');
        const qr_code = $('#updateEmployeeModal #update_qr_code');
        const first_name = $('#updateEmployeeModal #update_first_name');
        const last_name = $('#updateEmployeeModal #update_last_name');

        const url = $(this).attr('action');
        const method = $(this).attr('method');
        const data = {
            'id': employee_id.val(),
            'qr_code': qr_code.val(),
            'first_name': first_name.val(),
            'last_name': last_name.val(),
        };

        ajaxRequest(
            url, 
            method, 
            data, 
            function (response) {
                showToast(response.message);
                if(response.status) {
                    modalHide(modal, [employee_id, qr_code, first_name, last_name]);
                    employeesTable.ajax.reload();
                }
            },
            function () {
                modalHide(modal, [employee_id, qr_code, first_name, last_name]);
                showToast("An error occurred during updating employee.");
            },
        );
    });

    $('#downloadQRCode').on('click', function(){
        var qr_code = $('#qrcode').data('qr-code');

        const qrCodeDiv = document.getElementById("qrcode");
        const qrCodeDataURL = qrCodeDiv.querySelector("canvas").toDataURL();

        const downloadLink = document.createElement("a");
        downloadLink.href = qrCodeDataURL;

        downloadLink.download = removeSpecialChar(qr_code) + ".png";
        document.body.appendChild(downloadLink);
        downloadLink.click();

        document.body.removeChild(downloadLink);
    });
}

function initEmployeeTimeRecord() {
    const employeeTimeRecordsTable = $('#employeeTimeRecordsTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 10,
        paging: true,
        ajax: {
            url: baseUrl + 'employee-time-records/read',
            type: 'POST',
        },
        columnDefs: [
            { targets: [0], sortable: false }
        ],
        columns: [
            { data: 'qr_code' },
            { 
                data: null,
                render: function (data) {
                    return `${data.first_name} ${data.last_name}`;
                }
            },
            { 
                data: 'user_name',
                render: function (data) {
                    return data == null ? "<del>Deleted User<del>" : data;
                }
            },
            { 
                data: 'date_added',
                render: function (data) {
                    return datetimeUtcToLocal(data);
                }
            },
            { 
                data: 'time_in',
                render: function (data) {
                    return datetimeUtcToLocal(data);
                }
            },
            { 
                data: 'time_out',
                render: function (data) {
                    return data ? datetimeUtcToLocal(data) : null;
                }
            }
        ],
        order: [[ 3, "desc" ]],
    });

    $("#scanQRCode").on('click', function(e) {
        e.preventDefault();
        initQRScanner(employeeTimeRecordsTable);

        $('#scanQRModal').modal('show');
    });

    $('#inputTimeInForm').submit(function (e) {
        e.preventDefault();

        const modal = $('#inputTimeInModal');
        const qr_code = $('#inputTimeInModal #qr_code');

        const url = $(this).attr('action');
        const method = $(this).attr('method');
        const data = {
            'qr_code': qr_code.val(),
        };

        ajaxRequest(
            url, 
            method, 
            data, 
            function (response) {
                showToast(response.message);
                modalHide(modal);

                if(response.data) {
                    setConfirmTimeOut(response.data);
                } else if (response.status) {
                    employeeTimeRecordsTable.ajax.reload();
                }
            },
            function () {
                showToast("An error occurred during time in via employee no.");
                modalHide(modal, [qr_code]);
            },
        );
    });

    $('#confirmTimeOutForm').submit(function (e) {
        e.preventDefault();

        const modal = $('#confirmTimeOutModal');
        const employee_time_record_id = $('#confirmTimeOutModal #employee_time_record_id');
        const qr_code = $('#confirmTimeOutModal #employee_qr_code');
        const employee_name = $('#confirmTimeOutModal #employee_name');
        const employee_time_in = $('#confirmTimeOutModal #employee_time_in');

        const url = $(this).attr('action');
        const method = $(this).attr('method');
        const data = {
            'id': employee_time_record_id.val(),
        };

        ajaxRequest(
            url, 
            method, 
            data, 
            function (response) {
                showToast(response.message);
                if(response.status) {
                    modalHide(modal, [employee_time_record_id, qr_code, employee_name, employee_time_in]);
                    employeeTimeRecordsTable.ajax.reload();
                }
            },
            function () {
                showToast("An error occurred during confirm time out.");
                modalHide(modal, [employee_time_record_id, qr_code, employee_name, employee_time_in]);
            },
        );
    });
}

function initUser() {
    const usersTable = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 10,
        paging: true,
        ajax: {
            url: baseUrl + 'users/read',
            type: 'POST',
        },
        columnDefs: [
            { targets: [0], sortable: false }
        ],
        columns: [
            { 
                data: null,
                render: function (data, type, row) {
                    return '<input type="checkbox" data-id="' + data.id + '" class="form-check-input cursor-pointer">';
                }
            },
            { data: 'user_name' },
            { 
                data: 'user_type',
                render: function (data) {
                    return userType[data] ? userType[data] : 'Invalid ' + data;
                }
            },
            { 
                data: 'datetime_added',
                render: function (data) {
                    return datetimeUtcToLocal(data);
                }
            },
            { 
                data: 'datetime_modified',
                render: function (data) {
                    return datetimeUtcToLocal(data);
                }
            },
            { 
                data: null,
                render: function (data, type, row) {
                    return '<button class="btn btn-info update-btn" data-id="' + data.id + '" data-user-name="' + data.user_name + '" data-user-type="' + data.user_type + '">Update</button> <button class="btn btn-danger delete-btn" data-id="' + data.id + '">Delete</button>';
                }
            }
        ],
        order: [[ 4, "desc" ]],
    });

    usersTable.on('click', '.update-btn, .delete-btn', function (e) {
        e.preventDefault();

        var id = $(this).data('id');

        if ($(this).hasClass('update-btn')) {
            var user_name = $(this).data('user-name');
            var user_type = $(this).data('user-type');

            $('#updateUserModal #user_id').val(id);
            $('#updateUserModal #update_user_name').val(user_name);
            $('#updateUserModal #update_user_type').val(user_type);
            $('#updateUserModal').modal('show');

        } else if ($(this).hasClass('delete-btn')) {
            deleteItem(id, usersTable);
        }
    });

    $('#checkAll').on('change', function () {
        $(':checkbox', usersTable.rows().nodes()).prop('checked', this.checked);
    });

    $('#deleteSelectedUser').on('click', function (e) {
        e.preventDefault();

        var idSelected = [];
        $(':checkbox:checked', usersTable.rows().nodes()).each(function () {
            var row = $(this).closest('tr');
            var rowData = usersTable.row(row).data();
            idSelected.push(rowData.id);
        });

        if(idSelected.length > 0) {
            deleteItem(idSelected, usersTable)
        } else {
            showToast('No user selected.');
        }
    });

    $('#createUserForm').submit(function (e) {
        e.preventDefault();

        const modal = $('#createUserModal');
        const user_name = $('#createUserModal #user_name');
        const user_password = $('#createUserModal #user_password');
        const confirm_password = $('#createUserModal #confirm_password');
        const user_type = $('#createUserModal #user_type');

        const url = $(this).attr('action');
        const method = $(this).attr('method');
        const data = {
            'user_name': user_name.val(),
            'user_password': user_password.val(),
            'confirm_password': confirm_password.val(),
            'user_type': user_type.val(),
        };

        ajaxRequest(
            url, 
            method, 
            data, 
            function (response) {
                showToast(response.message);
                if (response.status) {
                    modalHide(modal, [user_name, user_password, confirm_password, user_type]);
                    user_password.parent().parent().children('.generated-password').html('');
                    usersTable.ajax.reload();
                }
            },
            function () {
                modalHide(modal, [user_name, user_password, confirm_password, user_type]);
                showToast("An error occurred during creating user.");
            },
        );
    });

    $('#updateUserForm').submit(function (e) {
        e.preventDefault();

        const modal = $('#updateUserModal');
        const user_id = $('#updateUserModal #user_id');
        const user_name = $('#updateUserModal #update_user_name');
        const user_password = $('#updateUserModal #update_user_password');
        const confirm_password = $('#createUserModal #update_confirm_password');
        const user_type = $('#updateUserModal #update_user_type');

        const url = $(this).attr('action');
        const method = $(this).attr('method');
        const data = {
            'id': user_id.val(),
            'user_name': user_name.val(),
            'user_password': user_password.val(),
            'confirm_password': confirm_password.val(),
            'user_type': user_type.val(),
        };

        ajaxRequest(
            url, 
            method, 
            data, 
            function (response) {
                showToast(response.message);
                if(response.status) {
                    modalHide(modal, [user_id, user_name, user_password, confirm_password, user_type]);
                    user_password.parent().parent().children('.generated-password').html('');
                    usersTable.ajax.reload();
                }
            },
            function () {
                showToast("An error occurred during updating user.");
                modalHide(modal, [user_id, user_name, user_password, confirm_password, user_type]);
            },
        );
    });

    $('.generate-password').on('click', function(e){
        const password = generatePassword(10);

        const parent = $(this).parent();
        if(parent.children("#user_password")) {
            parent.children("#user_password").val(password);
            parent.parent().children(".generated-password").html(`<small>Generated Password: <strong>${password}</strong></small>`);
        } else if(parent.children("#user_password")) {
            parent.children("#update_user_password").val(password);
            parent.parent().children(".generated-password").html(`<small>Generated Password: <strong>${password}</strong></small>`);
        }
    });
}

$(document).ready( function () {
    if (pathUrl == 'employees') {
        initEmployee();
    } else if (pathUrl == 'employee-time-records') {
        initEmployeeTimeRecord();
    } else if (pathUrl == 'users') {
        initUser();
    }
});