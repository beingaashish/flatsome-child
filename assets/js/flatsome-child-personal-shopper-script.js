const personalShopperForm = (
	function ( $ ) {

		if ( 'undefined' == flatsomeChildPersonalShopperParam ) {
			return;
		}
		const personalShopperParams = flatsomeChildPersonalShopperParam;

		// Data from PHP.
		const nonce   = personalShopperParams['personalShopperNonce'],
			ajaxUrl   = personalShopperParams['ajaxUrl'];

		const DOMStrings = {
			personalShopperForm: document.querySelector( '.myronja-personal-shopper-form-container .myronja-multistep-form' ),
		}

		function setUpEvents () {
			DOMStrings.personalShopperForm.addEventListener( 'submit', createPersonalShopperPost );
		}

		function createPersonalShopperPost ( e ) {
			e.preventDefault();

			const formDataObj = {};

			// Get Form Data.
			const formData = new FormData( this );

			// Process Form Data in a well structured object.
			formDataObj.hudtilstand           = formData.getAll( 'hudtilstand' );
			formDataObj.userGoals             = formData.get( 'myronja-user-goals' );
			formDataObj.hudtype               = formData.getAll( 'hudtype' );
			formDataObj.userExpierence        = formData.get( 'myronja-user-skin-expierence' );
			formDataObj.produkttype           = formData.getAll( 'produkttype' );
			formDataObj.userRecommendation    = formData.get( 'myronja-product-type-recommendation' );
			formDataObj.ingredients           = formData.getAll( 'ingredients' );
			formDataObj.ingredientsPreference = formData.get( 'myronja-product-user-ingredients' );
			formDataObj.userAwareness         = formData.get( 'myronja-product-user-awareness' );
			formDataObj.pricerange            = formData.get( 'pricerange' );

			let data = {
				action: 'personal_shopper_create_post',
				nonce: nonce,
				formFields: formDataObj,
			};

			$.post(ajaxUrl, data, function (data) {
				console.log(data);
			});
		}

		return {
			init() {
				setUpEvents();
			}
		}
	}
)( jQuery );

personalShopperForm.init();