<?php
/**
 * REST API Handler for Front IT Form
 *
 * @package FrontIT\Form
 */

namespace FrontIT\Form\Api;

use FrontIT\Form\Model\Feedback;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use FrontIT\Form\Api\Validator;

/**
 * Class Api
 * Handles REST API endpoints for form submissions
 */
class Api {
	/**
	 * The namespace for the REST API
	 *
	 * @var string
	 */
	private const API_NAMESPACE = 'front-it/v1';

	/**
	 * Initialize the class and register REST routes
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register REST API routes
	 *
	 * @return void
	 */
	public function register_routes(): void {
		register_rest_route(
			self::API_NAMESPACE,
			'/submit',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'handle_submission' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'name'    => array(
						'required' => true,
						'type'     => 'string',
					),
					'email'   => array(
						'required' => true,
						'type'     => 'string',
					),
					'phone'   => array(
						'type'     => 'string',
						'required' => false,
					),
					'message' => array(
						'required' => true,
						'type'     => 'string',
					),
				),
			)
		);
	}

	/**
	 * Handle form submission
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return WP_REST_Response|WP_Error Response object or error.
	 */
	public function handle_submission( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		$params = $request->get_params();

		// Validate all input data using the Validator class
		$validation_result = Validator::validate_all( $params );
		if ( is_wp_error( $validation_result ) ) {
			return $validation_result;
		}

		$name_parts = explode( ' ', trim( $params['name'] ), 2 );

		$feedback = new Feedback();
		$id       = $feedback->create(
			array(
				'first_name' => $name_parts[0],
				'last_name'  => isset( $name_parts[1] ) ? $name_parts[1] : '',
				'email'      => $params['email'],
				'subject'    => isset( $params['phone'] ) ? $params['phone'] : '',
				'message'    => $params['message'],
			)
		);

		if ( $id === false ) {
			return new WP_Error(
				'db_error',
				__( 'Failed to save form submission.', 'front-it' ),
				array( 'status' => 500 )
			);
		}

		return new WP_REST_Response(
			array(
				'success' => true,
				'message' => __( 'Thank you for sending us your feedback.', 'front-it' ),
				'id'      => $id,
			),
			200
		);
	}
} 
