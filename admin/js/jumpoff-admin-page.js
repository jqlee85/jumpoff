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

		/*----- JumpOff Page Functionality -------*/

		//prevent user from using delete or backspace keys
		jQuery("#jo_flow_box").keydown(function(e) {
		    e.keyCode; // this value
		    if ( e.keyCode == (8 || 46) ) return false;

		});

		//pad time values
		function str_pad_left(string,pad,length) {
		    return (new Array(length+1).join(pad)+string).slice(-length);
		}

		//return formatted time

		function getFormattedTime(counterTime) {
		    var minutes = Math.floor(counterTime / 60);
		    var seconds = counterTime - minutes * 60;
		    var hours = Math.floor(counterTime / 3600);
			var formattedTime = '';
			
			if (hours >= 1) {
				formattedTime = hours.toString() + ':';
			}
			if (minutes > 0) {
				if (hours > 0) {formattedTime = formattedTime + str_pad_left(minutes,0,2) + ':';}
				else {formattedTime = formattedTime + minutes.toString() + ':';}
			}
			if (seconds >= 0) {
				if (minutes > 0) {formattedTime = formattedTime + str_pad_left(seconds,0,2);}
				else {formattedTime = formattedTime + seconds.toString();}
			}
		    return formattedTime;
		}	



		//prevent cursor from moving
		var input = document.getElementById("jo_flow_box");
		
		var reset = function (e) {
		    var len = this.value.length;
		    this.setSelectionRange(len, len);
		};
		 
		input.addEventListener('focus', reset, false);
		input.addEventListener('mouseup', reset, false);
		input.addEventListener('keyup', reset, false);
		input.addEventListener('keydown', reset, false);


		//SetTimeout to show end of flow options
		jQuery('#jo_prompt_me').click(function() {
			//hide recent flows
			jQuery('#jo_recent_flows_table').hide();
			//hide flow time options and jumpoff image link
			jQuery('#jo_prompt_times_container').hide();
			jQuery('#jo_flow_counter_wrapper').show();
			

			//get chosen flow length, default to 5 min if wrong value inputted
			var flowTime = jQuery('.jo_prompt_time.jo_checked').data('value');
			if ( flowTime == 60 || flowTime == 120 || flowTime == 300 || flowTime == 600 ){
				var sec = flowTime;
			}
			else { var sec = 300; }
			
			//end timer if end button clicked
			jQuery('#jo_flow_end').click(function(){
				sec = 0;
			});

			//start counter
			jQuery('#jo_flow_counter').fadeTo(7000, 0);
			var timer = setInterval(function() { 
			   sec--;
			 
			   jQuery('#jo_flow_counter').text(getFormattedTime(sec,0,2));
			   if (sec < 1) {
			    	clearInterval(timer);
			   		jQuery('#jo_flow_counter_wrapper').hide();
			   		jQuery('#jo_flow_end_overlay').addClass('jo_show');
			   		jQuery('#jo_flow_box').blur();

			   		//archive flow
					console.log('archive click function ran');
					var flowContent = jQuery('#jo_flow_box').val();
					var flowTitle = jQuery('#jo_prompt').text();
					console.log(flowTitle + ' and ' + flowContent);

					var data_flow_archive  = {
						'action': 'jo_archive_flow',
						'flow_content': flowContent,
						'flow_title': flowTitle
					};

					jQuery.post(ajaxurl, data_flow_archive, function(response) {
						console.log('flow archived');
						console.log(response['data']['flow_id']);
						jQuery('#jo_flow_star').attr('id' , '#jo_flow_star_' + response['data']['flow_id']);
						console.log(response);
					});
					
			   }
			}, 1000);


			
			//show the counter on hover
			setTimeout(function() {
				jQuery('#jo_show_counter').hover(function() { 
				    jQuery('#jo_flow_counter').stop().animate({"opacity": .7},200);
				},function() { 
				    jQuery('#jo_flow_counter').stop().animate({"opacity": 0},1000); 
				});
			}, 10);

			//hide prompt me button when clicked
			jQuery(this).removeClass('jo_show').addClass('jo_hide');
			jQuery('#jo_prompt').removeClass('jo_hide').addClass('jo_show');


		});

		//FIX THIS resets focus to textarea if focus moved (need to make cursor move back to end still)
		// Focus on load
		jQuery('#jo_flow_box').focus();
		// Force focus
		jQuery('#jo_flow_box').focusout( function(){
		   if ( 
		   		!jQuery('#jo_flow_end_overlay').hasClass('jo_show') ) {
		   		jQuery('#jo_flow_box').focus();
			}
		});

		// Prompt Time Selector Functionality

		jQuery('.jo_prompt_time').click( function(){
			jQuery('.jo_prompt_time').removeClass('jo_checked');
			jQuery(this).addClass('jo_checked');

		});



	/*----- END JumpOff Page Functionality -------*/


	/*----- JumpOff AJAX Functionality -------*/

		//get new prompt
		var data = {
			'action': 'jo_get_new_prompt'
		};

		jQuery('#jo_prompt_me').click(function(){
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function(response) {
				jQuery('#jo_prompt').empty();
				jQuery('#jo_prompt').append(response);
			});
		});
		
		//on click, change star/unstar value for post
		jQuery('.jo_flow_star').click(function(){
			
			//get id
			var raw_id = jQuery(this).attr('id');
			var id_array = raw_id.split('_');
			var flow_id = parseInt( id_array[id_array.length - 1] );

			//get if checked
			var is_starred = jQuery(this).attr('data-checked');

			var data = {
				'action': 'jo_save_flow_star',
				'flow_id': flow_id,
				'is_starred': is_starred
			};

			jQuery.post(ajaxurl, data, function(response) {
				console.log(response);
				
				//update checked value on flow if successful
				if (response['success'] == true){
						
						var checked = response['data']['starred'];
						console.log(flow_id + '  ' + checked);
						jQuery('.jo_flow_star').attr('data-checked', checked );
				}
				
			});
		});

		


		// Flow is done, reload page
		jQuery('#jo_flow_done').click(function(){
			location.reload();

		});


		//save flow as draft and edit
		jQuery('#jo_flow_edit_now').click(function(){
			console.log('submit function ran');
			var flowContent = jQuery('#jo_flow_box').val();
			var flowTitle = jQuery('#jo_prompt').text();
			console.log(flowTitle + ' and ' + flowContent);

			var data_flow_save_draft  = {
				'action': 'jo_save_flow_as_draft',
				'flow_content': flowContent,
				'flow_title': flowTitle
			};

			jQuery.post(ajaxurl, data_flow_save_draft, function(response) {
				console.log(response);
				var flowID = JSON.parse(response)['flow_id'];
				console.log(flowID);
				var link = JSON.parse(response)['edit_draft_link'];
				if (link) {window.location.href = link;}
				else {location.reload();}
				
			});

		});

	});

	/*----- END JumpOff AJAX Functionality -------*/


})( jQuery );
