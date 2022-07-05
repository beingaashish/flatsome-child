
const categoriesFilter = (
    function ( $ ) {
        if ('undefined' === typeof flatsomeChildCategoriesParam) {
            return;
        }

        const childCatParams = flatsomeChildCategoriesParam;

        // Data from PHP.
        const catNonce              = childCatParams['productCategoriesNonce'],
              ajaxUrl               = childCatParams['ajaxUrl'],
              noProductFoundMessage = childCatParams['noProductFoundMessage'],
              queryArgs             = childCatParams['queryArgs'];

        const DOMStrings = {
                subCatLinks:   document.querySelectorAll( '.flatsome-child-related-product-categories-link' ),
                containerEl:   document.querySelector( '.shop-container .products' ),
                wooCounter:    document.querySelector( '.woocommerce-result-count' ),
                wooOrdering:   document.querySelector( '.woocommerce-ordering' ),
                wooPagination: document.querySelector( '.woocommerce-pagination' ),
                resetBtn: document.querySelector( '.flatsome-child-related-product-categories-filter-reset' ),
            }

        function setUpEvents () {
            DOMStrings.subCatLinks.forEach( ( el ) => {
                el.addEventListener( 'click', ( event ) => {
                    event.preventDefault();
                    loadProducts( event.target, el.dataset.categoryId );
                }, )
            } );

            DOMStrings.resetBtn.addEventListener( 'click', () => {
                location.reload();
            } )
        }

        function loadProducts( targetEl, targetCatID ) {


            if ( queryArgs.hasOwnProperty( `clickedCatID_${targetCatID}` ) ) {
                delete queryArgs[`clickedCatID_${targetCatID}`];
                targetEl.classList.remove( 'flatsome-child-active-cat-filter' );
            } else {
                queryArgs[`clickedCatID_${targetCatID}`] = parseInt( targetCatID );
                targetEl.classList.add( 'flatsome-child-active-cat-filter' );
            }

            console.log(queryArgs);

            // If queryArgs has only one value that is the ID of current page
            if (1 == Object.keys(queryArgs).length && queryArgs.hasOwnProperty('currentPageCatID') ) {
                if (null != DOMStrings.wooCounter ) DOMStrings.wooCounter.style.display = 'inline-block';
                if (null != DOMStrings.wooOrdering ) DOMStrings.wooOrdering.style.display = 'inline-block';
                if (null != DOMStrings.wooPagination ) DOMStrings.wooPagination.style.display = 'block';
            } else {
                if (null != DOMStrings.wooCounter) DOMStrings.wooCounter.style.display = 'none';
                if (null != DOMStrings.wooOrdering) DOMStrings.wooOrdering.style.display = 'none';
                if (null != DOMStrings.wooPagination ) DOMStrings.wooPagination.style.display = 'none';
            }

            // Display Loader.
            $('.shop-container').addClass('processing');

            let data = {
                action: 'flatsome_child_categories_filter',
                nonce: catNonce,
                query_args: queryArgs,
            };

            $.post( ajaxUrl, data, function ( data ) {
                let posts = $( data );

                if ( null != posts ) {

                    // Remove existing products
                    while ( DOMStrings.containerEl.firstChild ) {
                        DOMStrings.containerEl.removeChild( DOMStrings.containerEl.lastChild );
                    }

                    // Trigger necessary events.
                    Flatsome.attach('quick-view', posts);
                    Flatsome.attach('tooltips', posts);
                    Flatsome.attach('add-qty', posts);
                    Flatsome.attach('wishlist', posts);


                    // Add new filtered products.
                    $( '.shop-container .products' ).append( posts );

                    // Trigger event to change quick view style.
                    $( window ).trigger( 'flatsome_child_quick_view_reload' );

                } else {
                    // Display no products found message.
                }
            } );
        }

        return {
            init() {
                setUpEvents();
            }
        }
    }
)( jQuery );

categoriesFilter.init();
