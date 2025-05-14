/**
 * Navbar functionality
 * Handles mobile menu, dropdowns, and scroll behavior
 */
document.addEventListener('DOMContentLoaded', function() {
  // Initialize navbar after it's loaded
  document.addEventListener('component:loaded', function(e) {
    if (e.detail.targetId === 'navbar-container' || e.detail.componentPath === 'components/navbar.html') {
      // Add a small delay to ensure DOM elements are fully rendered
      setTimeout(() => {
        initializeNavbar();
      }, 100);
    }
  });
  
  // Initialize immediately if navbar is already in the DOM
  if (document.getElementById('main-navbar')) {
    initializeNavbar();
  }
  
  // Fallback initialization after a delay to catch any missed initialization
  setTimeout(() => {
    if (document.getElementById('main-navbar') && 
        document.getElementById('mobile-menu-button') && 
        !document.getElementById('mobile-menu-button').hasAttribute('data-initialized')) {
      console.log('Fallback navbar initialization');
      initializeNavbar();
    }
  }, 1000);
});

/**
 * Initialize navbar functionality
 */
function initializeNavbar() {
  const navbar = document.getElementById('main-navbar');
  const mobileMenu = document.getElementById('mobile-menu');
  const mobileMenuContent = document.getElementById('mobile-menu-content');
  const mobileMenuButton = document.getElementById('mobile-menu-button');
  const closeMobileMenuButton = document.getElementById('close-mobile-menu');
  const mobileDropdowns = document.querySelectorAll('.mobile-dropdown');
  
  if (!navbar) {
    console.error('Navbar element not found');
    return;
  }
  
  // Check if already initialized to prevent duplicate event listeners
  if (navbar.hasAttribute('data-initialized')) {
    console.log('Navbar already initialized');
    return;
  }
  
  // Mark as initialized
  navbar.setAttribute('data-initialized', 'true');
  console.log('Initializing navbar mobile menu');
  
  // Handle scroll behavior
  handleNavbarScroll(navbar);
  
  // Global event delegation for mobile menu buttons
  document.addEventListener('click', function(event) {
    // Mobile menu open button
    if (event.target.closest('#mobile-menu-button')) {
      openMobileMenu();
    }
    // Mobile menu close button
    else if (event.target.closest('#close-mobile-menu')) {
      closeMobileMenu();
    }
  });
  
  // Define mobile menu functions
  window.openMobileMenu = function() {
    console.log('Opening mobile menu');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuContent = document.getElementById('mobile-menu-content');
    if (!mobileMenu || !mobileMenuContent) return;
    
    // Make sure the menu is visible and has the right z-index
    mobileMenu.classList.remove('hidden');
    mobileMenu.style.zIndex = '9999'; // Ensure highest z-index
    
    // Force a reflow before starting animations
    void mobileMenu.offsetWidth;
    
    // Then animate it in
    setTimeout(() => {
      mobileMenu.classList.remove('translate-x-full');
      
      // Add a small delay before animating the content for a staggered effect
      setTimeout(() => {
        mobileMenuContent.classList.remove('translate-x-full');
      }, 50);
    }, 10);
    
    // Prevent scrolling when menu is open
    document.body.classList.add('overflow-hidden');
  };
  
  window.closeMobileMenu = function() {
    console.log('Closing mobile menu');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuContent = document.getElementById('mobile-menu-content');
    if (!mobileMenu || !mobileMenuContent) return;
    
    mobileMenu.classList.add('translate-x-full');
    mobileMenuContent.classList.add('translate-x-full');
    
    // Add a delay before hiding the menu to allow for animation
    setTimeout(() => {
      mobileMenu.classList.add('hidden');
      // Allow scrolling again
      document.body.classList.remove('overflow-hidden');
    }, 500);
  };
  
  // Close mobile menu
  closeMobileMenuButton.addEventListener('click', function(e) {
    e.preventDefault();
    console.log('Close mobile menu button clicked');
    closeMobileMenu();
  });
  
  // Close mobile menu when clicking outside
  mobileMenu.addEventListener('click', function(e) {
    if (e.target === mobileMenu) {
      closeMobileMenu();
    }
  });
  
  // Handle mobile dropdowns
  mobileDropdowns.forEach(dropdown => {
    const button = dropdown.querySelector('button');
    const content = dropdown.querySelector('.mobile-dropdown-content');
    const icon = button.querySelector('i');
    
    button.addEventListener('click', function() {
      // Toggle the dropdown content with animation
      if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        content.classList.add('max-h-0');
        setTimeout(() => {
          content.classList.remove('max-h-0');
          content.classList.add('max-h-96'); // Use a large enough value to accommodate all content
        }, 10);
      } else {
        content.classList.remove('max-h-96');
        content.classList.add('max-h-0');
        content.addEventListener('transitionend', function handler() {
          content.classList.add('hidden');
          content.removeEventListener('transitionend', handler);
        }, { once: true });
      }
      
      // Rotate the icon using classList instead of inline style
      if (content.classList.contains('hidden')) {
        icon.classList.remove('rotate-180');
      } else {
        icon.classList.add('rotate-180');
      }
    });
  });
  
  // Add active class to current page link
  highlightCurrentPage();
  
  // Function to close mobile menu
  function closeMobileMenu() {
    console.log('Closing mobile menu');
    // First animate the content out
    if (mobileMenuContent) {
      mobileMenuContent.classList.add('translate-x-full');
    }
    
    // Then animate the backdrop out with a delay
    setTimeout(() => {
      mobileMenu.classList.add('translate-x-full');
      
      // Hide the menu completely after animation completes
      setTimeout(() => {
        mobileMenu.classList.add('hidden');
        // Reset z-index to default after hiding
        mobileMenu.style.zIndex = '50';
      }, 300);
    }, 200);
    
    // Remove body overflow hidden
    setTimeout(() => {
      document.body.classList.remove('overflow-hidden');
    }, 500);
  }
  
  // Expose closeMobileMenu function globally for debugging
  window.closeMobileMenu = closeMobileMenu;
}

/**
 * Handle navbar scroll behavior
 * @param {HTMLElement} navbar - The navbar element
 */
function handleNavbarScroll(navbar) {
  const scrollThreshold = 50;
  const navbarContainer = document.getElementById('navbar-container');
  
  // Initial check
  updateNavbarOnScroll();
  
  // Listen for scroll events with passive option for better performance
  window.addEventListener('scroll', function() {
    updateNavbarOnScroll();
  }, { passive: true });
  
  function updateNavbarOnScroll() {
    // Apply scroll behavior to both the navbar and its container
    if (window.scrollY > scrollThreshold) {
      navbar.classList.add('bg-white', 'shadow-md');
      navbar.classList.remove('bg-transparent');
      
      // Ensure navbar container has the right classes
      if (navbarContainer) {
        navbarContainer.classList.add('bg-white');
        navbarContainer.classList.add('shadow-md');
      }
    } else {
      // Only remove shadow if we're at the top of the page
      if (window.scrollY === 0) {
        navbar.classList.remove('bg-white', 'shadow-md');
        
        // Only add transparent background on index page
        if (window.location.pathname.includes('index.html') || window.location.pathname.endsWith('/')) {
          navbar.classList.add('bg-transparent');
        }
        
        // Update container classes
        if (navbarContainer) {
          navbarContainer.classList.remove('bg-white');
          navbarContainer.classList.remove('shadow-md');
        }
      }
    }
  }
}

/**
 * Highlight the current page in the navigation
 */
function highlightCurrentPage() {
  const currentPath = window.location.pathname;
  const navLinks = document.querySelectorAll('nav a');
  
  navLinks.forEach(link => {
    const linkPath = link.getAttribute('href');
    if (currentPath.endsWith(linkPath) || 
        (currentPath.endsWith('/') && linkPath === 'index.html')) {
      link.classList.add('text-blue-600');
      link.classList.remove('text-gray-700');
      
      // If link is in a dropdown, also highlight the parent button
      const parentDropdown = link.closest('.mobile-dropdown');
      if (parentDropdown) {
        const parentButton = parentDropdown.querySelector('button');
        if (parentButton) {
          parentButton.classList.add('text-blue-600');
          parentButton.classList.remove('text-gray-700');
        }
      }
    }
  });
}
