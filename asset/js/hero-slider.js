// Hero Slider Functionality
document.addEventListener('DOMContentLoaded', function() {
  // Variables for the image slider
  const slides = document.querySelectorAll('.hero-slider .slide');
  const textSlides = document.querySelectorAll('.hero-text-slide');
  let currentSlide = 0;
  const totalSlides = slides.length;
  const slideInterval = 5000; // 5 seconds per slide
  
  // Function to show a specific slide
  function showSlide(index) {
    // Hide all slides
    slides.forEach(slide => {
      slide.style.opacity = '0';
    });
    
    // Show the current slide
    slides[index].style.opacity = '1';
    
    // Hide all text slides
    textSlides.forEach(slide => {
      slide.style.opacity = '0';
      slide.style.transform = 'translateY(20px)';
    });
    
    // Show the current text slide
    setTimeout(() => {
      textSlides[index].style.opacity = '1';
      textSlides[index].style.transform = 'translateY(0)';
    }, 300);
  }
  
  // Function to advance to the next slide
  function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
  }
  
  // Initialize the first slide
  showSlide(0);
  
  // Set up the automatic slider
  setInterval(nextSlide, slideInterval);
});
