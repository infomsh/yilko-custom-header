// assets/script.js
jQuery(document).ready(function($) {
    // Mobile Menu Toggle
    $('.yilko-mobile-toggle').on('click', function() {
        $('.yilko-mobile-menu').addClass('active');
        $('.yilko-mobile-overlay').addClass('active');
        $('body').css('overflow', 'hidden');
    });

    $('.yilko-mobile-close, .yilko-mobile-overlay').on('click', function() {
        $('.yilko-mobile-menu').removeClass('active');
        $('.yilko-mobile-overlay').removeClass('active');
        $('body').css('overflow', '');
    });

    // Mobile Submenu Toggle
    $('.yilko-mobile-category-list li.menu-item-has-children > a').on('click', function(e) {
        e.preventDefault();
        var $parent = $(this).parent();
        
        // Toggle current submenu
        $parent.toggleClass('active');
        $parent.children('.sub-menu').slideToggle(300);
        
        // Close other submenus at same level
        $parent.siblings('.menu-item-has-children').removeClass('active')
               .children('.sub-menu').slideUp(300);
    });

    // Update cart count on AJAX
    $(document.body).on('added_to_cart removed_from_cart updated_cart_totals', function() {
        $.ajax({
            url: wc_add_to_cart_params.ajax_url,
            type: 'POST',
            data: {
                action: 'get_cart_count'
            },
            success: function(response) {
                if (response) {
                    $('.yilko-cart-count').text(response).show();
                } else {
                    $('.yilko-cart-count').hide();
                }
            }
        });
    });
});
