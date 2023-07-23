$(document).ready(function () {
    $('#loginForm').submit(function (e) {
        e.preventDefault();

        const url = $(this).attr('action');
        const method = $(this).attr('method');
        const data = {
            'user_name': $('#loginForm #user_name').val(),
            'user_password': $('#loginForm #user_password').val(),
        };

        ajaxRequest(
            url, 
            method, 
            data, 
            function (response) {
                showToast(response.message);
                if (response.status) {
                    window.location.href = baseUrl + '/dashboard';
                }
            },
            function () {
                showToast("An error occurred during login.");
            },
        );
    });
});