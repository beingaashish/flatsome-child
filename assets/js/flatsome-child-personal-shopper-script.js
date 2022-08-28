const personalShopperForm = (
	function ( $ ) {

		if ( 'undefined' == flatsomeChildPersonalShopperParam ) {
			return;
		}
		const personalShopperParams = flatsomeChildPersonalShopperParam;

		// Data from PHP.
		const nonce   = personalShopperParams['personalShopperNonce'],
			ajaxUrl   = personalShopperParams['ajaxUrl'],
			myAccountUrl = personalShopperParams['myAccountUrl'];

		const DOMStrings = {
			personalShopperForm: document.querySelector( '.myronja-personal-shopper-form-container .myronja-multistep-form' ),
			personalShopperError: document.querySelector( '.myronja-personal-shopper-form-container .myronja-multistep-form myronja-form-steps__error-message p' ),
			personalShopperSubmit: document.querySelector( '#personal-shopper-submit' ),
			personalShopperSubmitWrap: document.querySelector( '.myronja-personal-shopper-form-container .myronja-form-steps-card__footer-buttons--submit' ),
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
			formDataObj.sendMail              = formData.get( 'send-mail' );

			DOMStrings.personalShopperSubmit.disabled = true;
			DOMStrings.personalShopperSubmit.style.pointerEvents = 'none';

			let data = {
				action: 'personal_shopper_create_post',
				nonce: nonce,
				formFields: formDataObj,
			};


			$.post(ajaxUrl, data, function (data) {

			}).done(function () {
				DOMStrings.personalShopperSubmitWrap.innerHTML = '';
				DOMStrings.personalShopperSubmitWrap.innerHTML = `<p>Your Request has been submitted, <a href="${myAccountUrl}">Go to your account</a><p/>`;
			}).fail(function () {
				DOMStrings.personalShopperSubmit.disabled = true;
				DOMStrings.personalShopperSubmit.style.pointerEvents = 'none';
				DOMStrings.personalShopperError.innerText = '';
				DOMStrings.personalShopperError.innerText = 'Please try again later';
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