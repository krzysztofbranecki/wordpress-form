<?php
/**
 * Database installer class
 *
 * @package FrontIT\Form
 */

namespace FrontIT\Form\Database;

use FrontIT\Form\Constants;

/**
 * Class Installer
 *
 * Handles database table creation and updates
 */
class Installer {

	/**
	 * Table name
	 *
	 * @var string
	 */
	private $table_name;

	/**
	 * Constructor
	 */
	public function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . Constants::TABLE_NAME;
	}

	/**
	 * Install or update database
	 *
	 * @return void
	 */
	public function install() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$installed_ver   = get_option( Constants::OPTION_DB_VERSION );

		// Only run installation if we have a new version or no version installed.
		if ( $installed_ver !== Constants::DB_VERSION ) {
			$sql = "CREATE TABLE {$this->table_name} (
				id bigint(20) NOT NULL AUTO_INCREMENT,
				created_at datetime DEFAULT CURRENT_TIMESTAMP,
				first_name varchar(100) NOT NULL,
				last_name varchar(100) NOT NULL,
				email varchar(255) NOT NULL,
				subject varchar(255) NOT NULL,
				message text NOT NULL,
				updated_at datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY  (id),
				KEY email (email),
				KEY created_at (created_at)
			) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );

			// Update or add the database version option.
			if ( $installed_ver ) {
				update_option( Constants::OPTION_DB_VERSION, Constants::DB_VERSION );
			} else {
				add_option( Constants::OPTION_DB_VERSION, Constants::DB_VERSION );
			}
		}
	}

	/**
	 * Uninstall database
	 *
	 * @return void
	 */
	public static function uninstall() {
		global $wpdb;
		$table_name = $wpdb->prefix . Constants::TABLE_NAME;
		$wpdb->query( "DROP TABLE IF EXISTS $table_name" );

		delete_option( Constants::OPTION_VERSION );
		delete_option( Constants::OPTION_DB_VERSION );
	}
} 
