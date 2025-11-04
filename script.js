document.addEventListener("DOMContentLoaded", function () {
    const themeToggle = document.getElementById("themeToggle");

    // Apply saved theme preference (if exists)
    if (localStorage.getItem("dark-mode") === "enabled") {
        document.body.classList.add("dark-mode");
        themeToggle.innerHTML = '<i class="fas fa-sun"></i> Light Mode';
    }

    // Toggle Dark/Light mode
    themeToggle.addEventListener("click", function (event) {
        event.preventDefault();
        document.body.classList.toggle("dark-mode");

        if (document.body.classList.contains("dark-mode")) {
            themeToggle.innerHTML = '<i class="fas fa-sun"></i> Light Mode';
            localStorage.setItem("dark-mode", "enabled"); // Save preference
        } else {
            themeToggle.innerHTML = '<i class="fas fa-moon"></i> Dark Mode';
            localStorage.setItem("dark-mode", "disabled"); // Save preference
        }
    });

    handleRecommendations(); // Ensure recommendations update properly
});

// Simulate login state (Change to `true` to simulate login)
let isLoggedIn = false;

// Handle showing recommendations based on login state
function handleRecommendations() {
    const carComponentsSection = document.getElementById('car-components');
    const mechanicsSection = document.getElementById('mechanics-list');
    const notLoggedInMessage = document.getElementById('not-logged-in-message');
    const notLoggedInMechanics = document.getElementById('not-logged-in-mechanics');

    if (isLoggedIn) {
        carComponentsSection.style.display = 'block';
        mechanicsSection.style.display = 'block';
        notLoggedInMessage.style.display = 'none';
        notLoggedInMechanics.style.display = 'none';
    } else {
        carComponentsSection.style.display = 'none';
        mechanicsSection.style.display = 'none';
        notLoggedInMessage.style.display = 'block';
        notLoggedInMechanics.style.display = 'block';
    }
}
document.addEventListener("DOMContentLoaded", function () {
    setTimeout(() => {
        document.getElementById("preloader").classList.add("hide");
        document.getElementById("content").style.display = "block";
    }, 1500); // Adjust timing if needed
});
