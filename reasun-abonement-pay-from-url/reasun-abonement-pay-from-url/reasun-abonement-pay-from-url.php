<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           Reasun_Abonement_Pay_From_URL
 *
 * @wordpress-plugin
 * Plugin Name:       Reasun Abonement Pay from URL
 * Plugin URI:        pitonoff.ru
 * Description:       Плагин оплаты предложений по ссылке.
 * Version:           1.0.0
 * Author:            Агеенко Петр
 * Author URI:        pitonoff.ru
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       reasun-abonement-pay-from-url
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Update it as you release new versions.
 */
define( 'REASUN_ABONEMENT_PAY_FROM_URL_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-reasun-abonement-pay-from-url-activator.php
 */
function activate_reasun_abonement_pay_from_url() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-reasun-abonement-pay-from-url-activator.php';
	Reasun_Abonement_Pay_From_URL_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-reasun-abonement-pay-from-url-deactivator.php
 */
function deactivate_reasun_abonement_pay_from_url() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-reasun-abonement-pay-from-url-deactivator.php';
	Reasun_Abonement_Pay_From_URL_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_reasun_abonement_pay_from_url' );
register_deactivation_hook( __FILE__, 'deactivate_reasun_abonement_pay_from_url' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-reasun-abonement-pay-from-url.php';
?>
<?php
/*
 * Plugin name: Primer
 * Description: Демонстрация создания страницы настроек для плагина
*/

/**
 * Создаем страницу настроек плагина
 */
add_action('admin_menu', 'add_plugin_page');
function add_plugin_page(){
	add_options_page( 'Настройки страницы оплаты по ссылке', 'Настройки страницы оплаты по ссылке', 'manage_options', 'primer_slug', 'primer_options_page_output' );
}

function primer_options_page_output(){
	?>
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>

		<form action="options.php" method="POST">
			<?php
				settings_fields( 'option_group' );     // скрытые защитные поля
				do_settings_sections( 'primer_page' ); // секции с настройками (опциями). У нас она всего одна 'section_id'
				submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Регистрируем настройки.
 * Настройки будут храниться в массиве, а не одна настройка = одна опция.
 */
add_action('admin_init', 'plugin_settings');
function plugin_settings(){
	// параметры: $option_group, $option_name, $sanitize_callback
	register_setting( 'option_group', 'option_name', 'sanitize_callback' );

	// параметры: $id, $title, $callback, $page
	add_settings_section( 'section_id', 'Настройки для веб-сервиса SOAP 1C', '', 'primer_page' );

	// параметры: $id, $title, $callback, $page, $section, $args
	add_settings_field('primer_field1', 'Хост SOAP 1с', 'fill_primer_field1', 'primer_page', 'section_id' );
	add_settings_field('primer_field2', 'Логин для веб-сервиса 1С', 'fill_primer_field2', 'primer_page', 'section_id' );
	add_settings_field('primer_field3', 'Пароль для веб-сервиса 1С', 'fill_primer_field3', 'primer_page', 'section_id' );
	// параметры: $id, $title, $callback, $page
	add_settings_section( 'section_id2', 'Настройки для платежной системы', '', 'primer_page' );

	// параметры: $id, $title, $callback, $page, $section, $args
	add_settings_field('primer_field4', 'Логин шлюза банка ', 'fill_primer_field4', 'primer_page', 'section_id2' );
	add_settings_field('primer_field5', 'Пароль шлюза банка', 'fill_primer_field5', 'primer_page', 'section_id2' );
	add_settings_field('primer_field6', 'URL шлюза банка', 'fill_primer_field6', 'primer_page', 'section_id2' );
	add_settings_field('primer_field7', 'URL тестового шлюза банка', 'fill_primer_field7', 'primer_page', 'section_id2' );
	add_settings_field('primer_field8', 'Включить тестовый режим (Y|N)', 'fill_primer_field8', 'primer_page', 'section_id2' );
	add_settings_field('primer_field9', 'Суфикс номера заказа', 'fill_primer_field9', 'primer_page', 'section_id2' );
	//add_settings_field('primer_field10', '', 'fill_primer_field10', 'primer_page', 'section_id2' );
	//add_settings_field('primer_field11', '', 'fill_primer_field11', 'primer_page', 'section_id2' );
}

## Заполняем опцию 1
function fill_primer_field1(){
	$val = get_option('option_name');
	$val = $val ? $val['rapfu_hst'] : null;
	?>
	<input type="text" name="option_name[rapfu_hst]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}
function fill_primer_field2(){
	$val = get_option('option_name');
	$val = $val ? $val['rapfu_lgn'] : null;
	?>
	<input type="text" name="option_name[rapfu_lgn]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}
function fill_primer_field3(){
	$val = get_option('option_name');
	$val = $val ? $val['rapfu_psw'] : null;
	?>
	<input type="text" name="option_name[rapfu_psw]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}
## Заполняем опцию 1
function fill_primer_field4(){
	$val = get_option('option_name');
	$val = $val ? $val['rapfu_payment_lgn'] : null;
	?>
	<input type="text" name="option_name[rapfu_payment_lgn]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}
## Заполняем опцию 1
function fill_primer_field5(){
	$val = get_option('option_name');
	$val = $val ? $val['rapfu_payment_psw'] : null;
	?>
	<input type="text" name="option_name[rapfu_payment_psw]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}

function fill_primer_field6(){
	$val = get_option('option_name');
	$val = $val ? $val['rapfu_payment_hst'] : null;
	?>
	<input type="text" name="option_name[rapfu_payment_hst]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}



function fill_primer_field7(){
	$val = get_option('option_name');
	$val = $val ? $val['rapfu_payment_test_hst'] : null;
	?>
	<input type="text" name="option_name[rapfu_payment_test_hst]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}


function fill_primer_field8(){
	$val = get_option('option_name');
	$val = $val ? $val['rapfu_payment_test_mode'] : false;
	?>
	<input type="text" name="option_name[rapfu_payment_test_mode]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}


function fill_primer_field9(){
	$val = get_option('option_name');
	$val = $val ? $val['rapfu_payment_order_prefix'] : null;
	?>
	<input type="text" name="option_name[rapfu_payment_order_prefix]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}


## Очистка данных
function sanitize_callback( $options ){
	// очищаем
	foreach( $options as $name => &$val ){
//	if( in_array($name, ['rapfu_hst' ,'rapfu_lgn','rapfu_psw' ])){
//			$val = trim( $val );
//}
		/* if( $name == 'checkbox' )
		($val==1)?$val=1:$val=0; */
	//		$val = intval( $val );
	}

	//die(print_r( $options )); // Array ( [input] => aaaa [checkbox] => 1 )

	return $options;
}
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_reasun_abonement_pay_from_url() {

	$plugin = new Reasun_Abonement_Pay_From_URL();
	$plugin->run();

}
run_reasun_abonement_pay_from_url();
