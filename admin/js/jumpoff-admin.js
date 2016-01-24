(function( jQuery ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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

		jQuery('.jo_edit_as_post').click(function(){

			var flowContent = jQuery('textarea#content').text();
			var flowTitle = jQuery('#title').attr('value');

			var data_flow_save_as_post  = {
				'action': 'jo_save_flow_as_post',
				'flow_content': flowContent,
				'flow_title': flowTitle
			};

			jQuery.post(ajaxurl, data_flow_save_as_post, function(response) {
				console.log(response);
				var link = response['data']['edit_draft_link'];
				if (link) {window.location.href = link;}
				
				
			});	
		
		});

	});

	


})( jQuery );
