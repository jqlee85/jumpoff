<?php

/**
 * The backend AJAX handlers of the plugin.
 *
 * @link       http://jessequinnlee.com
 * @since      1.0.0
 *
 * @package    Jumpoff
 * @subpackage Jumpoff/ajax
 */

/**
 * The Backend AJAX handling functionality of the plugin.
 *
 * 
 *
 * @package    Jumpoff
 * @subpackage Jumpoff/ajax
 * @author     Jesse Lee <jesse@jumpoff.io>
 */
class Jumpoff_AJAX {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Saves star/unstar value for Flows on Recent Flows page
	 *
	 *@since 	  1.0.0
	 *@param      Gets parameters form AJAX $_POST
	 */
	public function jo_save_flow_star() {

		global $wpdb;

		//get flow id
		$flow_id = (int) $_POST['flow_id'];
		
		//get if flow was starred and invert
		if ( isset( $_POST['is_starred'] ) ) { 
			
			//make sure value is 1 or null and invert value
			if ( $_POST['is_starred'] == 1 || $_POST['is_starred'] == '1' ){
				$is_starred = false;
			}
			elseif ( $_POST['is_starred'] == null || $_POST['is_starred'] == false ) {
				$is_starred = 1;
			}
			else {
				wp_send_json_error( array(  'jo_success' => false, 'message' => 'star value 1 or null' ) );
				
			}
		}
		else { 
			$is_starred = false;
			
		}
		
		// Update the meta field in the database.
		$is_success = update_post_meta( $flow_id, 'jumpoff_flow_flag', $is_starred );
		
		if ( $is_success ) { $message = 'UPDATED '. $flow_id . ' jumpoff_flow_flag ' . $is_starred . ' | ' . $_POST['is_starred']	;}
		else { $message = 'not updated '. $flow_id . ' jumpoff_flow_flag ' . $is_starred . ' | ' . $_POST['is_starred'];}
		$starred = get_post_meta( $flow_id, 'jumpoff_flow_flag', false );
		$starred = $starred[0];

		wp_send_json_success( array( 'success' => $is_success, 'message' => $message, 'starred' => $starred, 'id' => $flow_id ) );
		

	}

	/**
	 * Returns either 'a' or 'an' depending on a word
	 *
	 *@since 	  1.0.0
	 *@param      string 	$word 		to check if gets 'an' or 'a'
	 */
	public function jo_a_or_an($word) {

		$vowels = array('a','e','i','o','u');
		$exceptions = array('hour','unique','universal','honor','honorable','honest','honesty','one','unicorn');

		$an = false;
		if ( in_array( substr($word,0,1), $vowels ) ) {
			$an = true;
		}
		if ( in_array( $word, $exceptions ) ){
			$an = !$an;
		}

		if ($an) { return 'an'; }
		else { return 'a'; }

	}

	/**
	 * Generates and returns a random 3 word prompt from the JumpOff database
	 *
	 *@since 	  1.0.0
	 */
	public function jo_get_random_prompt() {
		
		global $wpdb;
		$verb = $wpdb->get_results( "SELECT word FROM ".$wpdb->prefix."jo_prompts WHERE word_class = 'verb' ORDER BY RAND() LIMIT 1", OBJECT );
		$noun = $wpdb->get_results( "SELECT word FROM ".$wpdb->prefix."jo_prompts WHERE word_class = 'noun' ORDER BY RAND() LIMIT 1", OBJECT );

		$prompt = $verb[0]->word . ' ' . $this->jo_a_or_an($noun[0]->word) . ' ' . $noun[0]->word;
		
		return $prompt;

	}

	/**
	 * Handles AJAX request for a new prompt
	 *
	 *@since 	  1.0.0
	 *@param      Gets parameters form AJAX $_POST
	 */
	public function jo_get_new_prompt_callback() {
		global $wpdb; 
		echo $this->jo_get_random_prompt();
		wp_die(); 
	}

	/**
	 * Saves a Flow as a Post draft
	 *
	 *@since 	  1.0.0
	 *@param      Gets parameters form AJAX $_POST
	 */
	public function jo_save_flow_as_draft() {
		global $wpdb; // this is how you get access to the database

	 		//get current timestamp
	 	$timestamp = time();

	 	// Create post object
		$my_post = array(
		  'post_type'	  => 'post',
		  'post_title'    => sanitize_text_field($_POST['flow_title']),
		  'post_content'  => sanitize_text_field($_POST['flow_content']),
		  'post_status'   => 'draft'
		  
		);

		// Insert the post into the database as a post
		$flow_id = wp_insert_post( $my_post, true );
		$flow_data = array('flow_id' => $flow_id, 'edit_draft_link' => get_edit_post_link($flow_id, '') );
		echo json_encode( $flow_data );
		wp_die(); // this is required to terminate immediately and return a proper response

	}

	/**
	 * Saves a Flow as a Post draft
	 *
	 *@since 	  1.0.0
	 *
	 */
	public function jo_save_flow_as_post() {

		global $wpdb; // this is how you get access to the database
		

	 	// Create post object
		$my_post = array(
		  'post_type'	  => 'post',
		  'post_title'    => $_POST['flow_title'],
		  'post_content'  => $_POST['flow_content'],
		  'post_status'   => 'draft'
		);

		// Insert the post into the database as a post
		$flow_id = wp_insert_post( $my_post, true );
		$flow_data = array(
			'flow_id' => $flow_id, 
			'edit_draft_link' => get_edit_post_link($flow_id, '') 

		);
		wp_send_json_success( $flow_data  );

	}

	/**
	 * Saves a Flow as a Flow draft, returns a flow's id and an edit link as JSON for front-end AJAX handlers
	 *
	 *@since 	  1.0.0
	 *@param      Gets parameters form AJAX $_POST
	 */
	public function jo_archive_flow() {
		global $wpdb; // this is how you get access to the database

	 	//get current timestamp
	 	$timestamp = time();

	 	// Create post object
		$my_post = array(
		  'post_type'	  => 'flow',
		  'post_title'    => $_POST['flow_title'],
		  'post_content'  => $_POST['flow_content'],
		  'post_status'   => 'draft'
		  //'post_author'   => $_POST['flow_author']
		);

		// Insert the post into the database
		$flow_id = wp_insert_post( $my_post, true );
		$flow_data = array('flow_id' => $flow_id, 'edit_draft_link' => get_edit_post_link($flow_id, '') );
		wp_send_json_success( $flow_data ); 

	}

}
