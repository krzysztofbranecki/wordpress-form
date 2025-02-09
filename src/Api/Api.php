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

		// Register endpoint for fetching entry details
		register_rest_route(
			self::API_NAMESPACE,
			'/entries/(?P<id>\d+)',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_entry' ),
				'permission_callback' => array( $this, 'check_admin_permissions' ),
				'args'                => array(
					'id' => array(
						'required'          => true,
						'type'             => 'integer',
						'validate_callback' => function( $param ) {
							return is_numeric( $param ) && $param > 0;
						},
					),
				),
			)
		);

		// Register endpoint for fetching entries list with pagination
		register_rest_route(
			self::API_NAMESPACE,
			'/entries',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_entries' ),
				'permission_callback' => array( $this, 'check_admin_permissions' ),
				'args'                => array(
					'limit'  => array(
						'required'          => false,
						'type'             => 'integer',
						'default'          => 10,
						'validate_callback' => function( $param ) {
							return is_numeric( $param ) && $param > 0 && $param <= 100;
						},
					),
					'offset' => array(
						'required'          => false,
						'type'             => 'integer',
						'default'          => 0,
						'validate_callback' => function( $param ) {
							return is_numeric( $param ) && $param >= 0;
						},
					),
				),
			)
		);
	}

	/**
	 * Check if the current user has admin permissions
	 *
	 * @return bool Whether the user has permission
	 */
	public function check_admin_permissions(): bool {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Get entries list with pagination
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return WP_REST_Response|WP_Error Response object or error.
	 */
	public function get_entries( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		$limit = $request->get_param( 'limit' );
		$offset = $request->get_param( 'offset' );

		$feedback = new Feedback();
		$entries = $feedback->get_all( array(
			'orderby' => 'created_at',
			'order'   => 'DESC',
			'limit'   => $limit,
			'offset'  => $offset,
		) );

		if ( empty( $entries ) ) {
			return new WP_REST_Response(
				array(
					'entries' => array(),
					'total'   => $feedback->get_total_count(),
				),
				200
			);
		}

		// Format entries for response
		$formatted_entries = array_map( function( $entry ) {
			return array(
				'id'         => (int) $entry->id,
				'created_at' => $entry->created_at,
				'first_name' => $entry->first_name,
				'last_name'  => $entry->last_name,
				'email'      => $entry->email,
			);
		}, $entries );

		return new WP_REST_Response(
			array(
				'entries' => $formatted_entries,
				'total'   => $feedback->get_total_count(),
			),
			200
		);
	}

	/**
	 * Get entry details by ID
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return WP_REST_Response|WP_Error Response object or error.
	 */
	public function get_entry( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		$entry_id = $request->get_param( 'id' );
		
		$feedback = new Feedback();
		$entry = $feedback->get($entry_id);

		if ( empty($entry) ) {
			return new WP_Error(
				'not_found',
				__( 'Entry not found.', 'front-it' ),
				array( 'status' => 404 )
			);
		}

		// Format the entry data
		$entry_data = array(
			'id'         => (int) $entry->id,
			'created_at' => $entry->created_at,
			'form_name'  => $entry->form_name ?? __('Contact Form', 'front-it'),
			'fields'     => array(
				'First Name' => $entry->first_name,
				'Last Name'  => $entry->last_name,
				'Email'      => $entry->email,
				'Phone'      => $entry->subject, // Phone is stored in subject field
				'Message'    => $entry->message,
			),
		);

		return new WP_REST_Response( $entry_data, 200 );
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
