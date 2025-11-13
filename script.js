// Dropdown toggle function
function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId + '-dropdown');
    const toggle = event.currentTarget;
    
    // Close all other dropdowns
    document.querySelectorAll('.dropdown-content').forEach(content => {
        if (content.id !== dropdownId + '-dropdown') {
            content.classList.remove('active');
            content.previousElementSibling.classList.remove('active');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('active');
    toggle.classList.toggle('active');
}

// Set active navigation item
function setActiveNav() {
    const currentPage = window.location.pathname.split('/').pop();
    const navItems = document.querySelectorAll('.nav-item');
    
    navItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href && href.includes(currentPage)) {
            item.classList.add('active');
            
            // If inside dropdown, open parent
            const dropdown = item.closest('.dropdown-content');
            if (dropdown) {
                dropdown.classList.add('active');
                dropdown.previousElementSibling.classList.add('active');
            }
        } else {
            item.classList.remove('active');
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    setActiveNav();
});