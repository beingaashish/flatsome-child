// Global Variables.
let productsDisplayArr = [],
  metaBoxParams = flatsomeChildMetaboxParams;

// Data from PHP.
const shopProductNonce = metaBoxParams['addShopProductNonce'],
  ajaxUrl = metaBoxParams['ajaxUrl'],
  personalShopPostID = metaBoxParams['personalShopPostID'],
  baseUrl = metaBoxParams['baseUrl'];

const personalShopperSearch = (
  function ($) {

    let typingTimer,
      searchResultVisibility = false,
      spinnerVisibility = false;

    const DOMString = {
      searchField: document.querySelector('.myronja-search-term'),
      searchResults: document.querySelector('.myronja-search-results'),
    }

    function setUpEvents() {
      if (DOMString.searchField != undefined) {
        DOMString.searchField.addEventListener('input', searchLogic);
      }
    }

    function searchLogic(e) {
      clearTimeout(typingTimer);

      toggleSearchResultsVisibility();
      toggleSpinnerVisibility();

      typingTimer = setTimeout(getResults, 2000, e);
    }

    function toggleSearchResultsVisibility() {
      if (DOMString.searchField.value != undefined && !searchResultVisibility) {
        DOMString.searchResults.classList.add('myronja-search-results--is-visible');
        searchResultVisibility = true
      }
      if (
        (DOMString.searchField.value == undefined || DOMString.searchField.value == '')
        && searchResultVisibility
      ) {
        DOMString.searchResults.classList.remove('myronja-search-results--is-visible');
        searchResultVisibility = false;
      }
    }

    function toggleSpinnerVisibility() {
      if (!spinnerVisibility) {
        DOMString.searchResults.innerHTML = '<div class="myronja-loader"></div>';
        spinnerVisibility = true;
      }
    }

    function getResults(e) {
      if (e.target.value != undefined && e.target.value != '') {
        let userEnteredSearchTerm = e.target.value;
        const productsRestURL = baseUrl + '/wp-json/wp/v2/product?per_page=50&search=' + userEnteredSearchTerm;

        $.getJSON(productsRestURL, function (products) {
          if (products.length <= 0) {
            DOMString.searchResults.innerHTML = '<h2>No Products found.</h2>';
          } else {
            DOMString.searchResults.innerHTML = `
            <h2>Search Results</h2>
            <ul class="myronja-search-results__list">
              ${products.map(product => {

                if (product.myronjaProductStockStatus) {
                  return (
                    `<li class="myronja-search-results__list-item" id="product-item-${product.id}">
                          <figure class="myronja-search-results__product-image"><img src="${product.myronjaProductThumbnailSrc[0]}"></figure>
                          <p class="myronja-search-results__product-brand">${product.myronjaProductBrand}</p>
                          <p class="myronja-search-results__product-title">${product.title.rendered}</p>
                          <p class="myronja-search-results__product-amount">${product.myronjaProductAmount}</p>
                          <p class="myronja-search-results__product-price">${product.myronjaProductPrice}</p>
                          <div class="myronja-search-results__action-wrapper">
                            <a href="#" button class="myronja-add-to-shop button button-primary" id="myronja-add-to-shop-${product.id}"
                            data-product-id="${product.id}"
                            data-product-thumbnail="${product.myronjaProductThumbnailSrc[0]}"
                            data-product-brand="${product.myronjaProductBrand}"
                            data-product-title="${product.title.rendered}"
                            data-product-price="${product.myronjaProductPrice}"
                            data-product-amount="${product.myronjaProductAmount}"
                            data-product-action="add-product">Add Product</a>
                          </div>
                      </li>`
                  );
                }

            }).join('')}
            </ul>`;

            $(window).trigger('myronjaSearchProductsLoaded');
          }
        });

        spinnerVisibility = false;
      }
    }

    return {
      init: function () {
        setUpEvents();
      }
    };
  }
)(jQuery);
personalShopperSearch.init();


const personalShopperAfterProductsLoaded = (
  function ($) {
    // Localized data.
    let productIDS = metaBoxParams['personalShopProductIDS'];

    // Update ProductsDisplayArr with product values that aready exists in meta data of the personal shopper post.
    if (productIDS.length > 0) {
      productIDS.forEach(id => {
        if ($(`#myronja-remove-from-shop-${id}`) != undefined) {
          productsDisplayArr.push($(`#myronja-remove-from-shop-${id}`).data());
        }
      });
    }

    // Custom event trigger.
    $(window).on('myronjaSearchProductsLoaded', function () {
      const DOMString = {
        shopProductAddButtons: document.querySelectorAll('.myronja-search-results .myronja-add-to-shop'),
        shopProductsDisplay: document.querySelector('.myronja-products-display .myronja-products-list')
      };

      if (DOMString.shopProductAddButtons != undefined) {

        DOMString.shopProductAddButtons.forEach(btn => {
          btn.addEventListener('click', (e) => {
            e.preventDefault();

            // Disable button.
            e.target.classList.add('disabled');
            e.target.style.pointerEvents = 'none';

            // Send Ajax request to update personal shopper post meta.
            let data = {
              action: 'personal_shopper_add_user_products_to_meta',
              nonce: shopProductNonce,
              productId: e.target.dataset.productId,
              productAction: e.target.dataset.productAction,
              personalShopPostID: personalShopPostID,
            }

            $.post(ajaxUrl, data, function (response, status) {

              // If the meta is successfully updated add display it
              if ('success' == status) {
                displayProducts(e);
              }
            });
          });
        });

        function displayProducts(e) {
          let productItemData = $(e.target).data(),
            productId = e.target.dataset.productId,
            productItemHTML = `
                <li class="myronja-products-item" id="product-item-${productId}">
                  <figure class="myronja-products-item__product-image"><img src="${productItemData.productThumbnail}"></figure>
                    <p class="myronja-products-item__product-title">${productItemData.productTitle}</p>
                    <p class="myronja-products-item__product-amount">${productItemData.productAmount}</p>
                    <p class="myronja-products-item__product-price">${productItemData.productPrice}</p>
                    <div class="myronja-products-item__action-wrapper">
								      <a href="#" class="myronja-remove-from-shop button button-primary" id="myronja-remove-from-shop-${productId}"
                      data-product-id="${productId}"
                      data-product-thumbnail="${productItemData.productThumbnail}"
                      data-product-title="${productItemData.productTitle}"
                      data-product-price="${productItemData.productPrice}"
                      data-product-amount="${productItemData.productAmount}"
                      data-product-action="remove-product">
									      Remove Product
								      </a>
							      </div>
                </li>
              `;

          if (productsDisplayArr.length == 0) {
            DOMString.shopProductsDisplay.insertAdjacentHTML('beforeend', productItemHTML);
            productsDisplayArr.push(productItemData);
          }

          if (!productsDisplayArr.some(el => parseInt(el.productId) == parseInt(productId))) {
            DOMString.shopProductsDisplay.insertAdjacentHTML('beforeend', productItemHTML);
            productsDisplayArr.push(productItemData);
          }

          // After products are being displayed add event listener to remove buttons to remove products.
          $(window).trigger('myronjaProductsLoadedOnDisplay', [e]);
        }
      }
    });
  }
)(jQuery);


const personalShopperAfterProductsLoadedOnDisplay = (
  function ($) {

    // Custom event trigger.
    $(window).on('load myronjaProductsLoadedOnDisplay', function () {

      let shopProductRemoveButtons = document.querySelectorAll('.myronja-products-display .myronja-remove-from-shop'),
          shopProductsDisplay = document.querySelector('.myronja-products-display .myronja-products-list');

      if (shopProductRemoveButtons != undefined) {
        shopProductRemoveButtons.forEach(btn => {
          btn.addEventListener('click', (e) => {
            e.preventDefault();

            // Disable button.
            e.target.classList.add('disabled');
            e.target.style.pointerEvents = 'none';

            // Send Ajax request to update (Delete) personal shopper post meta.
            let data = {
              action: 'personal_shopper_add_user_products_to_meta',
              nonce: shopProductNonce,
              productId: e.target.dataset.productId,
              productAction: e.target.dataset.productAction,
              personalShopPostID: personalShopPostID,
            }

            $.post(ajaxUrl, data, function (response, status) {
              // If the meta is successfully deleted remove it from the display.
              if ('success' == status) {
                removeProducts(e);
              }
            });
          });
        });

        function removeProducts(e) {
          let productId = e.target.dataset.productId,
            itemToRemove = document.querySelector(`.myronja-products-display #product-item-${productId}` );

            // Update productsDisplayArr[].
            productsDisplayArr.forEach((el, index, arr ) => {
              if ( el.productId == productId ) {
                arr.splice( index, 1 );
              }
            });

            if ( undefined != itemToRemove ) {
              // Remove from front end.
              shopProductsDisplay.removeChild(itemToRemove);
            }
        }
      }
    });
  }
)(jQuery);
