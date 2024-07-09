<?php
/*
	Plugin Name: Bluefrog Addons
	Description: This is for updating your Wordpress plugin.
	Version: 1.0.0
	Author: Blue Frog Agency
	Author URI: https://bluefrogdm.com
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Bluefrog_Addons
 */
final class Bluefrog_Addons {
	/**
	 * Constructor function.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init();
	}

	/**
	 * Defines constants
	 */
	public function define_constants() {
		define( 'BLUEFROG_ADDONS_VER', '1.0.0' );
		define( 'BLUEFROG_ADDONS_DIR', plugin_dir_path( __FILE__ ) );
		define( 'BLUEFROG_ADDONS_URL', plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Load files
	 */
	public function includes() {
		include_once( BLUEFROG_ADDONS_DIR . 'includes/functions.php' );
		include_once( BLUEFROG_ADDONS_DIR . 'includes/updater.php' );
		include_once( BLUEFROG_ADDONS_DIR . 'includes/user.php' );
		include_once( BLUEFROG_ADDONS_DIR . 'includes/portfolio.php' );
		include_once( BLUEFROG_ADDONS_DIR . 'includes/class-bluefrog-vc.php' );
		include_once( BLUEFROG_ADDONS_DIR . 'includes/shortcodes/class-bluefrog-shortcodes.php' );
		include_once( BLUEFROG_ADDONS_DIR . 'includes/shortcodes/class-bluefrog-banner.php' );
		include_once( BLUEFROG_ADDONS_DIR . 'includes/shortcodes/class-bluefrog-banner-grid.php' );
	}

	/**
	 * Initialize
	 */
	public function init() {
		// add_action( 'admin_notices', array( $this, 'check_dependencies' ) );

		add_action( 'vc_before_init', 'vc_set_as_theme' );
		add_action( 'vc_after_init', array( 'Bluefrog_Addons_VC', 'init' ), 50 );
		add_action( 'init', array( 'Bluefrog_Shortcodes', 'init' ), 50 );
		// add_action( 'init', array( $this, 'update' ) );

		add_action( 'init', array( 'Bluefrog_Addons_Portfolio', 'init' ) );

		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
	}

	/**
	 * Check plugin dependencies
	 * Check if Visual Composer plugin is installed
	 */
	public function check_dependencies() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			$plugin_data = get_plugin_data( __FILE__ );

			printf(
				'<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
				sprintf(
					__( '<strong>%s</strong> requires <strong><a href="http://bit.ly/wpbakery-page-builder" target="_blank">WPBakery Page Builder</a></strong> plugin to be installed and activated on your site.' ),
					$plugin_data['Name']
				)
			);
		}
	}

	/**
	 * Register widgets
	 */
	public function widgets_init() {
		$theme = wp_get_theme();
		$template = $theme->get( 'Template' );

		if ( $template ) {
			$theme = wp_get_theme( $template );
		}
		// Load widdget files
		include_once( BLUEFROG_ADDONS_DIR . 'includes/widgets/socials.php' );
		include_once( BLUEFROG_ADDONS_DIR . 'includes/widgets/popular-posts.php' );
		include_once( BLUEFROG_ADDONS_DIR . 'includes/widgets/instagram.php' );

		register_widget( 'Bluefrog_Social_Links_Widget' );
		register_widget( 'Bluefrog_Popular_Posts_Widget' );
		register_widget( 'Bluefrog_Instagram_Widget' );
	}

	/**
	 * Check for update
	 */
	
	public function update() {
		// set auto-update params
		$updater = new Bluefrog_Updater( __FILE__ );
		$updater->set_username( 'ashleyL24' );
		$updater->set_repository( 'bluefrog-updater-addons' );
		/*
			$updater->authorize( 'abcdefghijk1234567890' ); // Your auth code goes here for private repos
		*/
		$updater->initialize();
	}
}

new Bluefrog_Addons();
