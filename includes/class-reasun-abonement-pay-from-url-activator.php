<?php

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    Reasun_Abonement_Pay_From_URL
 * @subpackage Reasun_Abonement_Pay_From_URL/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Reasun_Abonement_Pay_From_URL
 * @subpackage Reasun_Abonement_Pay_From_URL/includes
 * @author     Агеенко Петр
 */
class Reasun_Abonement_Pay_From_URL_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$table = $wpdb->get_blog_prefix() . 'rapfu_orders';
		$charset = "DEFAULT CHARACTER SET " . $wpdb->charset . " COLLATE " . $wpdb->collate;
		
		$sql = "CREATE TABLE " . $table . " (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		offer_id_from_one_c varchar(255) NOT NULL default '',
		order_id_site varchar(255) NOT NULL default '',
		order_id_pay varchar(255) NOT NULL default '',
		order_pay_status tinyint(1) NOT NULL default '0',
		cart_item_name varchar(255) NOT NULL default '',
		cart_item_main_offer_flag tinyint(1) NOT NULL default '0',
		cart_item_uid varchar(255) NOT NULL default '',
		cart_item_price bigint(20),
		cart_item_quantity smallint(5),
		operation varchar(255) default '',
		checksum varchar(255) default '',
		md_order varchar(255) default '',
		PRIMARY KEY  (id)
		)
		" . $charset . ";";
		file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", $today . "___" ."\nSQL activation plugin:\n" . $sql, FILE_APPEND);
				dbDelta($sql);
		
			}

	}
