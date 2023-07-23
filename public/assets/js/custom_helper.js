(() => {
    'use strict'
    const tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl)
    })
})()

const userType = {
    1: 'Super Admin',
    2: 'Admin',
};

function showToast(message) {
    const toast = $('#toast');
    const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toast);

    toast.children(".toast-body").html(message);
    toastBootstrap.show();
}

function clearFields(fields) {
    if (fields && fields.length > 0) {
        $.each(fields, function(index, item) {
            item.val('');
        });
    }
}

function modalHide(modal, fields) {
    modal.modal('hide');

    // with clear fields
    clearFields(fields);
}

function datetimeUtcToLocal(data) {
    const utcDate = new Date(data);
    var timeZoneOffsetMinutes = utcDate.getTimezoneOffset();
    var localTime = new Date(utcDate.getTime() - (timeZoneOffsetMinutes * 60 * 1000));
    return localTime.toLocaleString();
}

function ajaxRequest(url, method, data, successCallback, errorCallback) {
    return $.ajax({
        url: url,
        type: method,
        data: data,
        dataType: 'json',
        success: function (response) {
            if (typeof successCallback === 'function') {
                successCallback(response);
            }
        },
        error: function () {
            if (typeof errorCallback === 'function') {
                errorCallback()
            }
        }
    });
}

function generatePassword(length) {
    const lowercaseChars = 'abcdefghijklmnopqrstuvwxyz';
    const uppercaseChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const numberChars = '0123456789';
    const specialChars = '!@#$%^&*()-_=+[]{}|;:,.<>?';
  
    const allChars = lowercaseChars + uppercaseChars + numberChars + specialChars;
  
    let password = '';
    let hasLowercase = false;
    let hasUppercase = false;
    let hasNumber = false;
    let hasSpecialChar = false;
    let passedAllRequirements = false;
  
    while (password.length < length || passedAllRequirements == false) {
        const randomIndex = Math.floor(Math.random() * allChars.length);
        const c = allChars.charAt(randomIndex);
    
        if (lowercaseChars.includes(c) && !hasLowercase) {
            hasLowercase = true;
        } else if (uppercaseChars.includes(c) && !hasUppercase) {
            hasUppercase = true;
        } else if (numberChars.includes(c) && !hasNumber) {
            hasNumber = true;
        } else if (specialChars.includes(c) && !hasSpecialChar) {
            hasSpecialChar = true;
        }
        
        if(hasLowercase == true && hasUppercase == true && hasNumber == true && hasSpecialChar == true) {
            passedAllRequirements = true;
        }

        password += c;
    }
  
    return password;
}

function removeSpecialChar(data) {
    const specialChars = /[@#?!$%^&*()_+|~=`{}\[\]:";'<>?,.\/]/g;
    return data.replace(specialChars, '');
}