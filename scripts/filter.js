const category = document.querySelector('#category');
const subcategory = document.querySelector('#subcategory');
const url = new URL(window.location.href);

window.addEventListener("load", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const selectedCategory = urlParams.get('category');
    if (selectedCategory) {
        category.value = selectedCategory;
    }

    console.log(category.value);
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

    const selectedSubCategory = urlParams.get('subcategory');
    if (selectedSubCategory) {
        subcategory.value = selectedSubCategory;
    }
});

category.addEventListener("input", function () {
    url.searchParams.set("category", category.value);
    url.searchParams.delete("subcategory");
    window.history.pushState({}, "", url);
    location.reload();
    category.value = value;
});

subcategory.addEventListener("input", function () {
    console.log(subcategory.value);

    url.searchParams.set("subcategory", subcategory.value);
    window.history.pushState({}, "", url);
    location.reload();
});