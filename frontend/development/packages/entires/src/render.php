<?php
use FrontIT\Form\Model\Feedback;

if ( ! current_user_can( 'manage_options' ) ) {
	echo '<div class="entries-list-error">' . esc_html__( 'You are not authorized to view the content of this page.', 'front-it' ) . '</div>';
} else {
	wp_localize_script(
		'front-it-entries-list-view-script',
		'frontItFormSettings',
		array(
			'nonce'   => wp_create_nonce( 'wp_rest' ),
			'restUrl' => get_rest_url( null, 'front-it/v1' ),
			'i18n'    => array(
				'errorLoadingDetails' => __( 'Error loading entry details.', 'front-it' ),
				'errorLoadingMore'    => __( 'Error loading more entries.', 'front-it' ),
				'showingEntries'      => __( 'Showing %1$d of %2$d entries', 'front-it' ),
				'loadMore'            => __( 'Load More', 'front-it' ),
				'loading'             => __( 'Loading...', 'front-it' ),
			),
		)
	);
	// Get the limit from block attributes, fallback to default if not set
	$entries_limit = isset( $attributes['entriesLimit'] ) ? absint( $attributes['entriesLimit'] ) : 10;
	
	echo '<div class="entries-list-container">';
	echo '<div class="entries-list">';
	echo '<h2>' . esc_html__( 'Entries List', 'front-it' ) . '</h2>';
	
	// Get entries using Feedback model with limit
	$feedback = new Feedback();
	$entries  = $feedback->get_all(
		array(
			'orderby' => 'created_at',
			'order'   => 'DESC',
			'limit'   => $entries_limit,
			'offset'  => 0,
		)
	);
	
	$total_entries = $feedback->get_total_count();
	
	if ( $entries ) {
		echo '<ul class="entries-items">';
		foreach ( $entries as $entry ) {
			printf(
				'<li class="entry-item" data-entry-id="%d">
                    <div class="entry-preview">
                        <span class="entry-date">%s</span>
                    </div>
                </li>',
				esc_attr( $entry->id ),
				esc_html( date( 'Y-m-d H:i', strtotime( $entry->created_at ) ) ),
			);
		}
		echo '</ul>';
		
		// Show total entries count
		echo '<div class="entries-count">' . 
			sprintf(
				esc_html__( 'Showing %1$d of %2$d entries', 'front-it' ),
				min( count( $entries ), $total_entries ),
				$total_entries
			) . 
		'</div>';

		// Add Load More button if there are more entries
		if ( $total_entries > count( $entries ) ) {
			echo '<div class="load-more-container">';
			echo '<button class="load-more-button" data-page="1" data-limit="' . esc_attr( $entries_limit ) . '">' . 
				esc_html__( 'Load More', 'front-it' ) . 
			'</button>';
			echo '</div>';
		}
	} else {
		echo '<p class="no-entries">' . esc_html__( 'No entries found.', 'front-it' ) . '</p>';
	}
	
	echo '</div>'; // Close entries-list
	
	// Container for displaying complete entry details
	echo '<div class="entry-details" style="display: none;">
        <h3>' . esc_html__( 'Entry Details', 'front-it' ) . '</h3>
        <div class="entry-content"></div>
    </div>';
	
	echo '</div>'; // Close entries-list-container
}
