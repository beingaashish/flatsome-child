
// Change wishlist heart icon when user adds product to wishlist.
(
    function ( $ ) {

        let $this = $(this);

        if ( $this.find('.yith-wcwl-wishlistexistsbrowse, .yith-wcwl-wishlistaddedbrowse' ).length ) {

            function flatsomeChildAddToWishlist () {
                $this.find('.wishlist-button i').removeClass('icon-heart-o');
                $this.find('.wishlist-button i').addClass('icon-heart');
            }

        }

        $('body').on('added_to_wishlist removed_from_wishlist', flatsomeChildAddToWishlist);
    }
)(jQuery);