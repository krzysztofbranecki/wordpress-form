<?php

namespace FrontIT\Form;

class Plugin {
	/**
	 * @var string
	 */
	private $version;

	/**
	 * @var string
	 */
	private $db_version;

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		$this->version    = FRONT_IT_FORM_VERSION;
		$this->db_version = FRONT_IT_FORM_DB_VERSION;
	}

	/**
	 * Initialize the plugin
	 */
	public function init(): void {
		add_action( 'init', array( $this, 'pluginInit' ) );
		add_action( 'admin_menu', array( $this, 'addAdminMenu' ) );
		add_action( 'plugins_loaded', array( $this, 'checkUpdate' ) );
	}

	/**
	 * Plugin initialization
	 */
	public function pluginInit(): void {
		// Initialize plugin functionality
	}

	/**
	 * Add admin menu
	 */
	public function addAdminMenu(): void {
		add_menu_page(
			__( 'Front IT Form', 'front-it-form' ),
			__( 'Front IT Form', 'front-it-form' ),
			'manage_options',
			'front-it-form',
			array( $this, 'renderAdminPage' ),
			'dashicons-database',
			30
		);
	}

	/**
	 * Render admin page
	 */
	public function renderAdminPage(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<p><?php _e( 'Welcome to Front IT Form plugin!', 'front-it-form' ); ?></p>
			<p>
			<?php 
				printf(
					__( 'Database Version: %s', 'front-it-form' ),
					esc_html( get_option( 'front_it_form_db_version', 'Not installed' ) )
				); 
			?>
			</p>
		</div>
		<?php
	}

	/**
	 * Check for updates
	 */
	public function checkUpdate(): void {
		if ( get_option( 'front_it_form_version' ) !== $this->version ) {
			$installer = new Database\Installer();
			$installer->install();
			update_option( 'front_it_form_version', $this->version );
		}
	}
} 