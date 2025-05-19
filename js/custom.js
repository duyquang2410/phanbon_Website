/**
 * Custom JavaScript for Chợ Cây Trồng
 * Enhanced animations and functionality
 */

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {

    // Add scroll animation to elements
    initScrollAnimations();

    // Initialize product hover effects
    initProductHoverEffects();

    // Initialize the category carousel
    initCategoryCarousel();

    // Add parallax effect
    initParallaxEffect();

    // Initialize counter animation
    initCounterAnimation();

    // Add smooth scrolling
    initSmoothScrolling();
});

/**
 * Initialize scroll-based animations
 */
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('.animate-on-scroll');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });

    animatedElements.forEach(element => {
        observer.observe(element);
    });
}

/**
 * Initialize product hover effects
 */
function initProductHoverEffects() {
    const productItems = document.querySelectorAll('.list_h2i');

    productItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.querySelector('.list_h2i2 .button').style.opacity = '1';
            this.querySelector('.list_h2i2 .button').style.transform = 'translateY(0)';
        });

        item.addEventListener('mouseleave', function() {
            this.querySelector('.list_h2i2 .button').style.opacity = '0.9';
            this.querySelector('.list_h2i2 .button').style.transform = 'translateY(5px)';
        });
    });
}

/**
 * Initialize category carousel with touch support
 */
function initCategoryCarousel() {
    // Check if categories section exists
    const categorySection = document.querySelector('#center');
    if (!categorySection) return;

    // Add carousel class
    const categoryContainer = categorySection.querySelector('.center_h1');
    if (!categoryContainer) return;

    // Add prev/next buttons
    const prevButton = document.createElement('button');
    prevButton.classList.add('carousel-control', 'carousel-control-prev');
    prevButton.innerHTML = '<i class="fa fa-chevron-left"></i>';

    const nextButton = document.createElement('button');
    nextButton.classList.add('carousel-control', 'carousel-control-next');
    nextButton.innerHTML = '<i class="fa fa-chevron-right"></i>';

    categorySection.appendChild(prevButton);
    categorySection.appendChild(nextButton);

    // Add touch support
    let startX, moveX;
    categoryContainer.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
    });

    categoryContainer.addEventListener('touchmove', function(e) {
        moveX = e.touches[0].clientX;
    });

    categoryContainer.addEventListener('touchend', function() {
        if (startX - moveX > 50) {
            // Swipe left - next
            categoryContainer.scrollBy({
                left: 300,
                behavior: 'smooth'
            });
        } else if (moveX - startX > 50) {
            // Swipe right - prev
            categoryContainer.scrollBy({
                left: -300,
                behavior: 'smooth'
            });
        }
    });

    // Add click handlers for buttons
    prevButton.addEventListener('click', function() {
        categoryContainer.scrollBy({
            left: -300,
            behavior: 'smooth'
        });
    });

    nextButton.addEventListener('click', function() {
        categoryContainer.scrollBy({
            left: 300,
            behavior: 'smooth'
        });
    });
}

/**
 * Initialize parallax effect for banner images
 */
function initParallaxEffect() {
    const parallaxElements = document.querySelectorAll('.parallax-bg');

    window.addEventListener('scroll', function() {
        const scrollY = window.scrollY;

        parallaxElements.forEach(element => {
            const speed = element.dataset.speed || 0.5;
            element.style.transform = `translateY(${scrollY * speed}px)`;
        });
    });
}

/**
 * Initialize counter animation for statistics
 */
function initCounterAnimation() {
    const counters = document.querySelectorAll('.counter');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.getAttribute('data-target'));
                const duration = 2000; // milliseconds
                const step = Math.ceil(target / (duration / 20)); // Update every 20ms

                let current = 0;
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        clearInterval(timer);
                        counter.textContent = target;
                    } else {
                        counter.textContent = current;
                    }
                }, 20);

                observer.unobserve(counter);
            }
        });
    }, {
        threshold: 0.5
    });

    counters.forEach(counter => {
        observer.observe(counter);
    });
}

/**
 * Initialize smooth scrolling for anchor links
 */
function initSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if (!targetElement) return;

            window.scrollTo({
                top: targetElement.offsetTop - 80, // Adjust for header
                behavior: 'smooth'
            });
        });
    });
}

/**
 * Add "Back to Top" button functionality
 */
window.addEventListener('scroll', function() {
    const backToTopBtn = document.querySelector('.back-to-top');
    if (!backToTopBtn) return;

    if (window.scrollY > 300) {
        backToTopBtn.classList.add('show');
    } else {
        backToTopBtn.classList.remove('show');
    }
});

/**
 * Add sticky header effect
 */
window.addEventListener('scroll', function() {
    const header = document.querySelector('#header');
    if (!header) return;

    if (window.scrollY > 100) {
        header.classList.add('sticky-slide-down');
    } else {
        header.classList.remove('sticky-slide-down');
    }
});