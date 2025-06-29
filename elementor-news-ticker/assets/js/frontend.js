(function ($) {
    'use strict';

    var NewsTickerHandler = function ($scope, $) {
        var $tickerWrapper = $scope.find('.news-ticker-wrapper');
        if (!$tickerWrapper.length) return;

        var $tickerList = $tickerWrapper.find('.ticker-list');
        var $tickerItems = $tickerList.find('li');
        if ($tickerItems.length <= 1) {
             if ($tickerItems.length === 1) $tickerItems.css({ opacity: 1, visibility: 'visible' });
             $tickerWrapper.find('.ticker-navigation').hide();
             return;
        }

        var effect = $tickerWrapper.data('effect');
        var speed = $tickerWrapper.data('speed') || 5000;
        var isInfinite = $tickerWrapper.data('infinite') === 'yes';

        $tickerList.addClass('effect-' + effect);

        if (effect === 'scroll') {
            handleScrollEffect();
        } else {
            handleSlideFadeEffect();
        }

        function handleScrollEffect() {
            var totalWidth = 0;
            $tickerItems.each(function() { totalWidth += $(this).outerWidth(true); });
            
            // For seamless loop, clone items if they don't fill the container width
            var contentWidth = $tickerWrapper.find('.ticker-content').width();
            if (totalWidth < contentWidth) {
                var cloneCount = Math.ceil((contentWidth * 1.5) / totalWidth);
                 for (var i = 0; i < cloneCount; i++) {
                    $tickerList.append($tickerItems.clone());
                 }
            }
             $tickerList.append($tickerItems.clone());

            var newTotalWidth = 0;
            $tickerList.find('li').each(function() { newTotalWidth += $(this).outerWidth(true); });

            var duration = (newTotalWidth / 100) * (speed / 1000); // Adjust speed based on width
            $tickerList.css('animation-duration', Math.max(10, duration) + 's');
        }

        function handleSlideFadeEffect() {
            var currentIndex = 0;
            var interval;
            var $navPrev = $scope.find('.arrow-prev');
            var $navNext = $scope.find('.arrow-next');
            
            function updateNav() {
                if (!isInfinite) {
                    $navPrev.toggleClass('arrow-disabled', currentIndex === 0);
                    $navNext.toggleClass('arrow-disabled', currentIndex === $tickerItems.length - 1);
                }
            }
            
            function showItem(index) {
                if (index < 0 || index >= $tickerItems.length) {
                    if(isInfinite) {
                        index = index < 0 ? $tickerItems.length - 1 : 0;
                    } else {
                        return;
                    }
                }
                $tickerItems.removeClass('active');
                $tickerItems.eq(index).addClass('active');
                currentIndex = index;
                updateNav();
            }

            function nextItem() {
                var nextIndex = currentIndex + 1;
                if (nextIndex >= $tickerItems.length && !isInfinite) {
                    stopTicker();
                    return;
                }
                showItem(nextIndex);
            }

            function prevItem() {
                var prevIndex = currentIndex - 1;
                 if (prevIndex < 0 && !isInfinite) {
                    return;
                }
                showItem(prevIndex);
            }
            
            function startTicker() {
                 stopTicker(); // Clear previous interval before starting a new one
                 if (isInfinite || currentIndex < $tickerItems.length -1) {
                    interval = setInterval(nextItem, speed);
                 }
            }
            
            function stopTicker() {
                clearInterval(interval);
            }

            // Bind events
            $navNext.on('click', function() { if(!$(this).hasClass('arrow-disabled')) { stopTicker(); nextItem(); startTicker(); } });
            $navPrev.on('click', function() { if(!$(this).hasClass('arrow-disabled')) { stopTicker(); prevItem(); startTicker(); } });
            $tickerWrapper.on('mouseenter', stopTicker).on('mouseleave', startTicker);

            // Initial setup
            showItem(0);
            startTicker();
        }
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/news-ticker.default', NewsTickerHandler);
    });

})(jQuery);
