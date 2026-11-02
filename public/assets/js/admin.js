"use strict";

document.addEventListener("DOMContentLoaded", function () {
    const body = document.body;
    const sidebarToggle = document.querySelector("[data-sidebar-toggle]");
    const backdrop = document.querySelector("[data-sidebar-close]");
    const navLinks = document.querySelectorAll(".sidebar-nav .nav-link");

    function isDesktop() {
        return window.innerWidth >= 992;
    }

    function toggleSidebar() {
        if (isDesktop()) {
            body.classList.toggle("sidebar-mini");
            try {
                localStorage.setItem("admin_sidebar_mini", body.classList.contains("sidebar-mini") ? "true" : "false");
            } catch (e) {}
        } else {
            body.classList.toggle("sidebar-open");
        }
    }

    function closeMobileSidebar() {
        body.classList.remove("sidebar-open");
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener("click", function (e) {
            e.preventDefault();
            toggleSidebar();
        });
    }

    if (backdrop) {
        backdrop.addEventListener("click", closeMobileSidebar);
    }

    // Auto-close sidebar on mobile after clicking a navigation link
    navLinks.forEach(function (link) {
        link.addEventListener("click", function () {
            if (!isDesktop()) {
                closeMobileSidebar();
            }
        });
    });

    // Restore desktop mini state
    try {
        if (isDesktop() && localStorage.getItem("admin_sidebar_mini") === "true") {
            body.classList.add("sidebar-mini");
        }
    } catch (e) {}

    // Theme toggle
    const themeToggle = document.querySelector("[data-theme-toggle]");
    if (themeToggle) {
        themeToggle.addEventListener("click", function () {
            const currentTheme = document.documentElement.getAttribute("data-bs-theme");
            const newTheme = currentTheme === "dark" ? "light" : "dark";
            document.documentElement.setAttribute("data-bs-theme", newTheme);
            const icon = themeToggle.querySelector("i");
            if (icon) {
                icon.className = newTheme === "dark" ? "bi bi-sun" : "bi bi-moon-stars";
            }
        });
    }
});
