// Initialize Splide for events carousel
document.addEventListener('DOMContentLoaded', function() {
    new Splide('.events-splide', {
        type: 'loop',
        perPage: 3,
        perMove: 1,
        gap: '2rem',
        autoplay: true,
        interval: 5000,
        pauseOnHover: true,
        pagination: true,
        arrows: true,
        breakpoints: {
            1024: {
                perPage: 2,
            },
            640: {
                perPage: 1,
            }
        },
        classes: {
            arrows: 'splide__arrows !text-primary',
            arrow: 'splide__arrow !bg-white !shadow-lg hover:!bg-gray-50',
            prev: 'splide__arrow--prev !-left-5',
            next: 'splide__arrow--next !-right-5',
            pagination: 'splide__pagination !bottom-0 !-mb-8',
            page: 'splide__pagination__page !bg-primary/20 !opacity-100',
        },
    }).mount();
});
