
// Change wishlist heart icon when user adds product to wishlist.
(
    function ($) {

        let $this = $(this);
        console.log($this);

        if ($this.find('.yith-wcwl-wishlistexistsbrowse, .yith-wcwl-wishlistaddedbrowse').length) {

            function flatsomeChildAddToWishlist() {
                $this.find('.wishlist-button i').removeClass('icon-heart-o');
                $this.find('.wishlist-button i').addClass('icon-heart');
                console.log('test');
            }

        }

        $('body').on('added_to_wishlist removed_from_wishlist', flatsomeChildAddToWishlist);
    }
)(jQuery);



// Code to modify quick view design.
jQuery(window).on('load flatsome_child_quick_view_reload', function () {

    (
        function () {

            const quickViews = document.querySelectorAll('a.quick-view')

            quickViews.forEach(el => {
                if ( ! el.parentElement.classList.contains( 'flatsome-child-quick-view-wrapper' ) ) {
                    el.parentElement.classList.add('flatsome-child-quick-view-wrapper');
                    el.parentElement.classList.remove('hover-slide-in');
                }
            });
        }
    )();
} );
