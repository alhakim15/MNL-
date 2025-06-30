// Modern Navbar JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const mobileMenu = document.querySelector('.mobile-menu');
    const mobileClose = document.querySelector('.mobile-close');
    
    console.log('Navbar script loaded');
    console.log('Hamburger element:', hamburger);
    console.log('Mobile menu element:', mobileMenu);
    
    // Toggle mobile menu
    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', function(e) {
            console.log('Hamburger clicked!');
            e.preventDefault();
            e.stopPropagation();
            hamburger.classList.toggle('active');
            mobileMenu.classList.toggle('active');
            console.log('Mobile menu active:', mobileMenu.classList.contains('active'));
        });
    } else {
        console.log('Hamburger or mobile menu not found!');
    }
    
    // Close mobile menu with close button
    if (mobileClose) {
        mobileClose.addEventListener('click', function() {
            hamburger.classList.remove('active');
            mobileMenu.classList.remove('active');
        });
    }
    
    // Mobile dropdown functionality
    const mobileDropdowns = document.querySelectorAll('.mobile-dropdown');
    mobileDropdowns.forEach(dropdown => {
        const header = dropdown.querySelector('.mobile-dropdown-header');
        if (header) {
            header.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Close other dropdowns
                mobileDropdowns.forEach(otherDropdown => {
                    if (otherDropdown !== dropdown) {
                        otherDropdown.classList.remove('active');
                    }
                });
                
                // Toggle current dropdown
                dropdown.classList.toggle('active');
            });
        }
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (mobileMenu && mobileMenu.classList.contains('active')) {
            if (!mobileMenu.contains(event.target) && !hamburger.contains(event.target)) {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('active');
                // Also close any open dropdowns
                mobileDropdowns.forEach(dropdown => {
                    dropdown.classList.remove('active');
                });
            }
        }
    });
    
    // Close mobile menu when clicking on links (but not dropdown headers)
    const mobileLinks = document.querySelectorAll('.mobile-menu a:not(.mobile-dropdown-header)');
    mobileLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Only close if it's not inside a dropdown content
            if (!link.closest('.mobile-dropdown-content')) {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('active');
                mobileDropdowns.forEach(dropdown => {
                    dropdown.classList.remove('active');
                });
            }
        });
    });
    
    // Desktop dropdown functionality
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(dropdown => {
        const menu = dropdown.querySelector('.dropdown-menu');
        if (menu) {
            dropdown.addEventListener('mouseenter', function() {
                menu.style.display = 'flex';
                setTimeout(() => {
                    menu.style.opacity = '1';
                    menu.style.visibility = 'visible';
                    menu.style.transform = 'translateY(0)';
                }, 10);
            });
            
            dropdown.addEventListener('mouseleave', function() {
                menu.style.opacity = '0';
                menu.style.visibility = 'hidden';
                menu.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    menu.style.display = 'none';
                }, 300);
            });
        }
    });
    
    // Close menu on window resize if screen becomes larger
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            hamburger.classList.remove('active');
            mobileMenu.classList.remove('active');
            mobileDropdowns.forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        }
    });
});
