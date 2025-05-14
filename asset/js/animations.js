// Performance optimization check
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// Initialize animations when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  if (prefersReducedMotion) {
    // Skip animations for users who prefer reduced motion
    document.querySelectorAll('[data-animate]').forEach(element => {
      element.style.opacity = '1';
      element.style.transform = 'none';
    });
    return;
  }

  // Create Intersection Observer
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const element = entry.target;
          const animationType = element.getAttribute('data-animate');
          const delay = element.getAttribute('data-delay') || '0';

          // Add Tailwind animation classes based on data-animate attribute
          setTimeout(() => {
            switch (animationType) {
              case 'fade-in':
                element.classList.add('animate-fade-in');
                break;
              case 'slide-up':
                element.classList.add('animate-slide-up');
                break;
              case 'slide-right':
                element.classList.add('animate-slide-in-right');
                break;
              case 'slide-left':
                element.classList.add('animate-slide-in-left');
                break;
              case 'scale-in':
                element.classList.add('animate-scale-in');
                break;
            }

            // Remove transform classes after animation completes
            setTimeout(() => {
              element.style.transform = 'none';
            }, 600); // Match with animation duration
          }, parseInt(delay));

          observer.unobserve(element);
        }
      });
    },
    { threshold: 0.1 }
  );

  // Observe all elements with data-animate attribute
  document.querySelectorAll('[data-animate]').forEach((element) => {
    observer.observe(element);
  });

  // Handle hover animations
  document.querySelectorAll('[data-hover]').forEach(element => {
    element.addEventListener('mouseenter', () => {
      if (!prefersReducedMotion) {
        element.classList.add('animate-float');
      }
    });

    element.addEventListener('mouseleave', () => {
      element.classList.remove('animate-float');
    });
  });
});

// Handle smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      const headerOffset = 80; // Adjust based on your fixed header height
      const elementPosition = target.getBoundingClientRect().top;
      const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

      if (!prefersReducedMotion) {
        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        });
      } else {
        window.scrollTo(0, offsetPosition);
      }
    }
  });
});
