<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://jumpoff.io
 * @since      0.5.0
 *
 * @package    Jumpoff
 * @subpackage Jumpoff/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Jumpoff
 * @subpackage Jumpoff/admin
 * @author     Jesse Lee <jesse@jessequinnlee.com>
 */
class Jumpoff_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.5.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.5.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.5.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the WordPress admin area.
	 *
	 * @since    0.5.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Jumpoff_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Jumpoff_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/jumpoff-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for just the JumpOff admin page.
	 *
	 * @since    0.5.0
	 */
	public function jo_page_enqueue_styles($hook) {
		
		if ('toplevel_page_'.$this->plugin_name != $hook) {
			return;
		}
		
		wp_enqueue_style( $this->plugin_name . '_page' , plugin_dir_url( __FILE__ ) . 'css/jumpoff-admin-page.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for just the My Flows admin page.
	 *
	 * @since    0.5.0
	 */
	public function jo_my_flows_enqueue_styles($hook) {

		if ($this->plugin_name.'_page_'.$this->plugin_name.'-flows' != $hook) {
			
			return;
		}

		wp_enqueue_style( $this->plugin_name . '_my_flows' , plugin_dir_url( __FILE__ ) . 'css/jumpoff-admin-my-flows.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.5.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/jumpoff-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the JavaScript for the JumpOff Admin Page.
	 *
	 * @since    0.5.0
	 */
	public function jo_page_enqueue_scripts($hook) {

		if ('toplevel_page_'.$this->plugin_name != $hook) {
			return;
		}

		wp_enqueue_script( $this->plugin_name . '_page', plugin_dir_url( __FILE__ ) . 'js/jumpoff-admin-page.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the JavaScript for the JumpOff Admin Page.
	 *
	 * @since    0.5.0
	 */
	public function jo_my_flows_enqueue_scripts($hook) {

		if ($this->plugin_name.'_page_'.$this->plugin_name.'-flows' != $hook) {
			return;
		}

		wp_enqueue_script( $this->plugin_name . '_my_flows', plugin_dir_url( __FILE__ ) . 'js/jumpoff-admin-my-flows.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add admin menu item for JumpOff
	 *
	 * @since    0.5.0
	 */
	public function jumpoff_menu() {
		
		//Main Menu Item
		add_menu_page( 
			'JumpOff Options', 
			'JumpOff',
			'manage_options',
			$this->plugin_name,
			array($this, 'jumpoff_show_page'),
			'dashicons-edit',
			'6'
		);
		
		//Submenu Item
		add_submenu_page( 
			$this->plugin_name, 
			'My Flows', 
			'My Flows', 
			'manage_options', 
			$this->plugin_name.'-flows',
			array($this,'jumpoff_show_my_flows_page')
		);
		
	}

	/**
	 * Register Custom Post Type
	 *
	 * @since    0.5.0
	 */
	public function jo_flow_cpt() {
		
		$labels = array(
			'name'               => _x( 'Flows', 'post type general name', 'jumpoff' ),
			'singular_name'      => _x( 'Flow', 'post type singular name', 'jumpoff' ),
			'menu_name'          => _x( 'Flows', 'admin menu', 'jumpoff' ),
			'name_admin_bar'     => _x( 'Flow', 'add new on admin bar', 'jumpoff' ),
			'add_new'            => _x( 'Add New', 'flow', 'jumpoff' ),
			'add_new_item'       => __( 'Add New Flow', 'jumpoff' ),
			'new_item'           => __( 'New Flow', 'jumpoff' ),
			'edit_item'          => __( 'Edit Flow', 'jumpoff' ),
			'view_item'          => __( 'View Flow', 'jumpoff' ),
			'all_items'          => __( 'My Flows', 'jumpoff' ),
			'search_items'       => __( 'Search Flows', 'jumpoff' ),
			'parent_item_colon'  => __( 'Parent Flow:', 'jumpoff' ),
			'not_found'          => __( 'No flows found.', 'jumpoff' ),
			'not_found_in_trash' => __( 'No flows found in Trash.', 'jumpoff' )
		);

		$args = array(
			'labels'             => $labels,
	        'description'        => __( 'Description.', 'jumpoff' ),
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'flow' ),
			'capability_type'    => 'post',
			// 'capabilities'		 => array('create_posts' => false),
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 7,
			'supports'           => array( 'title', 'editor', 'author', 'excerpt', )
		);

		register_post_type( 'flow', $args );
		
		/*------- /Register Custom Post Type ---------*/
		

		/*------- Add draft status to flows ---------*/
		
		register_post_status( 'draft', array(
			'label'                     => _x( 'Draft', 'flow' ),
			'public'                    => true,
			'exclude_from_search'       => true,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Draft <span class="count">(%s)</span>', 'Draft <span class="count">(%s)</span>' )
		) );
		

	}

	/**
	 * Disable creating new posts through default interface
	 *
	 * @since    0.5.0
	 */	
	public function jo_disable_new_posts() {

	  global $pagenow;
	  
	  //get post type
	  if ( isset($_GET['post']) ) { $post_type = get_post_type( $_GET['post'] ); }
	  if ( isset($_GET['post_type']) ) { $post_type = $_GET['post_type']; }

	  if( is_admin() && isset($post_type) ){
			if( ($pagenow == 'edit.php' || $pagenow == 'post.php' ) && $post_type == 'flow' )  {
				wp_enqueue_style( 'jumpoff_hide_add_new_css', '/wp-content/plugins/jumpoff/admin/css/jumpoff-hide-add-new.css');
			}  
		}

	}	

	/**
	 * Display JumpOff Page
	 *
	 * @since    0.5.0
	 */
	public function jumpoff_show_page() {

		include_once plugin_dir_path( __FILE__ ) . 'partials/jumpoff-admin-display.php';

	}

	/**
	 * Display JumpOff Flows Page
	 *
	 * @since    0.5.0
	 */
	public function jumpoff_show_my_flows_page() {

		include_once plugin_dir_path( __FILE__ ) . 'partials/jumpoff-admin-my-flows-display.php';

	}

	/**
	 * Display JumpOff DashBoard Widget
	 *
	 * @since    0.5.0
	 */
	public function jo_dash_widget_display() {

		include_once plugin_dir_path( __FILE__ ) . 'partials/jumpoff-admin-dashboard-widget-display.php';

	}
	

	/**
	 * Register Dashboard Widget
	 *
	 * @since    0.5.0
	 */
	public function jo_dashboard_widget() {

		global $wp_meta_boxes;

		wp_add_dashboard_widget( 
			'jo_dash_widget', 
			'JumpOff', 
			array( &$this, 'jo_dash_widget_display' )
		);

		$dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

		$jo_widget = array( 'jo_dash_widget' => $dashboard['jo_dash_widget'] );
 		unset( $dashboard['jo_dash_widget'] );

 		$sorted_dashboard = array_merge( $jo_widget, $dashboard );
 		$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;

	}

	/**
	 * Add flow star meta box to flow posts
	 *
	 * @since    0.5.0
	 */	
	public function jo_add_flow_meta_boxes() {

	  add_meta_box(
	    'jumpoff_flow_flag',      // Unique ID
	    esc_html__( 'Starred', 'example' ),    // Title
	    array($this, 'jo_flow_star_meta_box'),   // Callback function
	    'flow',         // Admin page (or post type)
	    'side',         // Context
	    'default'         // Priority
	  );
	}

	/**
	 * Display flow Star custom meta box
	 *
	 * @since    0.5.0
	 */	
	public function jo_flow_star_meta_box($post) { 

		//Add nonce field so we can check for it later
		wp_nonce_field( 'jo_save_post_flag' , 'jo_star_meta_box_nonce' );

		//Get current value of flag
		$is_starred = get_post_meta( $post->ID, 'jumpoff_flow_flag', true ); 
		
		?>

		  <p>
		    <label for="jo_flow_star"><?php _e( "Star this Flow", 'example' ); ?></label>
		    <br />
		    <input  type="checkbox" name="jo_flow_star" id="jo_flow_star" <?php if( $is_starred == true ) { ?>checked="checked"<?php } ?> />
		  </p>
		  <?php
	}	

	/**
	 * When the post is saved, saves flag/no flag for Flow.
	 *
	 *@since 	  0.5.0
	 *@param int $post_id The ID of the post being saved.
	 */
	public function jo_save_post_flag( $post_id ) {

		$post_id = (int) $post_id;
		
		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['jo_star_meta_box_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['jo_star_meta_box_nonce'], 'jo_save_post_flag' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
		
		// Make sure that it is set.
		if ( ! isset( $_POST['jo_flow_star'] ) ) {
			$is_starred = false;
		}
		else {
			$is_starred = true;
		}
		
		// Update the meta field in the database.
		update_post_meta( $post_id, 'jumpoff_flow_flag', $is_starred );
	}

	/**
	 * Display button to edit flow as a post
	 *
	 *@since 	  0.5.0
	 *
	 */
	public function jo_edit_as_post() {
		
		$my_post_type = 'flow';

		global $post;
		
		if($post->post_type == $my_post_type){
			$flow_id = $post->ID; 
			print '<a href="#"><div id="jo_edit_as_post_'.$post->ID.'" class="jo_edit_as_post jo_button">Clone to Post</div></a>';
		}


	}


	/**
	 * Remove publishing actions from flow edit pages
	 *
	 *@since 	  0.5.0
	 *@param int $post_id The ID flow on which the button is showing.
	 */
	public function jo_hide_publishing_actions() {
		
		$my_post_type = 'flow';
        global $post;
        
        error_log($my_post_type . '  ' . $post->post_type );
        if($post->post_type == $my_post_type){
            echo '
                <style type="text/css">
                    #misc-publishing-actions,
                    #publishing-action, #preview-action {
                        display:none;
                    }
                    #save-action {
                    	height:40px;
                	}
                </style>
            ';
        }
	}

}
