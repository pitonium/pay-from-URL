<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Reasun_Abonement_Pay_From_URL
 * @subpackage Reasun_Abonement_Pay_From_URL/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Reasun_Abonement_Pay_From_URL
 * @subpackage Reasun_Abonement_Pay_From_URL/public
 * @author     Агеенко Петр
 */
class Reasun_Abonement_Pay_From_URL_Public {

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
	 * @param      string    $reasun_abonement_pay_from_url       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $reasun_abonement_pay_from_url, $version ) {

		$this->reasun_abonement_pay_from_url = $reasun_abonement_pay_from_url;
		$this->version = $version;
	//	$this->reasun_abonement_pay_from_url_options = get_option($this->plugin_name);

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->reasun_abonement_pay_from_url, plugin_dir_url( __FILE__ ) . 'css/reasun-abonement-pay-from-url-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->reasun_abonement_pay_from_url, plugin_dir_url( __FILE__ ) . 'js/reasun-abonement-pay-from-url-public.js', array( 'jquery' ), $this->version, false );

	}
	
		public function reasun_abonement_pay_from_url_load_template( $template ){
		
		$custom_template_slug   = Reasun_Abonement_Pay_From_URL::TEMPLATE_SLUG;

		if( is_page_template( $custom_template_slug ) ){
			$template = plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/' . $custom_template_slug;
		}
	
		return $template;
		
	}
/**
  * The function of adding text to the footer
  */
/* 
  public function add_host(){

   if( !empty($this->reasun_abonement_pay_from_url_options['host_1c']) )
     {
        echo '<h3 class="center">'.$this->reasun_abonement_pay_from_url_options['host_1c'].'</h3>';
     }
  } */
}
