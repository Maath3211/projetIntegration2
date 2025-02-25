// Leaderboard functionality - handles both dropdowns and exports
document.addEventListener('DOMContentLoaded', function () {
    // Check if jQuery and Bootstrap are available for dropdowns
    if (typeof $ !== 'undefined' && typeof $.fn.dropdown !== 'undefined') {
        // Use jQuery/Bootstrap for dropdowns
        initializeBootstrapDropdowns();
    } else {
        // Fallback to vanilla JS implementation
        initializeVanillaDropdowns();
    }

    // Set up export handlers (doesn't depend on jQuery)
    setupExportHandlers();

    // Listen for Livewire updates if available
    if (typeof window.Livewire !== 'undefined') {
        window.Livewire.hook('message.processed', function () {
            // Reinitialize after Livewire updates the DOM
            if (typeof $ !== 'undefined' && typeof $.fn.dropdown !== 'undefined') {
                // Use jQuery/Bootstrap for dropdowns
                initializeBootstrapDropdowns();
            } else {
                // Fallback to vanilla JS implementation
                initializeVanillaDropdowns();
            }
        });
    }
});

// jQuery/Bootstrap dropdown initialization
function initializeBootstrapDropdowns() {
    try {
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

        dropdownToggles.forEach(toggle => {
            try {
                // Dispose first to prevent duplicates
                $(toggle).dropdown('dispose');
            } catch (e) {
                // Ignore errors if it wasn't initialized
            }

            // Initialize dropdown
            $(toggle).dropdown();

            // Ensure click event works
            toggle.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).dropdown('toggle');
            });
        });

        console.log('Initialized Bootstrap dropdowns:', dropdownToggles.length);
    } catch (error) {
        console.error('Error initializing Bootstrap dropdowns:', error);
        // Fallback to vanilla dropdowns if Bootstrap init fails
        initializeVanillaDropdowns();
    }
}

// Vanilla JS dropdown implementation (fallback)
function initializeVanillaDropdowns() {
    // Get all dropdown toggles
    const toggles = document.querySelectorAll('.dropdown-toggle');

    // Remove existing handlers to prevent duplicates
    toggles.forEach(toggle => {
        toggle.removeEventListener('click', toggleDropdown);
        toggle.addEventListener('click', toggleDropdown);
    });

    // Global document handler to close dropdowns when clicking outside
    document.removeEventListener('click', closeDropdownsOnClickOutside);
    document.addEventListener('click', closeDropdownsOnClickOutside);

    console.log('Initialized vanilla dropdowns:', toggles.length);

    // Add necessary CSS to make dropdowns work
    addDropdownStyles();
}

// Vanilla dropdown toggle function
function toggleDropdown(e) {
    e.preventDefault();
    e.stopPropagation();

    const toggle = this;
    const dropdown = toggle.closest('.dropdown');

    if (!dropdown) return;

    const menu = dropdown.querySelector('.dropdown-menu');
    if (!menu) return;

    // Close all other dropdowns first
    document.querySelectorAll('.dropdown.show').forEach(otherDropdown => {
        if (otherDropdown !== dropdown) {
            otherDropdown.classList.remove('show');
            const otherMenu = otherDropdown.querySelector('.dropdown-menu');
            if (otherMenu) otherMenu.classList.remove('show');
        }
    });

    // Toggle this dropdown
    dropdown.classList.toggle('show');
    menu.classList.toggle('show');

    // Position the menu properly
    if (menu.classList.contains('show')) {
        const rect = toggle.getBoundingClientRect();
        menu.style.position = 'absolute';
        menu.style.top = (window.scrollY + rect.bottom) + 'px';
        menu.style.left = rect.left + 'px';
        menu.style.zIndex = '1000';
    }
}

// Close dropdowns when clicking outside
function closeDropdownsOnClickOutside(e) {
    if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown.show').forEach(dropdown => {
            dropdown.classList.remove('show');
            const menu = dropdown.querySelector('.dropdown-menu');
            if (menu) menu.classList.remove('show');
        });
    }
}

// Add necessary CSS styles for vanilla dropdowns
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

// Set up export functionality
function setupExportHandlers() {
    // Define all export button IDs and their corresponding containers
    const exportConfig = {
        'exportClansImageBtn': 'topClansContainer',
        'exportUsersImageBtn': 'topUsersContainer',
        'exportTopMembresImage': 'topMembresContainer',
        'exportTopAmeliorationImage': 'topAmeliorationContainer'
    };

    // Use event delegation for all export buttons
    document.body.addEventListener('click', function (e) {
        // Find if click was on or inside an export button
        const button = e.target.closest('[id$="ImageBtn"], [id$="Image"]');
        if (!button) return;

        e.preventDefault();
        console.log('Export button clicked:', button.id);

        // Get the container ID from our mapping
        const containerId = exportConfig[button.id];
        if (!containerId) {
            console.error('Unknown export button ID:', button.id);
            return;
        }

        // Get the container
        const container = document.getElementById(containerId);
        if (!container) {
            console.error('Container not found:', containerId);
            return;
        }

        // If html2canvas is available, capture and export
        if (typeof html2canvas === 'undefined') {
            console.error('html2canvas not loaded');
            // Try to load it dynamically
            loadHtml2Canvas(() => captureAndExport(container, containerId));
            return;
        }

        captureAndExport(container, containerId);
    });

    console.log('Export handlers set up');
}

// Function to capture and export container as image
function captureAndExport(container, containerId) {
    // Store original display styles
    const originalStyles = new Map();

    // Hide elements that might interfere with capture
    const elementsToHide = [
        ...container.querySelectorAll('.dropdown'),
        ...container.querySelectorAll('button'),
        ...container.querySelectorAll('[id$="Dropdown"]')
    ];

    // Hide elements
    elementsToHide.forEach(el => {
        originalStyles.set(el, el.style.display);
        el.style.display = 'none';
    });

    // Take the screenshot
    html2canvas(container, {
        backgroundColor: window.getComputedStyle(container).backgroundColor,
        logging: true,
        allowTaint: true,
        useCORS: true,
        scale: 2 // Higher quality
    })
        .then(canvas => {
            // Restore hidden elements
            elementsToHide.forEach(el => {
                el.style.display = originalStyles.get(el);
            });

            // Create download link
            const link = document.createElement('a');
            link.download = containerId + '.png';
            link.href = canvas.toDataURL('image/png');
            link.click();

            console.log('Export complete for', containerId);
        })
        .catch(error => {
            // Restore hidden elements on error
            elementsToHide.forEach(el => {
                el.style.display = originalStyles.get(el);
            });
            console.error('Export error:', error);
            alert('Failed to export image. Please check console for details.');
        });
}

// Load html2canvas dynamically if not present
function loadHtml2Canvas(callback) {
    const script = document.createElement('script');
    script.src = 'https://html2canvas.hertzen.com/dist/html2canvas.min.js';
    script.onload = callback;
    script.onerror = () => console.error('Failed to load html2canvas dynamically');
    document.head.appendChild(script);
}