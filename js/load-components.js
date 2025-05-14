/**
 * Component Loader
 * Handles loading HTML components into the page
 */
document.addEventListener('DOMContentLoaded', function() {
  // Load all components with data-component attribute
  loadComponents();
  
  // Load specific components by ID
  loadComponentById('navbar-container', 'components/navbar.html');
  loadComponentById('footer-container', 'components/footer.html');
});

/**
 * Load a component into an element by ID
 * @param {string} targetId - The ID of the element to load the component into
 * @param {string} componentPath - The path to the component HTML file
 */
function loadComponentById(targetId, componentPath) {
  const targetElement = document.getElementById(targetId);
  if (!targetElement) return;
  
  fetch(componentPath)
    .then(response => {
      if (!response.ok) {
        throw new Error(`Failed to load component: ${componentPath}`);
      }
      return response.text();
    })
    .then(html => {
      targetElement.innerHTML = html;
      // Dispatch event to notify that component is loaded
      const event = new CustomEvent('component:loaded', { 
        detail: { 
          targetId: targetId,
          componentPath: componentPath 
        } 
      });
      document.dispatchEvent(event);
    })
    .catch(error => {
      console.error(`Error loading component ${componentPath}:`, error);
    });
}

/**
 * Load all components with data-component attribute
 */
function loadComponents() {
  const componentContainers = document.querySelectorAll('[data-component]');
  
  componentContainers.forEach(container => {
    const componentPath = container.getAttribute('data-component');
    if (!componentPath) return;
    
    fetch(componentPath)
      .then(response => {
        if (!response.ok) {
          throw new Error(`Failed to load component: ${componentPath}`);
        }
        return response.text();
      })
      .then(html => {
        container.innerHTML = html;
        // Dispatch event to notify that component is loaded
        const event = new CustomEvent('component:loaded', { 
          detail: { 
            container: container,
            componentPath: componentPath 
          } 
        });
        document.dispatchEvent(event);
      })
      .catch(error => {
        console.error(`Error loading component ${componentPath}:`, error);
      });
  });
}
