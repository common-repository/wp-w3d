<?php
/**
 * Plugin Name.
 *
 * @package   WP_W3D_Admin
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 MBA Multimedia
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-wp-w3d.php`
 *
 * @package WP_W3D_Admin
 * @author  Your Name <email@example.com>
 */
class WP_W3D_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		/*
		 * Call $plugin_slug from public plugin class.
		 */
		$plugin = WP_W3D::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menus' ) );

		// Remove slug box for given post types
		add_action( 'admin_menu', array( $this, 'remove_slug_box' ) );
		add_action( 'admin_head', array( $this, 'hide_slug_box' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		/*
		 * Define custom functionality.
		 *
		 * Read more about actions and filters:
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		//add_action( '@TODO', array( $this, 'action_method_name' ) );
		//add_filter( '@TODO', array( $this, 'filter_method_name' ) );
		add_filter( 'cmb_meta_boxes', array( $this, 'add_w3dslider_metaboxes' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen(); // See add_plugin_admin_menus below to know screens IDs
		if ( $this->plugin_screen_hook_suffix == $screen->id
				|| 'wp-w3d_page_wp-w3d-ui' == $screen->id
				|| 'wp-w3d_page_wp-w3d-settings' == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), WP_W3D::VERSION );
		}

		global $post_type;
		if( 'w3dslider' == $post_type ) {
			wp_enqueue_style( $this->plugin_slug . '-admin-edit-slider-styles', plugins_url( 'assets/css/edit-slider.css', __FILE__ ), array(), WP_W3D::VERSION );	
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		//$screen = get_current_screen();
		//if ( $this->plugin_screen_hook_suffix == $screen->id ) {
		//	wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), WP_W3D::VERSION );
		//}

		global $post_type;
		if( 'w3dslider' == $post_type ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-edit-slider-script', plugins_url( 'assets/js/edit-slider.js', __FILE__ ), array( 'jquery' ), WP_W3D::VERSION );	
		}

	}

	/**
	 * Register the administration menus for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menus() {

		$capability = get_option('w3dwp_custom_capability', 'manage_options');

		/*
		 * "All sliders" menu = 'Custom post type' regular dashboard
		 */ 
		$this->plugin_screen_hook_suffix = add_menu_page(
			__( 'WP W3D', $this->plugin_slug ),
			__( 'WP W3D', $this->plugin_slug ),
			$capability,
			$this->plugin_slug, 
			array( $this, 'display_plugin_admin_pages' ),
			'dashicons-format-gallery'
		);

		/*
		 * Add a "UI Elements" page.
		 */
		add_submenu_page( 
			$this->plugin_slug,
			__( 'W3D UI Elements', $this->plugin_slug ),
			__( 'UI Elements', $this->plugin_slug ),
			$capability,
			'wp-w3d-ui',
			array( $this, 'display_plugin_admin_pages' )
		);

		/*
		 * Add a settings page for this plugin.
		 */
		add_submenu_page( 
			$this->plugin_slug,
			__( 'WP W3D General settings', $this->plugin_slug ),
			__( 'Settings', $this->plugin_slug ),
			$capability,
			'wp-w3d-settings',
			array( $this, 'display_plugin_admin_pages' )
		);

	}

	/**
	 * Render the proper admin page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_pages() {

		// Get current screen details
		$screen = get_current_screen();

// var_dump( "Current screen ID : " . $screen->id . " / " . $screen->base );

		if(strpos($screen->base, 'wp-w3d-ui') !== false) {
			include('views/ui-elements.php');
			return;
		}
		//} elseif(strpos($screen->base, 'ls-transition-builder') !== false) {
		//	include(LS_ROOT_PATH.'/views/transition_builder.php');
		//} elseif(strpos($screen->base, 'ls-style-editor') !== false) {
		//	include(LS_ROOT_PATH.'/views/style_editor.php');
		//} elseif(isset($_GET['action']) && $_GET['action'] == 'edit') {
		//	include(LS_ROOT_PATH.'/views/slider_edit.php');
		//} else {
		//	include(LS_ROOT_PATH.'/views/slider_list.php');
		//}

		include_once( 'views/general-settings.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}

	/**
	 * NOTE:     Actions are points in the execution of a page or process
	 *           lifecycle that WordPress fires.
	 *
	 *           Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// @TODO: Define your action hook callback here
	}

	public function remove_slug_box() {
	    remove_meta_box('slugdiv', 'w3dslider', 'normal');
	}

	/**
	 * Hide obsolete slug box on W3D sliders edit page
	 */
	public function hide_slug_box() {
	    global $post, $pagenow, $typenow;
	    if ( is_admin() && ( $pagenow=='post-new.php' OR $pagenow=='post.php' ) && $typenow=='w3dslider' ) {
	        echo "<script type='text/javascript'>
	            jQuery(document).ready(function($) {
	                jQuery('#edit-slug-box').hide();
	            });
	            </script>
	        ";
	    }
	}

	/**
	 * NOTE:     Filters are points of execution in which WordPress modifies data
	 *           before saving it or sending it to the browser.
	 *
	 *           Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// @TODO: Define your filter hook callback here
	}

	/**
	 * Add custom files to w3d custom post types
	 */	
	public function add_w3dslider_metaboxes( $meta_boxes ) {

	    $prefix = '_w3dslider_'; // Prefix for all fields

	    $meta_boxes['w3d_slider_basic_settings_box'] = array(
	        'id' => 'w3d_slider_basic_settings_box',
	        'title' => 'Basic settings',
	        'pages' => array('w3dslider'), // post type
	        'context' => 'normal',
	        'priority' => 'high',
	        'show_names' => true, // Show field names on the left
	        'fields' => array(
				array(
					'name'    => __( 'Slider type', 'cmb' ),
					'desc'    => __( 'Choose the type of the slider you want to add in your Website', 'cmb' ),
					'id'      => $prefix . 'type',
					'type'    => 'select',
					'options' => array(
						array( 'name' => __( 'Simple Parallax Slider', 'cmb' ), 'value' => 'simple_parallax', ),
						array( 'name' => __( 'Mouse Parallax Slider ', 'cmb' ), 'value' => 'mouse_parallax', ),
						array( 'name' => __( '3D Box Slider', 'cmb' ), 'value' => '3d_box', ),
					),
				),
				array(
					'name' => __( 'Short description', 'cmb' ),
					'desc' => __( 'Short description for this slider', 'cmb' ),
					'id'   => $prefix . 'short_description',
					'type' => 'textarea_small',
				),
				array(
					'name' => __( 'Interval', 'cmb' ),
					'desc' => __( 'Time between each transition (ms)', 'cmb' ),
					'id'   => $prefix . 'interval',
					'std'  => '5000',
					'type' => 'text_small',
				),
				array(
					'name' => __( 'Autoplay', 'cmb' ),
					'desc' => __( 'Check to enable automatic switch between slides', 'cmb' ),
					'id'   => $prefix . 'autoplay',
					'type' => 'checkbox',
				),
	        ),
	    );

	    $meta_boxes['w3d_slider_content_settings_box'] = array(
	        'id' => 'w3d_slider_content_settings_box',
	        'title' => 'Content settings',
	        'pages' => array('w3dslider'), // post type
	        'context' => 'normal',
	        'priority' => 'high',
	        'show_names' => true, // Show field names on the left
	        'fields' => array(
				array(
				    'name' => 'Slider content',
				    //'desc' => 'Configure the slider content',
				    'type' => 'title',
				    'id' => $prefix . 'content_title'
				),
				array(
					'name'    => __( 'Content type', 'cmb' ),
					'desc'    => __( 'Choose what kind of content you want to display in the slides', 'cmb' ),
					'id'      => $prefix . 'content_type',
					'type'    => 'select',
					'options' => array(
						array( 'name' => __( 'Custom static content', 'cmb' ), 'value' => 'static', ),
						array( 'name' => __( 'Posts', 'cmb' ), 'value' => 'post', ),
						//array( 'name' => __( 'Pages', 'cmb' ), 'value' => 'page', ),
					),
				),
	        ),
	    );

	    $meta_boxes['w3d_slider_advanced_settings_box_simple_parallax'] = array(
	        'id' => 'w3d_slider_advanced_settings_box_simple_parallax',
	        'title' => 'Advanced settings (Simple Parallax sliders)',
	        'pages' => array('w3dslider'), // post type
	        'context' => 'normal',
	        'priority' => 'high',
	        'show_names' => true, // Show field names on the left
	        'fields' => array(
				array(
				    'name' => 'Slider transitions',
				    //'desc' => 'Configure the slider transitions',
				    'type' => 'title',
				    'id' => $prefix . 'simple_prlx_transition_title'
				),
				array(
					'name'    => __( 'Transition type', 'cmb' ),
					'desc'    => __( 'Choose the type of transitions between slides', 'cmb' ),
					'id'      => $prefix . 'transition_type',
					'type'    => 'select',
					'options' => array(
						array( 'name' => __( 'Random effect', 'cmb' ), 'value' => 'random', ),
						array( 'name' => __( 'Rotation effect', 'cmb' ), 'value' => 'rotation', ),
						array( 'name' => __( 'Horizontal translation effect', 'cmb' ), 'value' => 'translation', ),
						array( 'name' => __( 'Scale effect', 'cmb' ), 'value' => 'scale', ),
					),
				),/*,array(
				    'name' => __( 'Content type', 'cmb' ),
				    'desc' => 'field description (optional)',
				    'std' => 'standard value (optional)',
				    'id' => $prefix . 'test_text',
				    'type' => 'text'
				),*/
				array(
				    'name' => 'Premium features',
				    'desc' => 'To access all features, you need to purchase the premium version of this plugin: ' . PLUGIN_PREMIUM_LINK,
				    'type' => 'title',
				    'id' => $prefix . 'simple_prlx_premium_title'
				),
	        ),
	    );

	    $meta_boxes['w3d_slider_advanced_settings_box_mouse_parallax'] = array(
	        'id' => 'w3d_slider_advanced_settings_box_mouse_parallax',
	        'title' => 'Advanced settings (Mouse Parallax sliders)',
	        'pages' => array('w3dslider'), // post type
	        'context' => 'normal',
	        'priority' => 'high',
	        'show_names' => true, // Show field names on the left
	        'fields' => array(
				/*,array(
				    'name' => __( 'Content type', 'cmb' ),
				    'desc' => 'field description (optional)',
				    'std' => 'standard value (optional)',
				    'id' => $prefix . 'test_text',
				    'type' => 'text'
				),*/
				array(
				    'name' => 'Premium features',
				    'desc' => 'To access all features, you need to purchase the premium version of this plugin: ' . PLUGIN_PREMIUM_LINK,
				    'type' => 'title',
				    'id' => $prefix . 'simple_prlx_premium_title'
				),
	        ),
	    );

	    $meta_boxes['w3d_slider_advanced_settings_box_3d_box'] = array(
	        'id' => 'w3d_slider_advanced_settings_box_3d_box',
	        'title' => 'Advanced settings (3D Box sliders)',
	        'pages' => array('w3dslider'), // post type
	        'context' => 'normal',
	        'priority' => 'high',
	        'show_names' => true, // Show field names on the left
	        'fields' => array(
				/*,array(
				    'name' => __( 'Content type', 'cmb' ),
				    'desc' => 'field description (optional)',
				    'std' => 'standard value (optional)',
				    'id' => $prefix . 'test_text',
				    'type' => 'text'
				),*/
				array(
				    'name' => 'Premium features',
				    'desc' => 'To access all features, you need to purchase the premium version of this plugin: ' . PLUGIN_PREMIUM_LINK,
				    'type' => 'title',
				    'id' => $prefix . 'simple_prlx_premium_title'
				),
	        ),
	    );

	    return $meta_boxes;
	}

}
