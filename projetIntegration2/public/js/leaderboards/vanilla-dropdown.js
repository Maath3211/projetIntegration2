// Pure JavaScript dropdown solution - no jQuery dependency
document.addEventListener('DOMContentLoaded', function () {
    initVanillaDropdowns();

    // Set up observer for dynamic content changes
    const observer = new MutationObserver(function () {
        initVanillaDropdowns();
    });

    // Start observing
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    // Handle Livewire updates if available
    if (typeof window.Livewire !== 'undefined') {
        window.Livewire.hook('message.processed', function () {
            initVanillaDropdowns();
        });
    }
});

function initVanillaDropdowns() {
    // Get all dropdown toggles
    const toggles = document.querySelectorAll('.dropdown-toggle');

    toggles.forEach(function (toggle) {
        // Remove existing listeners to prevent duplicates
        toggle.removeEventListener('click', toggleDropdown);
        // Add fresh click listener
        toggle.addEventListener('click', toggleDropdown);
    });

    // Add global click handler to close dropdowns when clicking outside
    document.removeEventListener('click', closeDropdownsOnClickOutside);
    document.addEventListener('click', closeDropdownsOnClickOutside);

    console.log('Vanilla dropdowns initialized:', toggles.length);
}

function toggleDropdown(event) {
    event.preventDefault();
    event.stopPropagation();

    const toggle = this;
    const dropdown = toggle.closest('.dropdown');

    if (!dropdown) return;

    const menu = dropdown.querySelector('.dropdown-menu');
    if (!menu) return;

    // Close all other dropdowns first
    const allDropdowns = document.querySelectorAll('.dropdown.show');
    allDropdowns.forEach(function (otherDropdown) {
        if (otherDropdown !== dropdown) {
            otherDropdown.classList.remove('show');
            const otherMenu = otherDropdown.querySelector('.dropdown-menu');
            if (otherMenu) otherMenu.classList.remove('show');
        }
    });

    // Toggle this dropdown
    dropdown.classList.toggle('show');
    menu.classList.toggle('show');

    // Position the menu correctly
    if (menu.classList.contains('show')) {
        const rect = toggle.getBoundingClientRect();
        menu.style.position = 'absolute';
        menu.style.top = (window.scrollY + rect.bottom) + 'px';
        menu.style.left = rect.left + 'px';
        menu.style.zIndex = '1000';
    }
}

function closeDropdownsOnClickOutside(event) {
    if (!event.target.closest('.dropdown')) {
        // Close all open dropdowns
        const openDropdowns = document.querySelectorAll('.dropdown.show');
        openDropdowns.forEach(function (dropdown) {
            dropdown.classList.remove('show');
            const menu = dropdown.querySelector('.dropdown-menu');
            if (menu) menu.classList.remove('show');
        });
    }
}

// Add necessary CSS styles
function addDropdownStyles() {
    if (document.getElementById('vanilla-dropdown-styles')) return;

    const styleElement = document.createElement('style');
    styleElement.id = 'vanilla-dropdown-styles';
    styleElement.textContent = `
        .dropdown-menu.show {
            display: block !important;
            position: absolute !important;
            z-index: 1000 !important;
        }
    `;
    document.head.appendChild(styleElement);
}

// Initialize styles
addDropdownStyles();