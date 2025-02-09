<?php
/**
 * Form Validator
 *
 * @package FrontIT\Form
 */

namespace FrontIT\Form\Api;

use WP_Error;

/**
 * Class Validator
 * Handles validation of form submission data
 */
class Validator {
	/**
	 * Validate name field
	 *
	 * @param string $name Name to validate.
	 * @return true|WP_Error True if valid, WP_Error otherwise.
	 */
	public static function validate_name( string $name ): true|WP_Error {
		$name = trim( $name );
		if ( empty( $name ) ) {
			return new WP_Error(
				'invalid_name',
				__( 'Name is required.', 'front-it' ),
				array( 'status' => 400 )
			);
		}

		if ( strlen( $name ) < 2 ) {
			return new WP_Error(
				'invalid_name',
				__( 'Name must be at least 2 characters long.', 'front-it' ),
				array( 'status' => 400 )
			);
		}

		if ( strlen( $name ) > 100 ) {
			return new WP_Error(
				'invalid_name',
				__( 'Name must not exceed 100 characters.', 'front-it' ),
				array( 'status' => 400 )
			);
		}

		if ( ! preg_match( '/^[\p{L}\s\'-]+$/u', $name ) ) {
			return new WP_Error(
				'invalid_name',
				__( 'Name contains invalid characters.', 'front-it' ),
				array( 'status' => 400 )
			);
		}

		return true;
	}

	/**
	 * Validate email field
	 *
	 * @param string $email Email to validate.
	 * @return true|WP_Error True if valid, WP_Error otherwise.
	 */
	public static function validate_email( string $email ): true|WP_Error {
		$email = trim( $email );
		if ( empty( $email ) ) {
			return new WP_Error(
				'invalid_email',
				__( 'Email is required.', 'front-it' ),
				array( 'status' => 400 )
			);
		}

		if ( ! is_email( $email ) ) {
			return new WP_Error(
				'invalid_email',
				__( 'Please enter a valid email address.', 'front-it' ),
				array( 'status' => 400 )
			);
		}

		return true;
	}

	/**
	 * Validate phone field
	 *
	 * @param ?string $phone Phone to validate.
	 * @return true|WP_Error True if valid, WP_Error otherwise.
	 */
	public static function validate_phone( ?string $phone ): true|WP_Error {
		if ( empty( $phone ) ) {
			return true; // Phone is optional
		}

		$phone = trim( $phone );
		if ( ! preg_match( '/^[0-9\+\-\(\)\s]{6,20}$/', $phone ) ) {
			return new WP_Error(
				'invalid_phone',
				__( 'Please enter a valid phone number.', 'front-it' ),
				array( 'status' => 400 )
			);
		}

		return true;
	}

	/**
	 * Validate message field
	 *
	 * @param string $message Message to validate.
	 * @return true|WP_Error True if valid, WP_Error otherwise.
	 */
	public static function validate_message( string $message ): true|WP_Error {
		$message = trim( $message );
		if ( empty( $message ) ) {
			return new WP_Error(
				'invalid_message',
				__( 'Message is required.', 'front-it' ),
				array( 'status' => 400 )
			);
		}

		if ( strlen( $message ) < 10 ) {
			return new WP_Error(
				'invalid_message',
				__( 'Message must be at least 10 characters long.', 'front-it' ),
				array( 'status' => 400 )
			);
		}

		if ( strlen( $message ) > 1000 ) {
			return new WP_Error(
				'invalid_message',
				__( 'Message must not exceed 1000 characters.', 'front-it' ),
				array( 'status' => 400 )
			);
		}

		return true;
	}

	/**
	 * Validate all form fields
	 *
	 * @param array $data Form data to validate.
	 * @return true|WP_Error True if valid, WP_Error otherwise.
	 */
	public static function validate_all( array $data ): true|WP_Error {
		$name_result = self::validate_name( $data['name'] );
		if ( is_wp_error( $name_result ) ) {
			return $name_result;
		}

		$email_result = self::validate_email( $data['email'] );
		if ( is_wp_error( $email_result ) ) {
			return $email_result;
		}

		if ( isset( $data['phone'] ) ) {
			$phone_result = self::validate_phone( $data['phone'] );
			if ( is_wp_error( $phone_result ) ) {
				return $phone_result;
			}
		}

		$message_result = self::validate_message( $data['message'] );
		if ( is_wp_error( $message_result ) ) {
			return $message_result;
		}

		return true;
	}
} 
