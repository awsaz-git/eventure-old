var account_type = 0;
//0 for individual
//1 for organizer

const passwordField = document.querySelector('.password');
const phoneNumberField = document.querySelector('#phoneNumber');
const capitalization = document.querySelector('.capitalization');
const specialChars = document.querySelector('.specialChars');
const length = document.querySelector('.length');

var isCapitalized = false;
var isSpecialChars = false;
var isLength = false;
var isInvalidPhoneNumber = false;

passwordField.addEventListener("input", function () {
    const password = passwordField.value;

    if (password.length >= 8 && password.length <= 16) {
        length.src = 'assets/icons/correct.png';
        isCapitalized = true;
    } else {
        length.src = 'assets/icons/wrong.png';
        isCapitalized = false;
    }

    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
        specialChars.src = 'assets/icons/correct.png';
        isSpecialChars = true;
    } else {
        specialChars.src = 'assets/icons/wrong.png';
        isSpecialChars = false;
    }

    if (/[A-Z]/.test(password) && /[a-z]/.test(password)) {
        capitalization.src = 'assets/icons/correct.png';
        isLength = true;
    } else {
        capitalization.src = 'assets/icons/wrong.png';
        isLength = false;
    }
});

phoneNumberField.addEventListener('input', function () {
    if (phoneNumberField.value.substring(0, 2) !== "07") {
        phoneNumberField.value = "07";
    }
    if (phoneNumberField.value.length != 10) {
        isInvalidPhoneNumber = true;
    } else {
        isInvalidPhoneNumber = false;
    }
});

function navigate(currentStep, nextStep) {
    document.getElementById(currentStep).style.display = 'none';
    document.getElementById(nextStep).style.display = 'flex';
}

function isEmpty() {
    const inputField = document.getElementsByClassName('textField2');
    for (let i = 0; i < inputField.length; i++) {
        if (inputField[i].value === "" || /^\s*$/.test(inputField.value)) {
            return true;
        }
    }
    return false;
}

document.querySelector('#signupButton').addEventListener("click", function () {
    if (isEmpty()) {
        window.alert('please fill all fields');
    } else if (!isCapitalized || !isSpecialChars || !isLength) {
        window.alert('password is not strong enough or too long');
    } else if (isInvalidPhoneNumber) {
        window.alert('phone number too long');
    } else {
        document.querySelector('#signupForm').submit();
    }
}
);

switch (error) {
    case 1:
        window.alert('username already in use');
        window.location.href = 'signup.php';
        break;
    case 2:
        window.alert('email already in use');
        window.location.href = 'signup.php';
        break;
    case 3:
        window.alert('phone number already in use');
        window.location.href = 'signup.php';
        break;
    case 0:
        window.alert('account created successfully');
        window.location.href = 'login.php';
        break;
}

const fileInput = document.querySelector('#verificationFile');
const label = document.querySelector('#upload');

fileInput.addEventListener("change", function () {
    if (fileInput.files.length > 0) {
        label.innerHTML = fileInput.files[0].name;
    }
});