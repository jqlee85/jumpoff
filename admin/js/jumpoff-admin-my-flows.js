(function( jQuery ) {
	'use strict';

	/**
	 * All of the code for the My Flows page
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * jQuery function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * jQuery(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * jQuery( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	jQuery('document').ready(function(jQuery) {

		//on click, change star/unstar value for post
		jQuery('.jo_flow_star').click(function(){

			
			

			//get id
			var raw_id = jQuery(this).attr('id');
			var id_array = raw_id.split('_');
			var flow_id = parseInt( id_array[id_array.length - 1] );
			console.log(flow_id);

			//get if checked
			var is_starred = jQuery(this).attr('checked');
			console.log(is_starred);

			var data = {
				'action': 'jo_save_flow_star',
				'flow_id': flow_id,
				'is_starred': is_starred
			};

			jQuery.post(ajaxurl, data, function(response) {
				console.log(response);
			});


		});


	});

	


})( jQuery );
