// Add this script to your page to check if Bootstrap is properly loaded
// This will help identify if there's a dependency issue

console.log('=== BOOTSTRAP DEPENDENCY CHECK ===');

// Check jQuery
if (typeof jQuery === 'undefined') {
    console.error('jQuery is NOT loaded - Bootstrap requires jQuery');
} else {
    console.log('✓ jQuery is loaded:', jQuery.fn.jquery);

    // Check if Bootstrap is loaded through jQuery
    if (typeof jQuery.fn.dropdown === 'undefined') {
        console.error('Bootstrap dropdown plugin is NOT loaded');
    } else {
        console.log('✓ Bootstrap dropdown plugin is loaded');
    }
}

// Check for Bootstrap global object (Bootstrap 4+)
if (typeof bootstrap === 'undefined') {
    console.log('Bootstrap global object is not available (normal for Bootstrap 4)');
} else {
    console.log('✓ Bootstrap global object is available:', bootstrap.Dropdown.VERSION);
}

// Check for Popper.js (required by Bootstrap 4 dropdowns)
if (typeof Popper === 'undefined') {
    console.warn('Popper.js is NOT loaded - Bootstrap 4+ dropdowns require Popper.js');
} else {
    console.log('✓ Popper.js is loaded');
}

// Check if Bootstrap CSS is loaded
let bootstrapCSSLoaded = false;
document.querySelectorAll('link').forEach(link => {
    if (link.href && link.href.includes('bootstrap')) {
        bootstrapCSSLoaded = true;
        console.log('✓ Bootstrap CSS appears to be loaded:', link.href);
    }
});

if (!bootstrapCSSLoaded) {
    console.warn('Bootstrap CSS might not be loaded (could not detect "bootstrap" in link hrefs)');
}

// Check for any CSS classes that should be defined by Bootstrap
const testElement = document.createElement('div');
testElement.className = 'dropdown-menu';
document.body.appendChild(testElement);
const styles = window.getComputedStyle(testElement);
if (styles.position === 'absolute') {
    console.log('✓ Bootstrap CSS classes are working');
} else {
    console.warn('Bootstrap CSS classes may not be applied correctly');
}
document.body.removeChild(testElement);

console.log('=== END DEPENDENCY CHECK ===');