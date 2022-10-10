console.log(flatsomeChildExternalAPIParams);

const myronjaExternalAPI = (
  function ( $ ) {

    // Data from PHP.
    const externalAPINonce = flatsomeChildExternalAPIParams.externalAPINonce,
          ajaxUrl          = flatsomeChildExternalAPIParams.ajaxUrl,
          baseUrl          = flatsomeChildExternalAPIParams.baseUrl;


    const DOMStrings = {
      createOrderBtn: document.querySelector( '#external_order__metabox a.myronja-create-external-order' ),
      btnWrapper: document.querySelector( '#external_order__metabox .inside' )
    };

    function setUpEvents () {
      DOMStrings.createOrderBtn.addEventListener( 'click', createOrderCallBack );
    }

    function createOrderCallBack ( e ) {

      e.preventDefault();
      // Disable button.
      e.target.classList.add('disabled');
      e.target.style.pointerEvents = 'none';

      let data = {
        action: 'myronja_handle_order_create_request_in_external_api',
        nonce: externalAPINonce,
        orderId: e.target.dataset.orderId,
      }

      $.post(ajaxUrl, data, function (response, status) {

        if ( 'Order already exists in External API' == response ) {
          DOMStrings.btnWrapper.innerHTML = '';

          const textNode = document.createElement('p'),
            successText = document.createTextNode('Order details already updated in external API');

          textNode.appendChild(successText);
          DOMStrings.btnWrapper.appendChild(textNode);

          $('#order_status').val('wc-external-order').change();
        }

        if ( response == 200 ) {

          DOMStrings.btnWrapper.innerHTML = '';

          const textNode    = document.createElement('p'),
                successText = document.createTextNode('Order details updated in External API' );

          textNode.appendChild(successText);
          DOMStrings.btnWrapper.appendChild(textNode);

          $('#order_status').val('wc-external-order').change();
        }
        if ( response !== 200 && 'Order already exists in External API' !== response ) {
          console.log(response);
          const textNode = document.createElement('p'),
            errorText = document.createTextNode('Not able to update order in external API');

          textNode.appendChild(errorText);
          textNode.classList.add('hideMe');
          DOMStrings.btnWrapper.appendChild(textNode);

          e.target.classList.remove('disabled');
          e.target.style.pointerEvents = 'initial';
        }
      }, 'json');
    }

    return {
      init: function () {
        if ( DOMStrings.createOrderBtn != undefined ) {
          setUpEvents();
        }
      }
    }
  }
)( jQuery );

myronjaExternalAPI.init();