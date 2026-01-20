document.addEventListener('DOMContentLoaded', function () {
    if (typeof Splide === 'undefined') {
        return;
    }

    const carousels = document.querySelectorAll('.cre-section.splide');
    
    carousels.forEach(function (carousel) {
        new Splide(carousel, {
            type: 'loop',
            perPage: 4,
            perMove: 1,
            gap: '1.5rem',
            pagination: true,
            arrows: true,
            breakpoints: {
                1440: {
                    perPage: 3,
                    gap: '1.25rem',
                },
                1024: {
                    perPage: 2,
                    gap: '1rem',
                },
                640: {
                    perPage: 1,
                    gap: '0.75rem',
                }
            },
            // Professional styling options
            classes: {
                arrows: 'splide__arrows cre-arrows',
                arrow: 'splide__arrow cre-arrow',
                prev: 'splide__arrow--prev cre-arrow--prev',
                next: 'splide__arrow--next cre-arrow--next',
                pagination: 'splide__pagination cre-pagination',
                page: 'splide__pagination__page cre-dot',
            },
        }).mount();
    });
});

