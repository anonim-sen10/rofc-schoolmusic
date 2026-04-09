document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.querySelector("[data-portal-sidebar]");
    const toggle = document.querySelector("[data-portal-toggle]");
    const root = document.documentElement;
    const searchInput = document.querySelector("[data-global-search]");

    if (sidebar && toggle) {
        const applyDesktopState = () => {
            if (window.matchMedia("(max-width: 900px)").matches) {
                root.classList.remove("sidebar-collapsed");
                return;
            }

            const savedState = localStorage.getItem("portal.sidebar.collapsed");
            if (savedState === "true") {
                root.classList.add("sidebar-collapsed");
            }
        };

        applyDesktopState();

        toggle.addEventListener("click", () => {
            if (window.matchMedia("(max-width: 900px)").matches) {
                sidebar.classList.toggle("open");
                return;
            }

            root.classList.toggle("sidebar-collapsed");
            localStorage.setItem(
                "portal.sidebar.collapsed",
                String(root.classList.contains("sidebar-collapsed")),
            );
        });

        window.addEventListener("resize", applyDesktopState);
    }

    if (searchInput) {
        searchInput.addEventListener("input", (event) => {
            const keyword = String(event.target.value || "")
                .trim()
                .toLowerCase();
            const searchableBlocks =
                document.querySelectorAll("[data-searchable]");
            const blocks = searchableBlocks.length
                ? searchableBlocks
                : document.querySelectorAll(
                      "main section.card, .portal-main section.card",
                  );

            blocks.forEach((block) => {
                const text = String(block.textContent || "").toLowerCase();
                block.style.display =
                    keyword === "" || text.includes(keyword) ? "" : "none";
            });
        });
    }

    const loadingCards = document.querySelectorAll(".card-loading");
    if (loadingCards.length) {
        loadingCards.forEach((card) => card.classList.add("loading"));
        window.setTimeout(() => {
            loadingCards.forEach((card) => card.classList.remove("loading"));
        }, 450);
    }

    const chartDataScript = document.getElementById("dashboard-chart-data");
    if (chartDataScript && window.Chart) {
        let chartData;
        try {
            chartData = JSON.parse(chartDataScript.textContent || "{}");
        } catch {
            chartData = null;
        }

        if (chartData && Array.isArray(chartData.labels)) {
            const buildLineChart = (canvasId, label, data, color) => {
                const el = document.getElementById(canvasId);
                if (!el) {
                    return;
                }

                new window.Chart(el, {
                    type: "line",
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                label,
                                data,
                                tension: 0.35,
                                fill: true,
                                borderColor: color,
                                pointBackgroundColor: color,
                                backgroundColor: `${color}22`,
                                borderWidth: 2,
                                pointRadius: 3,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                        },
                        scales: {
                            x: {
                                ticks: { color: "#94A3B8" },
                                grid: { color: "rgba(148,163,184,0.12)" },
                            },
                            y: {
                                ticks: { color: "#94A3B8" },
                                grid: { color: "rgba(148,163,184,0.12)" },
                            },
                        },
                    },
                });
            };

            buildLineChart(
                "revenueChart",
                "Revenue",
                chartData.revenue || [],
                "#6366F1",
            );
            buildLineChart(
                "studentGrowthChart",
                "Student Growth",
                chartData.studentGrowth || [],
                "#22C55E",
            );
            buildLineChart(
                "attendanceRateChart",
                "Attendance Rate",
                chartData.attendanceRate || [],
                "#F59E0B",
            );
        }
    }
});
