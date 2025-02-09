<?php
/**
 * Main plugin class
 *
 * @package FrontIT\Form
 */

namespace FrontIT\Form;

use FrontIT\Form\Blocks\BlocksManager;

/**
 * Class Plugin
 *
 * Main plugin class
 */
class Plugin {

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Database version
	 *
	 * @var string
	 */
	private $db_version;

	/**
	 * Blocks manager
	 *
	 * @var BlocksManager
	 */
	private $blocks_manager;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->version        = Constants::VERSION;
		$this->db_version     = Constants::DB_VERSION;
		$this->blocks_manager = new BlocksManager();
	}

	/**
	 * Initialize plugin
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'init', array( $this, 'plugin_init' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'plugins_loaded', array( $this, 'check_update' ) );
	}

	/**
	 * Plugin initialization
	 *
	 * @return void
	 */
	public function plugin_init() {
		// Initialize plugin functionality.
	}

	/**
	 * Add admin menu
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		add_menu_page(
			__( 'Front IT Form', Constants::TEXT_DOMAIN ),
			__( 'Front IT Form', Constants::TEXT_DOMAIN ),
			'manage_options',
			'front-it-form',
			array( $this, 'render_admin_page' ),
			'dashicons-database',
			30
		);
	}

	/**
	 * Render admin page
	 *
	 * @return void
	 */
	public function render_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<p><?php esc_html_e( 'Welcome to Front IT Form plugin!', Constants::TEXT_DOMAIN ); ?></p>
			<p>
			<?php
				printf(
					/* translators: %s: Database version */
					esc_html__( 'Database Version: %s', Constants::TEXT_DOMAIN ),
					esc_html( get_option( Constants::OPTION_DB_VERSION, 'Not installed' ) )
				);
			?>
			</p>
		</div>
		<?php
	}

	/**
	 * Check for updates
	 *
	 * @return void
	 */
	public function check_update() {
		if ( get_option( Constants::OPTION_VERSION ) !== $this->version ) {
			$installer = new Database\Installer();
			$installer->install();
			update_option( Constants::OPTION_VERSION, $this->version );
		}
	}
} 