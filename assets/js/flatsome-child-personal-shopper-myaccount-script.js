const toggleAnswerField = (
  function () {
    const DOMStrings = {
      toggle: document.querySelector( '.personal-shop-user-answers__toggle' ),
      answerWrap: document.querySelector( '.personal-shop-user-answers' )
    }

    function setUpEvents () {
      DOMStrings.toggle.addEventListener( 'click', ( e ) => {
        if ( e.target.id == 'answer-toggle-button') {
          e.preventDefault();
          DOMStrings.answerWrap.classList.toggle( 'personal-shop-user-answers--is-visible' );
        }
      } );
    }

    return {
      init: function() {
        setUpEvents();
      }
    }
  }
)();

toggleAnswerField.init();


// Code for updating product quantity.
const updateProductQuantity = (
  function () {

    const DOMStrings = {
      addButtons: document.querySelectorAll( '.personal-shop_cart-form .quantity.buttons_added .plus' ),
      minusButtons: document.querySelectorAll('.personal-shop_cart-form .quantity.buttons_added .minus')
    };

    function setUpEvents() {
      DOMStrings.addButtons.forEach( addBtn => {
        addBtn.addEventListener( 'click', updateOnAdd );
      });

      DOMStrings.minusButtons.forEach( minusBtn => {
        minusBtn.addEventListener( 'click', updateOnMinus );
      } )
    }

    function updateOnAdd ( e ) {

      // Update Sub total for both mobile and desktop view.
      setTimeout(() => {
      let productId = e.target.dataset.productId;

      if ( productId ) {
        let parentEl        = document.querySelector( `#product-number-${productId}` );
        let desktopAmountEl = parentEl.querySelector( '.personal-shop__product-subtotal .woocommerce-Price-amount.amount .amount-number' );
        let mobileAmountEl  = parentEl.querySelector( '.mobile-product-price .woocommerce-Price-amount.amount .amount-number' );
        let mobileQtyEl     = parentEl.querySelector( '.mobile-product-price__qty .quantity' );
        let multiplier      = parseInt(parentEl.querySelector(`#product-amount-${productId}`).value );
        let price           = parseInt(parentEl.dataset.productPrice);
        let newVal          = multiplier * price;


        // Update on desktop.
        if ( desktopAmountEl != undefined ) {
          desktopAmountEl.innerText = newVal + '';
        }

        // Update mobile UI amount.
        if ( mobileAmountEl != undefined ) {
          mobileAmountEl.innerHTML = newVal + '';
        }

        // Update mobile UI quantitiy.
        if (mobileQtyEl != undefined) {
          mobileQtyEl.innerHTML = multiplier + '';
        }

        // Update data quantity attribute of checkbox field.
        parentEl.querySelector('.myronja-checkbox').dataset.productQuantity = multiplier;


        }
      }, 800);
    }

    function updateOnMinus(e) {

      // Update Sub total for both mobile and desktop view.
        let productId = e.target.dataset.productId;

        if (productId) {
          let parentEl = document.querySelector(`#product-number-${productId}`);
          let desktopAmountEl = parentEl.querySelector('.personal-shop__product-subtotal .woocommerce-Price-amount.amount .amount-number');
          let mobileAmountEl = parentEl.querySelector('.mobile-product-price .woocommerce-Price-amount.amount .amount-number');
          let mobileQtyEl = parentEl.querySelector('.mobile-product-price__qty .quantity');
          let multiplier = parseInt(parentEl.querySelector(`#product-amount-${productId}`).value);
          let price = parseInt(parentEl.dataset.productPrice);
          let newVal;

          if ( multiplier >= 1 ) {
            newVal = (multiplier * price ) - price;

            // Update on desktop.
            if (desktopAmountEl != undefined) {
              desktopAmountEl.innerText = newVal + '';
            }

            // Update mobile UI amount.
            if (mobileAmountEl != undefined) {
              mobileAmountEl.innerHTML = newVal + '';
            }

            // Update mobile UI quantitiy.
            setTimeout(() => {
              multiplier = parseInt(parentEl.querySelector(`#product-amount-${productId}`).value);
              if (mobileQtyEl != undefined) {
                mobileQtyEl.innerHTML = multiplier + '';
              }
              // Update data quantity attribute of checkbox field.
              parentEl.querySelector('.myronja-checkbox').dataset.productQuantity = multiplier;
            }, 800);
          }
        }
    }

    return {
      init: function () {
        setUpEvents();
      }
    }
  }
) ();
updateProductQuantity.init();


// Handle form submission and add multiple products to the cart.
const personalShopProductFormSubmission = (
  function ( $ ) {

    if ('undefined' == flatsomeChildPersonalMyAccountParam) {
      return;
    }

    const personalMyAccountParams = flatsomeChildPersonalMyAccountParam;

    // Data from PHP.
    const nonce = personalMyAccountParams['personalMyAccountNonce'],
      ajaxUrl   = personalMyAccountParams['ajaxUrl'];

    const DOMStrings = {
      personalMyAccountForm: document.querySelector( '.personal-shop-wrapper .personal-shop_cart-form' ),
    }

    function setUpEvents () {
      DOMStrings.personalMyAccountForm.addEventListener('submit', handleSubmitRequest );
    }

    function handleSubmitRequest ( e ) {
      e.preventDefault();

      const formDataObj = {};

      // Get Form Data.
      const formData = new FormData(this);

      // Process Form Data in a well structured object.
      productIdArr  = formData.getAll('personal-shop-product');

      productIdArr.forEach( id => {
        let parentEl = document.querySelector(`#product-number-${id}`);
        formDataObj[`${id}`] = parentEl.querySelector('.myronja-checkbox').dataset.productQuantity;
      });

      let data = {
        action: 'personal_shopper_myaccount',
        nonce: nonce,
        formFields: formDataObj,
      };

      if (productIdArr != 'undefined' && productIdArr.length != 0 ) {
        $.post(ajaxUrl, data, function (data) {

          $(document.body).trigger('wc_fragment_refresh'); // Refresh cart fragments

          console.log('ajax-response', data);
          // let fragments = data.fragments;

          // if (fragments) {

          //   $.each(fragments, function (key, value) {
          //     $(key).replaceWith(value);
          //   });

          // }
        });
      } else {
        // Message to display.
      }

    }

    return {
      init: function () {
        if (DOMStrings.personalMyAccountForm != undefined ) {
          setUpEvents();
        }
      }
    }
  }
) (jQuery);

personalShopProductFormSubmission.init();
