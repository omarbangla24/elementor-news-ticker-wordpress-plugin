<?php
/**
 * Plugin Name:       Elementor News Ticker Widget
 * Description:       A responsive news scroller and slider widget for Elementor.
 * Plugin URI:        https://logicean.com/
 * Version:           1.0.0
 * Author:            Omar Faruk
 * Author URI:        https://logicean.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       elementor-news-ticker
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Elementor News Ticker Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Elementor_News_Ticker_Extension {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Elementor_News_Ticker_Extension The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Elementor_News_Ticker_Extension An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ] );
	}

	/**
	 * On Plugins Loaded
	 *
	 * Checks if Elementor is installed and loaded.
	 *
	 * @since 1.0.0
	 */
	public function on_plugins_loaded() {
		// Check if Elementor is active
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Register widget scripts
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_widget_scripts' ] );
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'register_widget_styles' ] );


		// Add Plugin actions
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
	}
	
	/**
     * Register Widget Scripts
     *
     * @since 1.0.0
     */
    public function register_widget_scripts() {
        wp_register_script( 'news-ticker-frontend', plugins_url( 'assets/js/frontend.js', __FILE__ ), [ 'jquery' ], self::VERSION, true );
    }

    /**
     * Register Widget Styles
     *
     * @since 1.0.0
     */
    public function register_widget_styles() {
        wp_register_style( 'news-ticker-style', plugins_url( 'assets/css/style.css', __FILE__ ), [], self::VERSION );
    }


	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 */
	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-news-ticker' ),
			'<strong>' . esc_html__( 'Elementor News Ticker', 'elementor-news-ticker' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-news-ticker' ) . '</strong>'
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-news-ticker' ),
			'<strong>' . esc_html__( 'Elementor News Ticker', 'elementor-news-ticker' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-news-ticker' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.0.0
	 */
	public function register_widgets($widgets_manager) {
		// Include Widget files
		require_once( __DIR__ . '/widget.php' );

		// Register widget
		$widgets_manager->register( new \Elementor_News_Ticker_Widget() );
	}

}

Elementor_News_Ticker_Extension::instance();
