<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://jessequinnlee.com
 * @since      0.5.0
 *
 * @package    Jumpoff
 * @subpackage Jumpoff/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.5.0
 * @package    Jumpoff
 * @subpackage Jumpoff/includes
 * @author     Jesse Lee <jesse@jessequinnlee.com>
 */
class Jumpoff {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.5.0
	 * @access   protected
	 * @var      Jumpoff_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.5.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.5.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.5.0
	 */
	public function __construct() {

		$this->plugin_name = 'jumpoff';
		$this->version = '0.5.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_ajax_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Jumpoff_Loader. Orchestrates the hooks of the plugin.
	 * - Jumpoff_i18n. Defines internationalization functionality.
	 * - Jumpoff_Admin. Defines all hooks for the admin area.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.5.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-jumpoff-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-jumpoff-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-jumpoff-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'ajax/class-jumpoff-ajax.php';

		$this->loader = new Jumpoff_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Jumpoff_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.5.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Jumpoff_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.5.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Jumpoff_Admin( $this->get_plugin_name(), $this->get_version() );
		
		//Register Flow CPT
		$this->loader->add_action('init', $plugin_admin, 'jo_flow_cpt');
		
		//Add Star Metaboxes to Flow posts
		$this->loader->add_action('add_meta_boxes', $plugin_admin, 'jo_add_flow_meta_boxes');

		//Save Star metabox with Flow post save
		$this->loader->add_action('save_post', $plugin_admin, 'jo_save_post_flag' );

		//Add Edit as Post button to flow edit page
		$this->loader->add_action( 'post_submitbox_start', $plugin_admin, 'jo_edit_as_post');

		//Hide Publishing stuff from Flow edit pages
		$this->loader->add_action( 'admin_head-post.php', $plugin_admin, 'jo_hide_publishing_actions' );
		$this->loader->add_action( 'admin_head-post-new.php', $plugin_admin, 'jo_hide_publishing_actions' );

		//JumpOff Admin Menu
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'jumpoff_menu' );
		
		//Disable new posts through default editor
		$this->loader->add_action('admin_menu', $plugin_admin, 'jo_disable_new_posts');
		
		//Load CSS
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		
		//Load conditional CSS just on JumpOff page
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'jo_page_enqueue_styles');

		//Load conditional CSS just on My Flows page
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'jo_my_flows_enqueue_styles');

		//Load JS
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		//Load conditional JS just on JumpOff page
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'jo_page_enqueue_scripts');

		//Load conditional JS just on My Flows page
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'jo_my_flows_enqueue_scripts');

		//Dashboard Widget
		$this->loader->add_action( 'wp_dashboard_setup', $plugin_admin, 'jo_dashboard_widget', 99 );

	}

	/**
	 * Register all of the hooks related to the AJAX area functionality
	 * of the plugin.
	 *
	 * @since    0.5.0
	 * @access   private
	 */
	private function define_ajax_hooks() {

		$plugin_ajax = new JumpOff_AJAX( $this->get_plugin_name(), $this->get_version() );

		//JumpOff AJAX Handling
		$this->loader->add_action( 'wp_ajax_jo_get_new_prompt', $plugin_ajax, 'jo_get_new_prompt_callback' );
		$this->loader->add_action( 'wp_ajax_jo_save_flow_as_draft', $plugin_ajax, 'jo_save_flow_as_draft');
		$this->loader->add_action( 'wp_ajax_jo_archive_flow', $plugin_ajax, 'jo_archive_flow');
		$this->loader->add_action( 'wp_ajax_jo_save_flow_star', $plugin_ajax, 'jo_save_flow_star');
		$this->loader->add_action( 'wp_ajax_jo_save_flow_as_post', $plugin_ajax, 'jo_save_flow_as_post');

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.5.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.5.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.5.0
	 * @return    Jumpoff_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.5.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


}
