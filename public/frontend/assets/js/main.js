jQuery(document).ready(function() {
    // Nav Dropdown Start

    // Add slideup & fadein animation to dropdown
    $('.dropdown').on('show.bs.dropdown', function(e) {
        var $dropdown = $(this).find('.dropdown-menu');
        var orig_margin_top = parseInt($dropdown.css('margin-top'));
        $dropdown.css({
            'margin-top': (orig_margin_top + 10) + 'px',
            opacity: 0
        }).animate({
            'margin-top': orig_margin_top + 'px',
            opacity: 1
        }, 300, function() {
            $(this).css({
                'margin-top': ''
            });
        });
    });

    // Add slidedown & fadeout animation to dropdown
    $('.dropdown').on('hide.bs.dropdown', function(e) {
        var $dropdown = $(this).find('.dropdown-menu');
        var orig_margin_top = parseInt($dropdown.css('margin-top'));
        $dropdown.css({
            'margin-top': orig_margin_top + 'px',
            opacity: 1,
            display: 'block'
        }).animate({
            'margin-top': (orig_margin_top + 10) + 'px',
            opacity: 0
        }, 300, function() {
            $(this).css({
                'margin-top': '',
                display: ''
            });
        });
    });

    // Back to Top Start
    var btn = $('#button');
    $(window).scroll(function() {
        if ($(window).scrollTop() > 50) {
            btn.addClass('show');
        } else {
            btn.removeClass('show');
        }
    });
    btn.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: 0
        }, '50');
    });

    // Navbar Fixed
    $(window).scroll(function() {
        if ($(this).scrollTop() > 3) {
            $('nav').addClass("sticky");
        } else {
            $('nav').removeClass("sticky");
        }
    });

    // Date Picker
    $('.datepicker').datepicker({
        autoclose: 'true',
        todayHighlight: 'true'
    });

    $('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
        if (!$(this).next().hasClass('show')) {
            $(this).parents('.dropdown-menu').first().find('.show').removeClass('show');
        }
        var $subMenu = $(this).next('.dropdown-menu');
        $subMenu.toggleClass('show');


        $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
            $('.dropdown-submenu .show').removeClass('show');
        });
        return false;
    });
});