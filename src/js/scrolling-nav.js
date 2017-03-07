//jQuery to collapse the navbar on scoll
$(window).scroll(function() {
    if($(".navbar").offset().top > 50) {
        $(".navbar-fixed-top").addClass("top-nav-collapse");
    } else {
        $(".navbar-fixed-top").removeClass("top-navbar-collapse");
    }
});

//jQuery for page scolling feature - requires jQuery Easing plugin
$(function() {
    $('.page-scroll a').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
        $('.page-scroll').removeClass('active');

        //TODO fix this
        $($anchor.attr('href')).addClass('active');

        //$('.page-scroll').addClass('active');
    });
});