
function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId + '-dropdown');
    const allDropdowns = document.querySelectorAll('.dropdown-content');
    const allArrows = document.querySelectorAll('.arrow');
    
    
    const dropdownToggle = event.currentTarget;
    const arrow = dropdownToggle.querySelector('.arrow');
    
    
    allDropdowns.forEach(dd => {
        if (dd !== dropdown && dd.classList.contains('show')) {
            dd.classList.remove('show');
        }
    });
    
    
    allArrows.forEach(ar => {
        if (ar !== arrow && ar.classList.contains('open')) {
            ar.classList.remove('open');
        }
    });
    
    
    dropdown.classList.toggle('show');
    arrow.classList.toggle('open');
}


document.addEventListener('DOMContentLoaded', function() {
    const currentPage = window.location.pathname.split('/').pop() || 'dashboard.html';
    
   
    document.querySelectorAll('.nav-item, .dropdown-item').forEach(item => {
        item.classList.remove('active');
    });
    
    
    const navItems = document.querySelectorAll('.nav-item, .dropdown-item');
    navItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href === currentPage) {
            item.classList.add('active');
            
            // If it's a dropdown item, open its parent dropdown
            if (item.classList.contains('dropdown-item')) {
                const parentDropdown = item.closest('.dropdown-content');
                if (parentDropdown) {
                    parentDropdown.classList.add('show');
                    // Find and rotate the arrow
                    const dropdownId = parentDropdown.id.replace('-dropdown', '');
                    const dropdownToggle = document.querySelector(`[onclick="toggleDropdown('${dropdownId}')"]`);
                    if (dropdownToggle) {
                        const arrow = dropdownToggle.querySelector('.arrow');
                        if (arrow) {
                            arrow.classList.add('open');
                        }
                    }
                }
            }
        }
    });
});


function toggleMobileMenu() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('open');
}