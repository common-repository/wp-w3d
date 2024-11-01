<?php
/**
 * Plugin Name.
 *
 * @package   WP_W3D
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 MBA Multimedia
 */

require_once( 'classes/class.responsive3dgrid.php' );
require_once( 'classes/class.slider.php' );

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-wp-w3d-admin.php`
 *
 * @package WP_W3D
 * @author  Your Name <email@example.com>
 */
class WP_W3D {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'wp-w3d';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	protected $plugin_url = '';

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		// Set abs path
		$this->plugin_url = plugins_url('', dirname(__FILE__) ) . '/';

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		// add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'init',  array( $this, 'register_w3d_scripts' )  );
		// add_action('wp_footer', array( $this, 'load_only_needed_scripts' ) ); // Look instead for calls to 'wp_print_scripts'

		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action( 'init', array( $this, 'initialize_cmb_meta_boxes' ), 9999 );
		add_action( 'init', array( $this, 'register_w3dslider_custom_post_type' ) ); 
		// Customize CPT columns
		add_filter( 'manage_edit-w3dslider_columns', array( $this, 'admin_w3dslider_columns' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'populate_w3dslider_columns' ) );
		//add_filter( 'manage_edit-lieu_sortable_columns', array( $this, 'sort_w3dslider_columns' ) );
		//
		// @TODO customize CPT messages too ( http://thomasmaxson.com/2013/update-messages-for-custom-post-types/ )

		//add_filter( '@TODO', array( $this, 'filter_method_name' ) );

		// W3D content shortcodes
		add_shortcode( 'w3dslider', array( $this, 'w3dslider_shortcode_call' ) );
		add_shortcode( 'responsive3dgrid', array( $this, 'responsive3dgrid_shortcode_call' ) );

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		// @TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-simple-parallax-slider', plugins_url( 'assets/css/sliders/w3d-simple-parallax-slider.css', __FILE__ ), array(), self::VERSION );
		wp_enqueue_style( $this->plugin_slug . '-3d-box-slider', plugins_url( 'assets/css/sliders/w3d-3d-box-slider.css', __FILE__ ), array(), self::VERSION );
		wp_enqueue_style( $this->plugin_slug . '-mouse-parallax-slider', plugins_url( 'assets/css/sliders/w3d-mouse-parallax-slider.css', __FILE__ ), array(), self::VERSION );

		wp_enqueue_style( $this->plugin_slug . '-responsive-3d-grid', plugins_url( 'assets/css/ui-elements/grids/responsive-3d-grid.css', __FILE__ ), array(), self::VERSION );

		wp_enqueue_style( $this->plugin_slug . '-plugin-core-styles', plugins_url( 'assets/css/core.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-modernizr', plugins_url( 'assets/js/libs/modernizr.min.js', __FILE__ ), array(), self::VERSION, false );
		wp_enqueue_script( $this->plugin_slug . '-hoverintent', plugins_url( 'assets/js/libs/jquery.hoverIntent.min.js', __FILE__ ), array( 'jquery' ), self::VERSION, true );
		wp_enqueue_script( $this->plugin_slug . '-jparallax', plugins_url( 'assets/js/libs/jquery.parallax.js', __FILE__ ), array( 'jquery' ), self::VERSION, true ); // @TODO register only then enqueue only when needed
		wp_enqueue_script( $this->plugin_slug . '-jstween', plugins_url( 'assets/js/libs/jstween/jstween-1.1.min.js', __FILE__ ), array( 'jquery' ), self::VERSION, true );

		wp_enqueue_script( $this->plugin_slug . '-simple-parallax-slider', plugins_url( 'assets/js/sliders/w3d-simple-parallax-slider.js', __FILE__ ), array( 'jquery' ), self::VERSION, true ); // @TODO register only then enqueue only when needed
		wp_enqueue_script( $this->plugin_slug . '-3d-box-slider', plugins_url( 'assets/js/sliders/w3d-3d-box-slider.js', __FILE__ ), array( 'jquery' ), self::VERSION, true ); // @TODO register only then enqueue only when needed
		wp_enqueue_script( $this->plugin_slug . '-mouse-parallax-slider', plugins_url( 'assets/js/sliders/w3d-mouse-parallax-slider.js', __FILE__ ), array( 'jquery' ), self::VERSION, true ); // @TODO register only then enqueue only when needed
		
		wp_enqueue_script( $this->plugin_slug . '-responsive-3d-grid', plugins_url( 'assets/js/ui-elements/grids/responsive-3d-grid.js', __FILE__ ), array( 'jquery' ), self::VERSION, true );

		wp_enqueue_script( $this->plugin_slug . '-plugin-core-scripts', plugins_url( 'assets/js/core.js', __FILE__ ), array( 'jquery' ), self::VERSION, true );
	}
		/**
	 * Register public-facing JavaScript files (enqueue will be called only when needed).
	 *
	 * @since    1.0.0
	 */
	public function register_w3d_scripts() {
		wp_register_script( $this->plugin_slug . '-modernizr', plugins_url( 'assets/js/libs/modernizr.min.js', __FILE__ ), array(), self::VERSION, false );
		wp_register_script( $this->plugin_slug . '-hoverintent', plugins_url( 'assets/js/libs/jquery.hoverIntent.min.js', __FILE__ ), array( 'jquery' ), self::VERSION, true );
		wp_register_script( $this->plugin_slug . '-jparallax', plugins_url( 'assets/js/libs/jquery.parallax.js', __FILE__ ), array( 'jquery' ), self::VERSION, true ); // @TODO register only then enqueue only when needed
		wp_register_script( $this->plugin_slug . '-jstween', plugins_url( 'assets/js/libs/jstween/jstween-1.1.min.js', __FILE__ ), array( 'jquery' ), self::VERSION, true );
		wp_register_script( $this->plugin_slug . '-simple-parallax-slider', plugins_url( 'assets/js/sliders/w3d-simple-parallax-slider.js', __FILE__ ), array( 'jquery' ), self::VERSION, true ); // @TODO register only then enqueue only when needed
		wp_register_script( $this->plugin_slug . '-3d-box-slider', plugins_url( 'assets/js/sliders/w3d-3d-box-slider.js', __FILE__ ), array( 'jquery' ), self::VERSION, true ); // @TODO register only then enqueue only when needed
		wp_register_script( $this->plugin_slug . '-mouse-parallax-slider', plugins_url( 'assets/js/sliders/w3d-mouse-parallax-slider.js', __FILE__ ), array( 'jquery' ), self::VERSION, true ); // @TODO register only then enqueue only when needed
		wp_register_script( $this->plugin_slug . '-responsive-3d-grid', plugins_url( 'assets/js/ui-elements/grids/responsive-3d-grid.js', __FILE__ ), array( 'jquery' ), self::VERSION, true );
		wp_register_script( $this->plugin_slug . '-plugin-core-scripts', plugins_url( 'assets/js/core.js', __FILE__ ), array( 'jquery' ), self::VERSION, true );
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// @TODO: Define your action hook callback here
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// @TODO: Define your filter hook callback here
	}

	/**
	 * Helper library to manage easily custom fields inputs
	 */
	function initialize_cmb_meta_boxes() {
	    if ( !class_exists( 'cmb_Meta_Box' ) ) {
	        require_once( plugin_dir_path( __FILE__ ) .'../includes/metabox/init.php' );
	    }
	}

	/**
	 * New custom type to manage sliders
	 */
	function register_w3dslider_custom_post_type() {

		$labels = array(
			'name'                => _x( 'W3D Sliders', 'Post Type General Name', 'wp-w3d' ),
			'singular_name'       => _x( 'W3D Slider', 'Post Type Singular Name', 'wp-w3d' ),
			'menu_name'           => __( 'W3D Sliders', 'wp-w3d' ),
			'parent_item_colon'   => __( 'Parent Slider:', 'wp-w3d' ),
			'all_items'           => __( 'All Sliders', 'wp-w3d' ),
			'view_item'           => __( 'View Slider', 'wp-w3d' ),
			'add_new_item'        => __( 'Add New W3D Slider', 'wp-w3d' ),
			'add_new'             => __( 'Add New Slider', 'wp-w3d' ),
			'edit_item'           => __( 'Edit Slider', 'wp-w3d' ),
			'update_item'         => __( 'Update Slider', 'wp-w3d' ),
			'search_items'        => __( 'Search Slider', 'wp-w3d' ),
			'not_found'           => __( 'Not found', 'wp-w3d' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'wp-w3d' ),
		);
		$capabilities = array(
			'edit_post'           => 'edit_w3dslider',
			'read_post'           => 'read_w3dslider',
			'delete_post'         => 'delete_w3dslider',
			'edit_posts'          => 'edit_w3dsliders',
			'edit_others_posts'   => 'edit_others_w3dsliders',
			'publish_posts'       => 'publish_w3dsliders',
			'read_private_posts'  => 'read_private_w3dslider',
		);
		$args = array(
			'label'               => __( 'w3dslider', 'wp-w3d' ),
			'description'         => __( 'W3D Plugin slider', 'wp-w3d' ),
			'labels'              => $labels,
			'supports'            => array( 'title', ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => "wp-w3d",
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 100, 
			'menu_icon'           => 'dashicons-format-gallery',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type' => 'post',
			//'capabilities'        => // $capabilities,
		);
		register_post_type( 'w3dslider', $args );

	}
	/**
	 * Add custom columns to W3D sliders
	 */
	function admin_w3dslider_columns( $columns ) {
		
		$columns["w3dslider_type"] = __("Slider type", "wp-w3d");
		$columns["w3dslider_contenttype"] = __("Content type", "wp-w3d");
		$columns["w3dslider_shortcode"] = __("Shortcode", "wp-w3d");
		return $columns;
	}

	function populate_w3dslider_columns( $column ) {
		if ( 'w3dslider_type' == $column ) {
			$w3dslider_type = esc_html( get_post_meta( get_the_ID(), '_w3dslider_type', true ) );
			echo $w3dslider_type;
		}
		elseif ( 'w3dslider_contenttype' == $column ) {
			$w3dslider_contenttype = esc_html( get_post_meta( get_the_ID(), '_w3dslider_content_type', true ) );
			echo $w3dslider_contenttype;
		}
		elseif ( 'w3dslider_shortcode' == $column ) {
			$w3dslider_shortcode = "<pre>[w3dslider id=\"".get_the_ID()."\"]</pre>";
			echo $w3dslider_shortcode;
		}
	}

	/**
	 * Enable W3D shortcodes
	 */
	public function w3dslider_shortcode_call( $atts ){
		// Extracting parameters
		extract( shortcode_atts( array (
										'id' => '',
									   ), $atts ) );

		$post_type = get_post_type($id);
		if ($post_type &&  $post_type == "w3dslider" && "publish" == get_post_status($id)) 
		{
			$type = get_post_meta( $id, '_w3dslider_type', true );
			$content_type = get_post_meta( $id, '_w3dslider_content_type', true );
			$slide_interval = get_post_meta( $id, '_w3dslider_interval', true );
			$autoplay = (get_post_meta( $id, '_w3dslider_autoplay', true ) === "on") ? '1' : '0';

			$W3DWordpressCustomParams = array(
												'interval' => $slide_interval,
												'autoplay' => $autoplay,
											);

// var_dump($W3DWordpressCustomParams);

			// send PHP parameters to javascripts
			wp_localize_script($this->plugin_slug . '-plugin-core-scripts', 'W3DWordpressCustomParams', $W3DWordpressCustomParams);

			// Call scripts only if needed ( Cf. http://scribu.net/wordpress/optimal-script-loading.html )
			wp_print_scripts($this->plugin_slug . '-modernizr');
			wp_print_scripts($this->plugin_slug . '-hoverintent');
			wp_print_scripts($this->plugin_slug . '-plugin-core-scripts');
			
			if ($type == "simple_parallax") 
			{
				wp_print_scripts($this->plugin_slug . '-jparallax');
				wp_print_scripts($this->plugin_slug . '-simple-parallax-slider');
			}
			elseif ($type == "mouse_parallax")
			{
				wp_print_scripts($this->plugin_slug . '-mouse-parallax-slider');
			}
			else // '3d_box'
			{
				wp_print_scripts($this->plugin_slug . '-3d-box-slider');
			}

			// Generate and return HTML output
			$slider = new W3dSlider( $type, $content_type );
			return $slider->getHtml();
		}
		return __("<p>Sorry: There is no W3D slider with this ID in the database</p>", "wp-w3d");
	}

	public function responsive3dgrid_shortcode_call( $atts ){
		// Extracting parameters
		extract( shortcode_atts( array (
										'type' => '1',
										'content' => 'post',
										'include' => array(),
										'exclude' => array(),
										'order' => 'DESC',
									   ), $atts ) );
		
		// Call scripts only if needed ( Cf. http://scribu.net/wordpress/optimal-script-loading.html )
		wp_print_scripts($this->plugin_slug . '-modernizr');
		wp_print_scripts($this->plugin_slug . '-hoverintent');
		wp_print_scripts($this->plugin_slug . '-jstween');
		wp_print_scripts($this->plugin_slug . '-responsive-3d-grid');
		wp_print_scripts($this->plugin_slug . '-plugin-core-scripts');

		// Generate and return HTML output
		$responsive_grid = new W3dResponsiveGrid( $type, $content, $include, $exclude, $order );
		return $responsive_grid->getHtml();
	}

}