// Add this JavaScript code within <script> tags in your HTML file or in a separate JS file

document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (event) {
        const searchKey = form.elements["searchKey"].value;
        const checkboxes = document.querySelectorAll("input[type='checkbox']");
    });
});
