document.addEventListener("DOMContentLoaded", () => {
    console.log("✅ admin.js Loaded");

    const body = document.querySelector("body");
    const modeToggle = document.querySelector(".mode-toggle");
    const sidebar = document.querySelector("nav");
    const sidebarToggle = document.querySelector(".sidebar-toggle");

    // Check if elements exist before adding event listeners
    if (!modeToggle) console.warn("⚠️ modeToggle button not found! Check your HTML.");
    if (!sidebarToggle) console.warn("⚠️ sidebarToggle button not found! Check your HTML.");
    if (!sidebar) console.warn("⚠️ Sidebar <nav> not found! Check your HTML.");

    // Mode Toggle Event
    if (modeToggle) {
        modeToggle.addEventListener("click", () => {
            body.classList.toggle("dark");
            localStorage.setItem("mode", body.classList.contains("dark") ? "dark" : "light");
            console.log("🌓 Mode toggled:", body.classList.contains("dark") ? "Dark Mode" : "Light Mode");
        });
    }

    // Sidebar Toggle Event
    if (sidebarToggle) {
        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("close");
            localStorage.setItem("status", sidebar.classList.contains("close") ? "close" : "open");
            console.log("📂 Sidebar toggled:", sidebar.classList.contains("close") ? "Closed" : "Open");
        });
    }

    // Fix for `someElementId`
    const someElement = document.getElementById("someElementId"); // Replace with correct ID
    if (someElement) {
        someElement.addEventListener("click", function() {
            console.log("🎯 Element clicked!");
        });
    } else {
        console.warn("⚠️ someElementId not found! Update your HTML.");
    }
});
