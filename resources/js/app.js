import "./bootstrap";

document.addEventListener("DOMContentLoaded", () => {
    const menuToggle = document.querySelector("[data-menu-toggle]");
    const menu = document.querySelector("[data-menu]");

    if (menuToggle && menu) {
        menuToggle.addEventListener("click", () => {
            const isOpen = menu.classList.toggle("open");
            menuToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
        });
    }

    const revealTargets = document.querySelectorAll(
        ".section, .page-banner, .hero-section",
    );
    revealTargets.forEach((element) => element.setAttribute("data-reveal", ""));

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("visible");
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12 },
    );

    revealTargets.forEach((element) => observer.observe(element));
});
