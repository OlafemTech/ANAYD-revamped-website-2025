// Initialize Splide for events carousel
document.addEventListener('DOMContentLoaded', function() {
  const eventsSplide = new Splide('.events-splide', {
    type: 'loop',
    perPage: 3,
    perMove: 1,
    gap: '2rem',
    padding: { left: 0, right: 0 },
    arrows: true,
    pagination: false,
    autoplay: true,
    interval: 5000,
    speed: 800,
    easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
    breakpoints: {
      1024: {
        perPage: 2,
      },
      640: {
        perPage: 1,
      }
    },
    classes: {
      arrow: 'splide__arrow !bg-white !shadow-md',
      prev: 'splide__arrow--prev !-left-6',
      next: 'splide__arrow--next !-right-6',
    }
  });

  eventsSplide.mount();
});
