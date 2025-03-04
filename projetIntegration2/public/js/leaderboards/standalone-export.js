// Standalone Export Script
// Add this as a separate script file or inline in your HTML

// Run immediately and after DOM content loaded
(function () {
    setupDirectExport();
    document.addEventListener('DOMContentLoaded', setupDirectExport);
})();

function setupDirectExport() {
    console.log('Setting up direct export handlers');

    // Define all export buttons and containers
    const exportMappings = {
        'exportClansImageBtn': 'topClansContainer',
        'exportUsersImageBtn': 'topUsersContainer',
        'exportTopMembresImage': 'topMembresContainer',
        'exportTopAmeliorationImage': 'topAmeliorationContainer'
    };

    // Add direct click handlers to all export buttons
    for (const buttonId in exportMappings) {
        const button = document.getElementById(buttonId);
        const containerId = exportMappings[buttonId];

        if (button) {
            console.log('Found export button:', buttonId);

            // Remove any existing event listeners
            button.replaceWith(button.cloneNode(true));

            // Get the fresh button reference
            const freshButton = document.getElementById(buttonId);

            // Add direct click handler
            freshButton.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                console.log(`Direct export click on ${buttonId} for ${containerId}`);

                const container = document.getElementById(containerId);
                if (container) {
                    captureAndDownload(container, containerId);
                } else {
                    console.error(`Container ${containerId} not found`);
                    alert(`Container ${containerId} not found`);
                }
            });

            console.log(`Added direct handler to ${buttonId}`);
        }
    }

    // Also set up delegation as fallback
    document.body.addEventListener('click', function (e) {
        const exportBtn = e.target.closest('[id$="ImageBtn"], [id$="Image"]');
        if (!exportBtn) return;

        const btnId = exportBtn.id;
        const containerId = exportMappings[btnId];

        if (containerId) {
            e.preventDefault();
            e.stopPropagation();
            console.log(`Delegation export click on ${btnId}`);

            const container = document.getElementById(containerId);
            if (container) {
                captureAndDownload(container, containerId);
            } else {
                console.error(`Container ${containerId} not found (delegation)`);
            }
        }
    });

    console.log('Export handlers setup complete');
}

function captureAndDownload(container, filename) {
    console.log('Starting capture for', filename);

    // Check if html2canvas is available
    if (typeof html2canvas === 'undefined') {
        console.error('html2canvas not available');
        alert('html2canvas library not loaded. Export functionality unavailable.');
        return;
    }

    // Prepare the container (hide buttons, etc.)
    const elementsToHide = container.querySelectorAll('button, .dropdown, .dropdown-toggle, .dropdown-menu');

    // Hide elements
    elementsToHide.forEach(el => el.style.visibility = 'hidden');

    // Log for debugging
    console.log(`Capturing container ${filename} (${container.offsetWidth}x${container.offsetHeight})`);

    // Take screenshot
    html2canvas(container, {
        // Basic options, minimal to avoid problems
        logging: true,
        backgroundColor: '#ffffff'
    })
        .then(function (canvas) {
            console.log('Capture successful, downloading...');

            // Show elements again
            elementsToHide.forEach(el => el.style.visibility = 'visible');

            try {
                // Create download link
                const link = document.createElement('a');
                link.download = filename + '.png';
                link.href = canvas.toDataURL('image/png');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                console.log('Download triggered');
            } catch (error) {
                console.error('Error creating download:', error);
                alert('Failed to create download: ' + error.message);
            }
        })
        .catch(function (error) {
            console.error('Error during capture:', error);

            // Show elements again
            elementsToHide.forEach(el => el.style.visibility = 'visible');

            alert('Failed to capture image: ' + error.message);
        });
}