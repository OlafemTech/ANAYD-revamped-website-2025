// Initialize Intersection Observer for animations
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                const delay = element.getAttribute('data-delay') || '0';
                
                // Add animation classes after the specified delay
                setTimeout(() => {
                    if (element.classList.contains('opacity-0')) {
                        element.classList.remove('opacity-0');
                    }
                    if (element.classList.contains('translate-y-8')) {
                        element.classList.remove('translate-y-8');
                    }
                    
                    // Add animation classes based on data-animate attribute
                    const animation = element.getAttribute('data-animate');
                    if (animation) {
                        switch (animation) {
                            case 'fade-in':
                                element.classList.add('animate-fade-in');
                                break;
                            case 'slide-up':
                                element.classList.add('animate-slide-up');
                                break;
                            case 'slide-right':
                                element.classList.add('animate-slide-right');
                                break;
                            case 'scale-in':
                                element.classList.add('animate-scale-in');
                                break;
                        }
                    }
                }, parseInt(delay));

                // Stop observing after animation is triggered
                observer.unobserve(element);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '50px'
    });

    // Observe all elements with data-animate attribute
    document.querySelectorAll('[data-animate]').forEach(element => {
        // Add initial state classes
        element.classList.add('opacity-0');
        if (element.getAttribute('data-animate') === 'slide-up') {
            element.classList.add('translate-y-8');
        }
        observer.observe(element);
    });
});
