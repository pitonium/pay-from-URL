<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 *
 * @package    Reasun_Abonement_Pay_From_URL
 * @subpackage Reasun_Abonement_Pay_From_URL/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Reasun_Abonement_Pay_From_URL
 * @subpackage Reasun_Abonement_Pay_From_URL/includes
 * @author     Агеенко Петр
 */
class Reasun_Abonement_Pay_From_URL_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'reasun-abonement-pay-from-url',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
