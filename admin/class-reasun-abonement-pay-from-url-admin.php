<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Reasun_Abonement_Pay_From_URL
 * @subpackage Reasun_Abonement_Pay_From_URL/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Reasun_Abonement_Pay_From_URL
 * @subpackage Reasun_Abonement_Pay_From_URL/admin
 * @author     Агеенко Петр
 */
class Reasun_Abonement_Pay_From_URL_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $reasun_abonement_pay_from_url    The ID of this plugin.
	 */
	private $reasun_abonement_pay_from_url;

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
	 * @param      string    $reasun_abonement_pay_from_url       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $reasun_abonement_pay_from_url, $version ) {

		$this->reasun_abonement_pay_from_url = $reasun_abonement_pay_from_url;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Reasun_Abonement_Pay_From_URL_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Reasun_Abonement_Pay_From_URL_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->reasun_abonement_pay_from_url, plugin_dir_url( __FILE__ ) . 'css/reasun-abonement-pay-from-url-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Reasun_Abonement_Pay_From_URL_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Reasun_Abonement_Pay_From_URL_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->reasun_abonement_pay_from_url, plugin_dir_url( __FILE__ ) . 'js/reasun-abonement-pay-from-url-admin.js', array( 'jquery' ), $this->version, false );

	}
 /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     */
	 /*
      * Add a settings page for this plugin to the Settings menu.
     */
/* 
    public function add_plugin_admin_menu() {

     
        add_options_page( 'My plugin and Base Options Functions Setup', 'Настройка страницы оплаты по ссылке', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
        );
    }
 */
     /**
     * Add settings action link to the plugins page.
     */

/*     public function add_action_links( $links ) {
        
       $settings_link = array(
        '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
       );
       return array_merge(  $settings_link, $links );

    } */

    /**
     * Render the settings page for this plugin.
     */
/* 
    public function display_plugin_setup_page() {
        
        include_once( 'partials/reasun-abonement-pay-from-url-admin-display.php' );
        
    }
	 */
	/**
   * Validate options
   *//* 
   public function validate($input) {
     $valid = array();
     $valid['host_1c'] = (isset($input['host_1c']) && !empty($input['host_1c'])) ? $input['host_1c'] : '';
     $valid['login_1c'] = (isset($input['login_1c']) && !empty($input['login_1c'])) ? $input['login_1c'] : '';
     $valid['pwd_1c'] = (isset($input['pwd_1c']) && !empty($input['pwd_1c'])) ? $input['pwd_1c'] : '';
     return $valid;
   } */
     /**
     * Update all options
     *//* 
    public function options_update() {
        register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
    } */
	
	
	public function reasun_abonement_pay_from_url_change_templates_list($page_templates, $theme, $post){
		
		$page_templates[Reasun_Abonement_Pay_From_URL::TEMPLATE_SLUG] = Reasun_Abonement_Pay_From_URL::TEMPLATE_NAME;
		
		return $page_templates;
		
	}
}
