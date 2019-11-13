(function ($) {
    $(window).on('load.404', function () {
        let count = 10;
        let counter = null;

        let timer = function () {
            count = count - 1;
            $('[data-no-found-counter-index]').text(count);
            if (count === 1) {
                clearInterval(counter);
                window.location.href = '/';
            }
        }

        if ($('body').hasClass('error404')) {
            counter = setInterval(timer, 1000);
        }

    });
})(jQuery);
