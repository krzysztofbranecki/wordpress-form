<?php
/**
 * Feedback model class
 *
 * @package FrontIT\Form
 */

namespace FrontIT\Form\Model;

use FrontIT\Form\Constants;

/**
 * Class Feedback
 *
 * Handles feedback data operations
 */
class Feedback {

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
	 * Create new feedback entry
	 *
	 * @param array $data Form data.
	 * @return int|false
	 */
	public function create( $data ) {
		global $wpdb;

		$defaults = array(
			'created_at' => current_time( 'mysql' ),
		);

		$data = wp_parse_args( $data, $defaults );

		$inserted = $wpdb->insert(
			$this->table_name,
			$this->sanitize_data( $data ),
			$this->get_field_formats()
		);

		if ( false === $inserted ) {
			return false;
		}

		return $wpdb->insert_id;
	}

	/**
	 * Get feedback by ID
	 *
	 * @param int $id Feedback ID.
	 * @return object|null
	 */
	public function get( $id ) {
		global $wpdb;

		return $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$this->table_name} WHERE id = %d", $id )
		);
	}

	/**
	 * Get all feedback entries
	 *
	 * @param array $args Query arguments.
	 * @return array
	 */
	public function get_all( $args = array() ) {
		global $wpdb;

		$defaults = array(
			'orderby' => 'created_at',
			'order'   => 'DESC',
			'limit'   => null,
			'offset'  => 0,
		);

		$args = wp_parse_args( $args, $defaults );

		$sql     = "SELECT * FROM {$this->table_name}";
		$prepare = array();

		$sql .= " ORDER BY {$args['orderby']} {$args['order']}";

		if ( ! empty( $args['limit'] ) ) {
			$sql      .= ' LIMIT %d';
			$prepare[] = $args['limit'];

			if ( ! empty( $args['offset'] ) ) {
				$sql      .= ' OFFSET %d';
				$prepare[] = $args['offset'];
			}
		}

		return empty( $prepare )
			? $wpdb->get_results( $sql )
			: $wpdb->get_results( $wpdb->prepare( $sql, $prepare ) );
	}

	/**
	 * Get total count of feedback entries
	 *
	 * @return int
	 */
	public function get_total_count() {
		global $wpdb;
		return (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$this->table_name}" );
	}

	/**
	 * Get field formats for wpdb
	 *
	 * @return array
	 */
	private function get_field_formats() {
		return array(
			'first_name' => '%s',
			'last_name'  => '%s',
			'email'      => '%s',
			'subject'    => '%s',
			'message'    => '%s',
			'created_at' => '%s',
		);
	}

	/**
	 * Sanitize input data
	 *
	 * @param array $data Raw input data.
	 * @return array
	 */
	private function sanitize_data( $data ) {
		$sanitized = array();

		if ( ! empty( $data['first_name'] ) ) {
			$sanitized['first_name'] = sanitize_text_field( $data['first_name'] );
		}
		if ( ! empty( $data['last_name'] ) ) {
			$sanitized['last_name'] = sanitize_text_field( $data['last_name'] );
		}
		if ( ! empty( $data['email'] ) ) {
			$sanitized['email'] = sanitize_email( $data['email'] );
		}
		if ( ! empty( $data['subject'] ) ) {
			$sanitized['subject'] = sanitize_text_field( $data['subject'] );
		}
		if ( ! empty( $data['message'] ) ) {
			$sanitized['message'] = sanitize_textarea_field( $data['message'] );
		}
		if ( ! empty( $data['created_at'] ) ) {
			$sanitized['created_at'] = sanitize_text_field( $data['created_at'] );
		}

		return $sanitized;
	}
} 
