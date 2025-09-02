(function($) {
    $(document).ready(function() {
        $('.dls-logo-slider').each(function() {
            var $slider = $(this);
            var slidesPerView = $slider.data('slides-per-view');
            var spaceBetween = $slider.data('space-between');
            var sliderSpeed = $slider.data('slider-speed');
            var autoplay = $slider.data('autoplay') === 'on';
            var pauseOnHover = $slider.data('pause-on-hover') === 'on';
            var navigationArrows = $slider.data('navigation-arrows') === 'on';
            var paginationDots = $slider.data('pagination-dots') === 'on';

            var swiperOptions = {
                slidesPerView: slidesPerView,
                spaceBetween: spaceBetween,
                speed: sliderSpeed,
                loop: true,
                autoplay: autoplay ? { delay: 3000, disableOnInteraction: pauseOnHover } : false,
                navigation: navigationArrows ? {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                } : false,
                pagination: paginationDots ? { el: '.swiper-pagination', clickable: true } : false,
            };

            var swiper = new Swiper($slider[0], swiperOptions);

            if (pauseOnHover) {
                $slider.on('mouseenter', function() {
                    swiper.autoplay.stop();
                });
                $slider.on('mouseleave', function() {
                    swiper.autoplay.start();
                });
            }
        });
    });
})(jQuery);