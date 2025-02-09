<?php
/**
 * Blocks Manager class
 *
 * @package FrontIT\Form\Blocks
 */

namespace FrontIT\Form\Blocks;

use FrontIT\Form\Constants;

/**
 * Class BlocksManager
 *
 * Handles block registration and assets
 */
class BlocksManager {

	/**
	 * Build directory path
	 *
	 * @var string
	 */
	private $build_dir;

	/**
	 * Ignored blocks
	 *
	 * @var array
	 */
	private $ignored_blocks;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->build_dir = plugin_dir_path( dirname( __DIR__ ) ) . 'frontend/build';

		add_action( 'init', array( $this, 'register_blocks' ) );
	}

	/**
	 * Register blocks
	 *
	 * @return void
	 */
	public function register_blocks() {
		if ( ! file_exists( $this->build_dir ) ) {
			add_action( 'admin_notices', array( $this, 'display_dev_notice' ) );
			return;
		}

		$block_json_files = glob( $this->build_dir . '/*/block.json' );

		foreach ( $block_json_files as $filename ) {
			$block_folder = dirname( $filename );

			register_block_type( $block_folder );
		}
	}

	/**
	 * Display development notice
	 *
	 * @return void
	 */
	public function display_dev_notice() {
		$message = sprintf(
			/* translators: %s: Build directory path */
			__( 'Front IT Form: Block editor assets are not built. Please run `yarn build` in the frontend/development directory. Build directory: %s', Constants::TEXT_DOMAIN ),
			esc_html( $this->build_dir )
		);

		printf(
			'<div class="notice notice-warning"><p>%s</p></div>',
			wp_kses_post( $message )
		);
	}
} 
