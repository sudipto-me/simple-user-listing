<?php
/**
 * Plugin Name: User Listing
 * Plugin URI:  https://github.com/sudipto-me/simple-user-listing
 * Description: The best User Listing Plugin!
 * Version:     1.0.0
 * Author:     sudipto-me
 * Author URI:  https://github.com/sudipto-me
 * License:     GPLv2+
 * Text Domain: user-listing
 * Tested up to: 5.8.1
 */

// don't call the file directly
defined( 'ABSPATH' ) || exit();

/**
 * User_Listing class.
 *
 * @class User_Listing contains everything for the plugin.
 */
class User_Listing {
	/**
	 * User_Listing version.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $version = '1.0.0';

	/**
	 * This plugin's instance
	 *
	 * @var User_Listing The one true WC_Serial_Numbers
	 * @since 1.0.0
	 */
	private static $instance;

	/**
	 * Main User_Listing Instance
	 *
	 * Insures that only one instance of User_Listing exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @return User_Listing The one true User_Listing
	 * @since  1.0.0
	 * @static var array $instance
	 */
	public static function init() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof User_Listing ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Return plugin version.
	 *
	 * @return string
	 * @since  1.0.0
	 * @access public
	 **/
	public function get_version() {
		return $this->version;
	}

	/**
	 * Plugin URL getter.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Plugin path getter.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Plugin base path name getter.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function plugin_basename() {
		return plugin_basename( __FILE__ );
	}

	/**
	 * Initialize plugin for localization
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function localization_setup() {
		load_plugin_textdomain( 'user-listing', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Define constant if not already defined
	 *
	 * @param string      $name
	 * @param string|bool $value
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @access protected
	 * @return void
	 * @since  1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'user-listing' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @access protected
	 * @return void
	 * @since  1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'user-listing' ), '1.0.0' );
	}

	/**
	 * User_Listing constructor.
	 */
	private function __construct() {
		$this->define_constants();
		add_action( 'init', array( $this, 'init_plugin' ) );
	}

	/**
	 * Define all constants
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function define_constants() {
		$this->define( 'USER_LISTING_PLUGIN_VERSION', $this->version );
		$this->define( 'USER_LISTING_PLUGIN_FILE', __FILE__ );
		$this->define( 'USER_LISTING_PLUGIN_DIR', dirname( __FILE__ ) );
		$this->define( 'USER_LISTING_PLUGIN_INC_DIR', dirname( __FILE__ ) . '/includes' );
	}

	/**
	 * Load the plugin when plugins loaded.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function init_plugin() {
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @since 1.0.0
	 */
	public function includes() {
		require_once dirname( __FILE__ ) . '/includes/class-shortcode.php';

		do_action( 'user_listing__loaded' );
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 */
	private function init_hooks() {
		add_action( 'plugins_loaded', array( $this, 'localization_setup' ) );
	}
}

/**
 * The main function responsible for returning the one true User Listing
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * @return User_Listing
 * @since 1.0.0
 */
function user_listing() {
	return User_Listing::init();
}

//lets go.
user_listing();
