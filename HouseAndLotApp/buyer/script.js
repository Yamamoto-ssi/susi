
document.addEventListener("DOMContentLoaded", function () {

    // Get the current HTML filename
    let currentPage = window.location.pathname.split("/").pop();

    if (currentPage === "") {
        currentPage = "index.html";
    }

    // Automatically highlight the active navigation link
    document.querySelectorAll(".nav-links a").forEach(function (link) {
        let linkPage = link.getAttribute("href");

        if (linkPage === currentPage) {
            link.classList.add("active");
        } else {
            link.classList.remove("active");
        }
    });

});
