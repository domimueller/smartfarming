(function($) {
    $(document).ready(function() {
        function handleLazyLoadError(response) {
            console.log(response.status + ': ' + response.responseText);
        }

        function handleLazyLoadSuccess(response) {
            let disableButton = (response.slice(-1) === '0');
            response = response.slice(0,response.length-1);
            $('[data-ct-lazyload-target]').append(response);
            let page = $('[data-ct-lazyload-trigger]').data('ct-lazyload-page');
            $('[data-ct-lazyload-trigger]').data('ct-lazyload-page', page+1);
            if (disableButton) {
                $('[data-ct-lazyload-trigger]').prop('disabled', true);
            }
            $('[data-ct-lazyload-trigger]').removeClass('hidden');
            $('[data-ct-lazyload-spinner]').addClass('hidden');
        }

        function sendLazyLoadRequest(postType, page, postsPerPage, filters, card, ancestor, children, wrapperClass) {
            let data = {
                action         : 'ct_lazyload',
                post_type      : postType,
                page           : page,
                posts_per_page : postsPerPage,
                wrapper_class  : wrapperClass,
                filter         : filters
            };
            if (card !== undefined) {
                data.card = card;
            }
            if (ancestor !== undefined) {
                data.ancestor = ancestor;
            }
            if (children !== undefined) {
                data.children = children;
            }
            $.ajax(ajaxurl, {
                success : handleLazyLoadSuccess,
                error   : handleLazyLoadError,
                data    : data,
                method  : 'POST'
            });
        }

        function getFilterObjectFromString(str) {
            const regex = /([a-z0-9\-_]+)(?:\[([0-9]+)\])?=([a-z0-9]+)/gm;
            let m;

            let filter = {};
            while ((m = regex.exec(str)) !== null) {
                // This is necessary to avoid infinite loops with zero-width matches
                if (m.index === regex.lastIndex) {
                    regex.lastIndex++;
                }
                // The result can be accessed through the `m`-variable.
                m.forEach((match, groupIndex) => {
                    if (match != undefined) {
                        if (groupIndex == 1) {
                            filter.name = match;
                        } else if (groupIndex == 2) {
                            filter.index = match;
                        } else if (groupIndex == 3) {
                            filter.value = match;
                        }
                    }
                });
            }
            return filter;
        }

        function getFilterArrayFromString(filterString) {
            $('[data-ct-lazyload-trigger]').addClass('hidden');
            $('[data-ct-lazyload-spinner]').removeClass('hidden');
            if (filterString == undefined || filterString.length == 0) {
                return undefined;
            }
            let filterArray = [];
            if (filterString.indexOf(';') > -1) {
                let filters = filterString.split(';');
                filters.forEach(function(item, index) {
                    filterArray.push(getFilterObjectFromString(item));
                });
            } else {
                filterArray.push(getFilterObjectFromString(filterString));
            }

            return filterArray;
        }

        function prepareAndSendLazyLoadRequest() {
            let postType = $(this).data('ct-lazyload-post-type');
            let page = $(this).data('ct-lazyload-page');
            let postsPerPage = $(this).data('ct-lazyload-posts-per-page');
            let wrapperClass = $(this).data('ct-lazyload-wrapper-class');
            let filter = getFilterArrayFromString($(this).data('ct-lazyload-filter'));
            let card = $(this).data('ct-lazyload-card');
            let ancestor = $(this).data('ct-lazyload-ancestor');
            let children = $(this).data('ct-lazyload-children');

            sendLazyLoadRequest(postType, page, postsPerPage, filter, card, ancestor, children, wrapperClass);
        }

        $('[data-ct-lazyload-trigger]').not('[disabled]').click(prepareAndSendLazyLoadRequest);
    });
})(jQuery);
