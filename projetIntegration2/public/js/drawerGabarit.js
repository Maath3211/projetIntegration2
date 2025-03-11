// drawerGabarit.js
navBar = document.querySelector("header");
console.log(navBar);
const hamburgerBtn = document.getElementById("hamburgerBtn");
const mobileMenu = document.getElementById("mobileMenu");

// Toggle mobile menu when hamburger is clicked
hamburgerBtn.addEventListener("click", function () {
    mobileMenu.classList.toggle("show-mobile-menu");
    hamburgerBtn.classList.toggle("is-active");
});

// Close mobile menu when clicking outside
document.addEventListener("click", function (event) {
    const mobileProfileMenuBtn = document.getElementById('mobileProfileMenuBtn');
    const mobileProfileMenu = document.getElementById('mobileProfileMenu');
    
    if (
        !navBar.contains(event.target) &&
        !hamburgerBtn.contains(event.target) &&
        !(mobileProfileMenuBtn && mobileProfileMenuBtn.contains(event.target)) && 
        !(mobileProfileMenu && mobileProfileMenu.contains(event.target)) &&
        mobileMenu.classList.contains("show-mobile-menu")
    ) {
        mobileMenu.classList.remove("show-mobile-menu");
        hamburgerBtn.classList.remove("is-active");
    }
});

// Close mobile menu when window is resized to desktop
window.addEventListener("resize", function () {
    if (
        window.innerWidth > 991 &&
        mobileMenu.classList.contains("show-mobile-menu")
    ) {
        mobileMenu.classList.remove("show-mobile-menu");
        hamburgerBtn.classList.remove("is-active");
    }
});

// Update JavaScript to handle both buttons
document.addEventListener("DOMContentLoaded", function () {
    // Existing event for main create clan button
    document.getElementById("creerClan").addEventListener("click", function () {
        document.getElementById("fenetreAjoutClan").style.display = "flex";
    });

    // Additional event for mobile create clan button
    const creerClanMobile = document.getElementById("creerClanMobile");
    if (creerClanMobile) {
        creerClanMobile.addEventListener("click", function () {
            document.getElementById("fenetreAjoutClan").style.display = "flex";
            mobileMenu.classList.remove("show-mobile-menu");
            hamburgerBtn.classList.remove("is-active");
        });
    }
});

// Menu profil PC
document
    .getElementById("profileMenuBtn")
    .addEventListener("click", function () {
        document.getElementById("profileMenu").classList.toggle("hidden");
    });

// Close menu when clicking outside
document.addEventListener("click", function (event) {
    if (
        !document.getElementById("profileMenuBtn").contains(event.target) &&
        !document.getElementById("profileMenu").contains(event.target)
    ) {
        document.getElementById("profileMenu").classList.add("hidden");
    }
});

// Menu profil mobile
document.addEventListener('DOMContentLoaded', function() {
    const mobileProfileMenuBtn = document.getElementById('mobileProfileMenuBtn');
    const mobileProfileMenu = document.getElementById('mobileProfileMenu');
    
    if (mobileProfileMenuBtn) {
        mobileProfileMenuBtn.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent event from bubbling up
            mobileProfileMenu.classList.toggle('hidden');
        });
    }
    
    // Hide menu when clicking outside
    document.addEventListener('click', function(event) {
        if (!mobileProfileMenuBtn.contains(event.target) && !mobileProfileMenu.contains(event.target)) {
            mobileProfileMenu.classList.add('hidden');
        }
    });
});