// Combined leaderboard.js - Contains both dropdown and export functionality
(function() {
    'use strict';
    
    // Initialize on DOM content loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing leaderboard functionality');
        initLeaderboard();
    });
    
    // Also initialize on window load to ensure everything is ready
    window.addEventListener('load', function() {
        console.log('Window loaded - reinitializing leaderboard');
        initLeaderboard();
    });
    
    // Main initialization function
    function initLeaderboard() {
        // Add CSS for dropdowns
        addDropdownStyles();
        
        // Initialize dropdown functionality
        initializeDropdowns();
        
        // Set up export handlers
        setupExportHandlers();
        
        // Listen for Livewire updates
        setupLivewireIntegration();
    }
    
    // Add necessary styles for dropdowns
    function addDropdownStyles() {
        if (document.getElementById('dropdown-styles')) return;
        
        const styleElement = document.createElement('style');
        styleElement.id = 'dropdown-styles';
        styleElement.textContent = `
            .dropdown-menu {
                display: none;
                min-width: 10rem;
                max-width: 20rem;
            }
            .dropdown-menu.show {
                display: block !important;
                position: absolute !important;
                z-index: 1050 !important;
                padding: 8px 0;
                margin: 0.125rem 0 0;
                background-color: #fff;
                border: 1px solid rgba(0, 0, 0, 0.15);
                border-radius: 0.25rem;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
                right: 0;
                text-align: left;
            }
            .dropdown-toggle::after {
                display: inline-block;
                margin-left: 0.255em;
                vertical-align: 0.255em;
                content: "";
                border-top: 0.3em solid;
                border-right: 0.3em solid transparent;
                border-bottom: 0;
                border-left: 0.3em solid transparent;
            }
            .dropdown-item {
                display: block;
                width: 100%;
                padding: 0.25rem 1.5rem;
                clear: both;
                font-weight: 400;
                color: #212529;
                text-align: inherit;
                white-space: nowrap;
                background-color: transparent;
                border: 0;
                text-decoration: none;
            }
            .dropdown-item:hover, .dropdown-item:focus {
                color: #16181b;
                text-decoration: none;
                background-color: #f8f9fa;
            }
        `;
        document.head.appendChild(styleElement);
    }
    
    // ==============================
    // DROPDOWN FUNCTIONALITY
    // ==============================
    
    // Initialize all dropdowns
    function initializeDropdowns() {
        console.log('Initializing universal dropdown handlers');
        
        // Remove event delegation to avoid conflicts
        document.removeEventListener('click', documentClickHandler);
        
        // Add global click handler with event delegation
        document.addEventListener('click', documentClickHandler);
        
        console.log('Universal dropdown handlers initialized');
    }
    
    // Handle all clicks with event delegation
    function documentClickHandler(event) {
        // Handle dropdown toggle clicks
        if (event.target.matches('.dropdown-toggle') || event.target.closest('.dropdown-toggle')) {
            handleDropdownToggleClick(event);
        } 
        // Handle clicks outside dropdowns (to close them)
        else if (!event.target.closest('.dropdown')) {
            closeAllDropdowns();
        }
    }
    
    // Handle clicks on dropdown toggles
    function handleDropdownToggleClick(event) {
        event.preventDefault();
        event.stopPropagation();
        
        const toggleButton = event.target.matches('.dropdown-toggle') 
            ? event.target 
            : event.target.closest('.dropdown-toggle');
            
        if (!toggleButton) return;
        
        const dropdown = toggleButton.closest('.dropdown');
        if (!dropdown) return;
        
        const dropdownMenu = dropdown.querySelector('.dropdown-menu');
        if (!dropdownMenu) return;
        
        // Check if this dropdown is already open
        const isOpen = dropdown.classList.contains('show');
        
        // Close all other dropdowns
        closeAllDropdowns();
        
        // If this dropdown wasn't open, open it now
        if (!isOpen) {
            // Show the dropdown
            dropdown.classList.add('show');
            dropdownMenu.classList.add('show');
            
            // Position the dropdown
            positionDropdown(toggleButton, dropdownMenu);
        }
    }
    
    // Position a dropdown menu relative to its toggle button
    function positionDropdown(toggleButton, dropdownMenu) {
        const rect = toggleButton.getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Set dropdown menu position
        dropdownMenu.style.position = 'absolute';
        dropdownMenu.style.top = (rect.bottom + scrollTop - rect.height) + 'px';
        dropdownMenu.style.right = '0';
        dropdownMenu.style.zIndex = '1050';
        
        // Ensure the dropdown is visible within viewport
        setTimeout(() => {
            const menuRect = dropdownMenu.getBoundingClientRect();
            const viewportWidth = window.innerWidth;
            
            if (menuRect.right > viewportWidth) {
                // Adjust position if it's going off-screen
                const overflow = menuRect.right - viewportWidth;
                dropdownMenu.style.right = (parseInt(dropdownMenu.style.right || 0) + overflow + 5) + 'px';
            }
        }, 0);
    }
    
    // Close all dropdowns
    function closeAllDropdowns() {
        document.querySelectorAll('.dropdown.show').forEach(dropdown => {
            dropdown.classList.remove('show');
            const menu = dropdown.querySelector('.dropdown-menu');
            if (menu) menu.classList.remove('show');
        });
    }
    
    // ==============================
    // EXPORT FUNCTIONALITY
    // ==============================
    
    // Set up export handlers
    function setupExportHandlers() {
        console.log('Setting up export handlers');
        
        // Flag to prevent multiple simultaneous exports
        if (typeof window.exportInProgress === 'undefined') {
            window.exportInProgress = false;
        }
        
        // Use event delegation for export buttons
        document.removeEventListener('click', handleExportClick);
        document.addEventListener('click', handleExportClick);
        
        console.log('Export handlers set up');
    }
    
    // Handle export button clicks
    function handleExportClick(e) {
        // Find export button clicks
        const button = e.target.closest('[id$="ImageBtn"], [id$="Image"]');
        if (!button) return;
        
        e.preventDefault();
        e.stopPropagation();
        
        // Only proceed if not already exporting
        if (window.exportInProgress) {
            console.log('Export already in progress, ignoring click');
            return;
        }
        
        console.log('Export button clicked:', button.id || button.textContent);
        
        // Map button IDs to container IDs
        const exportMapping = {
            'exportClansImageBtn': 'topClansContainer',
            'exportUsersImageBtn': 'topUsersContainer',
            'exportTopMembresImage': 'topMembresContainer',
            'exportTopAmeliorationImage': 'topAmeliorationContainer'
        };
        
        // Determine container from button ID
        let containerId;
        if (button.id in exportMapping) {
            containerId = exportMapping[button.id];
        } else {
            // Try to infer from button ID
            if (button.id.includes('Clans')) containerId = 'topClansContainer';
            else if (button.id.includes('Users')) containerId = 'topUsersContainer';
            else if (button.id.includes('Membres')) containerId = 'topMembresContainer';
            else if (button.id.includes('Amelioration')) containerId = 'topAmeliorationContainer';
            else {
                // Try to find container from button's parent structure
                const leaderboardHeader = button.closest('.leaderboard-header');
                if (leaderboardHeader && leaderboardHeader.parentElement) {
                    containerId = leaderboardHeader.parentElement.id;
                }
            }
        }
        
        if (!containerId) {
            console.error('Could not determine container for export');
            return;
        }
        
        const container = document.getElementById(containerId);
        if (!container) {
            console.error('Container not found:', containerId);
            return;
        }
        
        // Start export process
        window.exportInProgress = true;
        captureContainer(container, containerId);
    }
    
    // Capture and export a container
    function captureContainer(container, containerId) {
        console.log('Starting capture of container:', containerId);
        
        // Create notification
        const notification = document.createElement('div');
        notification.textContent = 'Capture...';
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.backgroundColor = '#4CAF50';
        notification.style.color = 'white';
        notification.style.padding = '10px 20px';
        notification.style.borderRadius = '4px';
        notification.style.zIndex = '9999';
        notification.style.boxShadow = '0 2px 5px rgba(0,0,0,0.2)';
        document.body.appendChild(notification);
        
        // Close any open dropdowns
        closeAllDropdowns();
        
        // Save original container styles
        const originalStyles = {
            width: container.style.width,
            position: container.style.position,
            visibility: container.style.visibility,
            display: container.style.display
        };
        
        // Find elements to hide
        const elementsToHide = [
            ...container.querySelectorAll('.dropdown-menu'),
            ...container.querySelectorAll('.btn.dropdown-toggle'),
            ...container.querySelectorAll('[id$="Dropdown"]')
        ];
        
        // Find icons to hide
        const iconsToHide = [
            ...container.querySelectorAll('.fa-share-from-square')
        ];
        
        // Save and hide elements
        const elementStyles = new Map();
        elementsToHide.forEach(el => {
            elementStyles.set(el, {
                display: el.style.display,
                visibility: el.style.visibility,
                isDropdownToggle: el.classList.contains('dropdown-toggle') || el.classList.contains('btn')
            });
            
            el.style.visibility = 'hidden';
            el.style.display = 'none';
        });
        
        // Save and hide icons using opacity
        const iconStyles = new Map();
        iconsToHide.forEach(icon => {
            iconStyles.set(icon, {
                opacity: icon.style.opacity,
                visibility: icon.style.visibility
            });
            
            icon.style.opacity = '0';
            icon.style.visibility = 'hidden';
        });
        
        // Prepare container for capture
        container.style.width = container.offsetWidth + 'px';
        container.style.position = 'relative';
        container.style.visibility = 'visible';
        container.style.display = 'block';
        
        // Check if html2canvas is loaded
        loadHtml2Canvas(() => {
            // Capture after a small delay to ensure styles are applied
            setTimeout(() => {
                html2canvas(container, {
                    backgroundColor: null,
                    allowTaint: true,
                    useCORS: true,
                    scale: 2,
                    logging: false,
                    onclone: function(clonedDoc) {
                        // Ensure elements are hidden in the clone
                        const clonedContainer = clonedDoc.getElementById(containerId);
                        if (clonedContainer) {
                            const toHide = [
                                ...clonedContainer.querySelectorAll('.dropdown-toggle'),
                                ...clonedContainer.querySelectorAll('.fa-share-from-square'),
                                ...clonedContainer.querySelectorAll('.dropdown-menu')
                            ];
                            toHide.forEach(el => {
                                el.style.display = 'none';
                                el.style.visibility = 'hidden';
                            });
                        }
                    }
                })
                .then(canvas => {
                    finishCapture(canvas, container, containerId, notification, originalStyles, elementStyles, iconStyles);
                })
                .catch(error => {
                    console.error('Capture error:', error);
                    restoreElements(container, notification, originalStyles, elementStyles, iconStyles);
                    alert('Failed to generate image. Please try again.');
                });
            }, 100);
        });
    }
    
    // Complete the capture and export process
    function finishCapture(canvas, container, containerId, notification, originalStyles, elementStyles, iconStyles) {
        // Remove notification
        document.body.removeChild(notification);
        
        // Create filename
        let filename = containerId;
        const clanNameHolder = document.getElementById('clanNameHolder');
        if (clanNameHolder && clanNameHolder.textContent.trim()) {
            filename += '-' + clanNameHolder.textContent.trim();
        }
        
        // Create download link
        const link = document.createElement('a');
        link.download = filename + '.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
        
        // Restore elements
        restoreElements(container, null, originalStyles, elementStyles, iconStyles);
        
        console.log('Export complete for', containerId);
    }
    
    // Restore all elements after capture
    function restoreElements(container, notification, originalStyles, elementStyles, iconStyles) {
        // Remove notification if it exists
        if (notification && document.body.contains(notification)) {
            document.body.removeChild(notification);
        }
        
        // Restore container styles
        if (originalStyles) {
            container.style.width = originalStyles.width;
            container.style.position = originalStyles.position;
            container.style.visibility = originalStyles.visibility;
            container.style.display = originalStyles.display;
        }
        
        // Restore regular elements
        if (elementStyles) {
            elementStyles.forEach((styles, el) => {
                if (styles.isDropdownToggle) {
                    // Reset dropdown toggle styles
                    el.style.display = '';
                    el.style.visibility = '';
                    
                    // Force display if needed
                    setTimeout(() => {
                        if (window.getComputedStyle(el).display === 'none') {
                            el.style.display = 'inline-block';
                        }
                    }, 10);
                } else {
                    // Reset other element styles
                    el.style.display = styles.display === 'none' ? '' : styles.display;
                    el.style.visibility = styles.visibility;
                }
            });
        }
        
        // Restore icons
        if (iconStyles) {
            iconStyles.forEach((styles, icon) => {
                icon.style.opacity = styles.opacity || '';
                icon.style.visibility = styles.visibility || '';
            });
        }
        
        // Ensure dropdowns in this container are closed
        container.querySelectorAll('.dropdown.show').forEach(dropdown => {
            dropdown.classList.remove('show');
            const menu = dropdown.querySelector('.dropdown-menu');
            if (menu) {
                menu.classList.remove('show');
                menu.style.display = 'none';
            }
        });
        
        // Reset export flag
        window.exportInProgress = false;
        
        // Reinitialize dropdown functionality
        setTimeout(initializeDropdowns, 100);
    }
    
    // Load html2canvas if needed
    function loadHtml2Canvas(callback) {
        if (typeof html2canvas !== 'undefined') {
            callback();
            return;
        }
        
        console.log('Loading html2canvas');
        
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
        script.onload = callback;
        script.onerror = () => {
            console.error('Failed to load html2canvas, trying alternative source');
            
            const fallbackScript = document.createElement('script');
            fallbackScript.src = 'https://html2canvas.hertzen.com/dist/html2canvas.min.js';
            fallbackScript.onload = callback;
            fallbackScript.onerror = () => {
                console.error('Failed to load html2canvas from all sources');
                alert('Could not load required library for export.');
                window.exportInProgress = false;
            };
            
            document.head.appendChild(fallbackScript);
        };
        
        document.head.appendChild(script);
    }
    
    // ==============================
    // LIVEWIRE INTEGRATION
    // ==============================
    
    // Setup Livewire integration if available
    function setupLivewireIntegration() {
        if (typeof window.Livewire !== 'undefined') {
            window.Livewire.hook('message.processed', function() {
                console.log('Livewire update detected, reinitializing leaderboard');
                setTimeout(initializeDropdowns, 100);
                setTimeout(setupExportHandlers, 100);
            });
        }
    }
    
    // ==============================
    // PUBLIC API
    // ==============================
    
    // Make functionality available globally
    window.leaderboardFunctions = {
        init: initLeaderboard,
        closeDropdowns: closeAllDropdowns
    };
})();