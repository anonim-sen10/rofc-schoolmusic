document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.querySelector("[data-portal-sidebar]");
    const toggle = document.querySelector("[data-portal-toggle]");

    if (sidebar && toggle) {
        toggle.addEventListener("click", () => {
            sidebar.classList.toggle("open");
        });
    }
});
