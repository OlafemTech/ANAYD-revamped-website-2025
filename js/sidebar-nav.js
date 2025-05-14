document.addEventListener('DOMContentLoaded', function() {
  // Mobile Menu Toggle
  const menuToggle = document.getElementById('program-menu-toggle');
  const menuIcon = document.getElementById('program-menu-icon');
  const sidebar = document.getElementById('program-sidebar');

  // Initialize mobile menu state
  let isMobileMenuOpen = false;

  if (menuToggle && menuIcon && sidebar) {
    menuToggle.addEventListener('click', function(e) {
      e.preventDefault();
      isMobileMenuOpen = !isMobileMenuOpen;
      sidebar.classList.toggle('hidden', !isMobileMenuOpen);
      sidebar.classList.toggle('lg:block');
      menuIcon.style.transform = isMobileMenuOpen ? 'rotate(180deg)' : 'rotate(0deg)';
      
      // Add animation classes
      if (isMobileMenuOpen) {
        sidebar.classList.add('animate-slide-in-left');
        sidebar.classList.remove('opacity-0');
      } else {
        sidebar.classList.remove('animate-slide-in-left');
        sidebar.classList.add('opacity-0');
      }
    });

    function handleResize() {
      if (window.innerWidth >= 1024) { // lg breakpoint
        sidebar.classList.remove('hidden');
        sidebar.classList.add('lg:block');
        if (!sidebar.classList.contains('animate-slide-in-left')) {
          sidebar.classList.add('animate-slide-in-left');
          sidebar.classList.remove('opacity-0');
        }
      } else {
        if (!isMobileMenuOpen) {
          sidebar.classList.add('hidden');
          sidebar.classList.remove('animate-slide-in-left');
          sidebar.classList.add('opacity-0');
        }
      }
    }

    window.addEventListener('resize', handleResize);
    handleResize(); // Initial check
  }

  // Animation Observer for sections
  const animationObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in-view');
      }
    });
  }, {
    threshold: 0.1,
    rootMargin: '50px'
  });

  // Observe all sections and animated elements
  document.querySelectorAll('[data-animate]').forEach((element) => {
    animationObserver.observe(element);
  });
});
