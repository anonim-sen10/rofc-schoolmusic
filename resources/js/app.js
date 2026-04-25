import "./bootstrap";

function initApp() {
    const siteHeader = document.querySelector(".site-header");
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

    if (siteHeader) {
        const updateHeaderState = () => {
            siteHeader.classList.toggle("is-scrolled", window.scrollY > 12);
        };

        updateHeaderState();
        window.addEventListener("scroll", updateHeaderState, { passive: true });
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initApp);
} else {
    initApp();
}
