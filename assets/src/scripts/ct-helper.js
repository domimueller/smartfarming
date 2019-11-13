(function ($) {
    $.fn.isInViewport = function (tolerance) {
        if (typeof tolerance !== 'number') {
            tolerance = 0;
        }

        if ($(this).length > 0) {
            let elementTop = $(this).offset().top - tolerance;
            let elementBottom = elementTop + $(this).height() + tolerance;
            let viewportTop = $(window).scrollTop();
            let viewportBottom = viewportTop + $(window).height();

            return elementBottom > viewportTop && elementTop < viewportBottom;
        }

        return false;
    };
})(jQuery);