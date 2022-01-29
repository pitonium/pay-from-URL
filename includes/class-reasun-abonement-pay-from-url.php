<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    Reasun_Abonement_Pay_From_URL
 * @subpackage Reasun_Abonement_Pay_From_URL/includes
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
 * @since      1.0.0
 * @package    Reasun_Abonement_Pay_From_URL
 * @subpackage Reasun_Abonement_Pay_From_URL/includes
 * @author     Агеенко Петр
 */
class Reasun_Abonement_Pay_From_URL {

	const TEMPLATE_SLUG       = 'page-reasun-abonement-pay-from-url-plugin.php';
	const TEMPLATE_NAME       = 'Payment From URL';
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Reasun_Abonement_Pay_From_URL_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $reasun_abonement_pay_from_url    The string used to uniquely identify this plugin.
	 */
	protected $reasun_abonement_pay_from_url;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    20180530
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'REASUN_ABONEMENT_PAY_FROM_URL_VERSION' ) ) {
			$this->version = REASUN_ABONEMENT_PAY_FROM_URL_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->reasun_abonement_pay_from_url = 'reasun-abonement-pay-from-url';
		$this->plugin_name ='reasun-abonement-pay-from-url';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Reasun_Abonement_Pay_From_URL_Loader. Orchestrates the hooks of the plugin.
	 * - Reasun_Abonement_Pay_From_URL_i18n. Defines internationalization functionality.
	 * - Reasun_Abonement_Pay_From_URL_Admin. Defines all hooks for the admin area.
	 * - Reasun_Abonement_Pay_From_URL_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-reasun-abonement-pay-from-url-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-reasun-abonement-pay-from-url-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-reasun-abonement-pay-from-url-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-reasun-abonement-pay-from-url-public.php';

		$this->loader = new Reasun_Abonement_Pay_From_URL_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Reasun_Abonement_Pay_From_URL_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Reasun_Abonement_Pay_From_URL_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Reasun_Abonement_Pay_From_URL_Admin( $this->get_reasun_abonement_pay_from_url(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		// Save/Update our plugin options
		$this->loader->add_action('admin_init', $plugin_admin, 'options_update');
		// Add menu item
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		// Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );
				
		$this->loader->add_filter( 'theme_page_templates', $plugin_admin, 'reasun_abonement_pay_from_url_change_templates_list', 10, 3 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Reasun_Abonement_Pay_From_URL_Public( $this->get_reasun_abonement_pay_from_url(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_content', $plugin_public, 'add_host' );
				
		$this->loader->add_filter( 'template_include', $plugin_public, 'reasun_abonement_pay_from_url_load_template' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_reasun_abonement_pay_from_url() {
		return $this->reasun_abonement_pay_from_url;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Reasun_Abonement_Pay_From_URL_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
