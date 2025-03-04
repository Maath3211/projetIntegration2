// Drop-in direct fix script for dropdown issues
// Add this to your layout page or include directly on the problematic pages

(function () {
    // Function to force initialize dropdowns
    function forceInitDropdowns() {
        console.log('Force initializing dropdowns');

        // Get all dropdown toggles
        document.querySelectorAll('.dropdown-toggle').forEach(function (element) {
            // Manual dropdown toggle on click
            element.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                // Toggle the dropdown menu visibility manually
                const parent = this.closest('.dropdown');
                if (parent) {
                    const menu = parent.querySelector('.dropdown-menu');
                    if (menu) {
                        // Toggle the show class on both elements
                        parent.classList.toggle('show');
                        menu.classList.toggle('show');

                        if (menu.classList.contains('show')) {
                            // Position the dropdown menu below the toggle button
                            const rect = this.getBoundingClientRect();
                            menu.style.top = rect.bottom + 'px';
                            menu.style.left = rect.left + 'px';

                            // Add a click handler to close when clicking outside
                            document.addEventListener('click', closeDropdown);
                        }
                    }
                }
            });
        });

        // Function to close all dropdowns
        function closeDropdown(e) {
            if (!e.target.closest('.dropdown')) {
                // Close all dropdowns
                document.querySelectorAll('.dropdown-menu.show').forEach(function (menu) {
                    menu.classList.remove('show');
                    if (menu.closest('.dropdown')) {
                        menu.closest('.dropdown').classList.remove('show');
                    }
                });

                // Remove the document click handler
                document.removeEventListener('click', closeDropdown);
            }
        }
    }

    // Run immediately
    forceInitDropdowns();

    // Run again after DOM changes
    const observer = new MutationObserver(function (mutations) {
        forceInitDropdowns();
    });

    // Start observing for DOM changes
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    // Handle Livewire updates if Livewire is present
    if (typeof window.Livewire !== 'undefined') {
        window.Livewire.hook('message.processed', function () {
            forceInitDropdowns();
        });
    }

    // Also run on jQuery document ready if jQuery is available
    if (typeof jQuery !== 'undefined') {
        jQuery(document).ready(forceInitDropdowns);
    }

    console.log('Dropdown fix script loaded');
})();