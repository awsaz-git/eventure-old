const category = document.querySelector('#category');
const subcategory = document.querySelector('#subcategory');

category.addEventListener("input", function () {

    if (category.value == 0) {
        subcategory.innerHTML = `
        <option value="0">Subcategory</option>
        `;
    }
    if (category.value == 1) {
        subcategory.innerHTML = `
        <option value="0">Subcategory</option>
        <option value="1.1">Hiking Trips</option>
        <option value="1.2">Camping Trips</option>
        <option value="1.3">City Tours</option>
        <option value="1.4">Marathons</option>
        `;
    }
    if (category.value == 2) {
        subcategory.innerHTML = `
        <option value="0">Subcategory</option>
        <option value="2.1">Volunteering</option>
        `;
    }
    if (category.value == 3) {
        subcategory.innerHTML = `
        <option value="0">Subcategory</option>
        <option value="3.1">Football</option>
        <option value="3.2">Basketball</option>
        <option value="3.3">Volleyball</option>
        <option value="3.4">Competitions</option>
        `;
    }
    if (category.value == 4) {
        subcategory.innerHTML = `
        <option value="0">Subcategory</option>
        <option value="4.1">Arts</option>
        <option value="4.2">Digital Skills</option>
        <option value="4.3">Photography & Videography</option>
        <option value="4.4">Personal Development</option>
        <option value="4.5">Languages</option> -->
        `;
    }
});

const fileInput = document.querySelector('#eventImage');
const label = document.querySelector('#upload');

fileInput.addEventListener("change", function () {
    if (fileInput.files.length > 0) {
        label.innerHTML = fileInput.files[0].name;
    }
});

function isEmpty() {
    const inputField = document.getElementsByClassName('input');
    for (let i = 0; i < inputField.length - 1; i++) {
        if (inputField[i].value === "" || /^\s*$/.test(inputField.value)) {
            return true;
        }
    }
    return false;
}

document.querySelector('#createEventButton').addEventListener("click", function () {
    if (isEmpty()) {
        window.alert('please fill all required fields');
    } else {
        document.querySelector('#createEventForm').submit();
    }
}
);