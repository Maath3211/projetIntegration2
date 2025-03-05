// drawerGabarit.js
navBar = document.querySelector("header");
console.log(navBar);
const hamburgerBtn = document.getElementById("hamburgerBtn");
const mobileMenu = document.getElementById("mobileMenu");

// Toggle mobile menu when hamburger is clicked
hamburgerBtn.addEventListener("click", function() {
    mobileMenu.classList.toggle("show-mobile-menu");
    hamburgerBtn.classList.toggle("is-active");
});

// Close mobile menu when clicking outside
document.addEventListener("click", function(event) {
    if (!navBar.contains(event.target) && !hamburgerBtn.contains(event.target) && 
        mobileMenu.classList.contains("show-mobile-menu")) {
        mobileMenu.classList.remove("show-mobile-menu");
        hamburgerBtn.classList.remove("is-active");
    }
});

// Close mobile menu when window is resized to desktop
window.addEventListener("resize", function() {
    if (window.innerWidth > 991 && mobileMenu.classList.contains("show-mobile-menu")) {
        mobileMenu.classList.remove("show-mobile-menu");
        hamburgerBtn.classList.remove("is-active");
    }
});

// Update JavaScript to handle both buttons
document.addEventListener('DOMContentLoaded', function() {
    // Existing event for main create clan button
    document.getElementById('creerClan').addEventListener('click', function() {
        document.getElementById('fenetreAjoutClan').style.display = 'flex';
    });
    
    // Additional event for mobile create clan button
    const creerClanMobile = document.getElementById('creerClanMobile');
    if (creerClanMobile) {
        creerClanMobile.addEventListener('click', function() {
            document.getElementById('fenetreAjoutClan').style.display = 'flex';
            mobileMenu.classList.remove("show-mobile-menu");
            hamburgerBtn.classList.remove("is-active");
        });
    }
});
