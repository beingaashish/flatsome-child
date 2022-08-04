
// Change wishlist heart icon when user adds product to wishlist.
(
	function ($) {

		let $this = $(this);

		if ($this.find('.yith-wcwl-wishlistexistsbrowse, .yith-wcwl-wishlistaddedbrowse').length) {

			function flatsomeChildAddToWishlist() {
				$this.find('.wishlist-button i').removeClass('icon-heart-o');
				$this.find('.wishlist-button i').addClass('icon-heart');
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
				if (!el.parentElement.classList.contains('flatsome-child-quick-view-wrapper')) {
					el.parentElement.classList.add('flatsome-child-quick-view-wrapper');
					el.parentElement.classList.remove('hover-slide-in');
				}
			});
		}
	)();
});

// Personal shopper multistep form.
const multiStepForm = (
	function () {
		let currentStep = 0;
		const DOMStrings = {
			myronjaMultiForm: document.querySelector( '.myronja-multistep-form' ),
			myronjaMultiFormsSteps: document.querySelectorAll( '.myronja-form-steps-card' ),
		};


		function setActiveCard () {
			const myronkaMultiFormsStepsArr = Array.from( DOMStrings.myronjaMultiFormsSteps );

			currentStep = myronkaMultiFormsStepsArr.findIndex( step => {
				return step.classList.contains( 'active' );
			} );

			if ( currentStep < 0 ) {
				currentStep = 0;
				DOMStrings.myronjaMultiFormsSteps[currentStep].classList.add( 'active' );
			}
		}

		function setEvents () {

			DOMStrings.myronjaMultiForm.addEventListener( 'click', ( e ) => {
				let incrementor;

				if (e.target.matches( '[data-next]' ) ) {
					incrementor = 1;

					// Check validity;
					let allValid = checkFieldsValidity();

					if (allValid) {
						currentStep += incrementor;
						displayActiveFormStep();
					} else {
						console.log('error message');
					}

				} else if ( e.target.matches( '[data-previous]' ) ) {
					incrementor = -1;
					currentStep += incrementor;
					displayActiveFormStep();
				}

				if ( incrementor == null ) return;

			} );
		}

		function displayActiveFormStep () {
			const myronkaMultiFormsStepsArr = Array.from(DOMStrings.myronjaMultiFormsSteps);
			myronkaMultiFormsStepsArr.forEach( ( step, index ) => {
				step.classList.toggle( 'active', index === currentStep );
			} );
		}

		function checkFieldsValidity () {
			const currentStepsInputs    = DOMStrings.myronjaMultiFormsSteps[currentStep].querySelectorAll( '.myronja-checkbox' );
			const currentStepsInputsArr = Array.from( currentStepsInputs );

			if ( currentStepsInputsArr.length == 0 ) return true;

			// Checks if at least one checkbox is selected.
			let atleastOneChecked = currentStepsInputsArr.some( input => {
				return input.checked;
			} );

			return atleastOneChecked;
		}

		return {
			init: function () {
				setActiveCard();
				setEvents();
			}
		}
	}
)();
multiStepForm.init();