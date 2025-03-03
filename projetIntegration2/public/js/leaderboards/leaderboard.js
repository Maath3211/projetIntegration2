// Wait for both DOM and Livewire to be ready
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.Livewire === 'undefined') {
        console.warn('Livewire not loaded yet');
        return;
    }

    // Title update function
    function updateTitle() {
        const clanInfo = document.getElementById('clanInfo');
        if (clanInfo) {
            const clanName = clanInfo.getAttribute('data-clan-name') || 'Global';
            document.title = `Leaderboards - ${clanName}`;
        }
    }

    // Export function
    window.exportContainerImage = function(containerId, filename) {
        const container = document.getElementById(containerId);
        if (!container) {
            console.error("Container not found:", containerId);
            return;
        }

        const dropdowns = container.querySelectorAll('.dropdown');
        dropdowns.forEach(d => d.style.display = 'none');

        html2canvas(container, {
            backgroundColor: window.getComputedStyle(container).backgroundColor
        })
        .then(canvas => {
            dropdowns.forEach(d => d.style.display = '');
            const link = document.createElement('a');
            link.download = filename;
            link.href = canvas.toDataURL("image/png");
            link.click();
        })
        .catch(error => console.error("Export error:", error));
    };

    // Click handler using event delegation
    document.body.addEventListener('click', function(e) {
        const exportBtn = e.target.closest('[id$="ImageBtn"]');
        if (!exportBtn) return;

        e.preventDefault();
        const btnId = exportBtn.id;
        const containerId = btnId.replace('export', '').replace('ImageBtn', 'Container');
        const filename = containerId + '.png';
        exportContainerImage(containerId, filename);
    });

    // Set up Livewire hooks
    window.Livewire.hook('message.processed', () => setTimeout(updateTitle, 50));
    window.Livewire.on('clanSelected', updateTitle);

    // Initial update
    updateTitle();
});

function initLeaderboard() {
    // Export functionality with component-aware handling
    document.body.addEventListener('click', function(e) {
        const exportBtn = e.target.closest('[id$="ImageBtn"], [id$="Image"]'); // Handle both button types
        if (!exportBtn) return;
        
        e.preventDefault();
        console.log('Export button clicked:', exportBtn.id);
        
        // Expanded mapping to handle both global and clan views
        const btnIdToContainerId = {
            'exportClansImageBtn': 'topClansContainer',
            'exportUsersImageBtn': 'topUsersContainer',
            'exportTopMembresImage': 'topMembresContainer',
            'exportTopAmeliorationImage': 'topAmeliorationContainer'
        };
        
        const containerId = btnIdToContainerId[exportBtn.id];
        if (!containerId) {
            console.error('Unknown export button ID:', exportBtn.id);
            return;
        }
        
        const container = document.getElementById(containerId);
        console.log('Looking for container:', containerId);
        
        if (!container) {
            console.error('Container not found:', containerId);
            return;
        }

        // Hide dropdowns during capture
        const dropdowns = container.querySelectorAll('.dropdown');
        dropdowns.forEach(d => d.style.display = 'none');

        html2canvas(container, {
            backgroundColor: window.getComputedStyle(container).backgroundColor
        })
        .then(canvas => {
            dropdowns.forEach(d => d.style.display = '');
            const link = document.createElement('a');
            link.download = containerId + '.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
            console.log('Export complete:', containerId);
        })
        .catch(error => console.error('Export error:', error));
    });

    // Listen for Livewire component switches
    document.addEventListener('livewire:load', function() {
        Livewire.hook('message.processed', function() {
            console.log('Component updated - reinitializing export handlers');
        });
    });

    console.log('Leaderboard initialized with component switching support');
}

// Initialize on load
document.addEventListener('DOMContentLoaded', initLeaderboard);