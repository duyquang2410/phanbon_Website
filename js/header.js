// Utility function for debouncing
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

// Handle all header-related functionality
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarContent = document.querySelector('#navbarContent');

    if (navbarToggler && navbarContent) {
        navbarToggler.addEventListener('click', function() {
            navbarContent.classList.toggle('show');
        });
    }

    // Cart icon animation
    const cartIcon = document.querySelector('.cart-icon');
    if (cartIcon) {
        cartIcon.addEventListener('mouseenter', function() {
            this.style.animation = 'none';
            this.offsetHeight; // Trigger reflow
            this.style.animation = 'shake 0.5s ease';
        });
    }

    // Navigation link hover effects with debouncing
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    if (navLinks.length > 0) {
        const handleHover = debounce((event, transform) => {
            if (event.target) {
                event.target.style.transform = transform;
            }
        }, 50);

        navLinks.forEach(link => {
            if (link) {
                link.addEventListener('mouseenter', e => handleHover(e, 'translateY(-2px)'));
                link.addEventListener('mouseleave', e => handleHover(e, 'translateY(0)'));
            }
        });
    }
});