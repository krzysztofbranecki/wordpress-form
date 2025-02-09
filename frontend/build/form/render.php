<?php

$current_user = wp_get_current_user();
$is_logged_in = is_user_logged_in();

	// Get user data if logged in
	$name  = '';
	$email = '';
if ( $is_logged_in ) {
	$name  = trim( $current_user->first_name . ' ' . $current_user->last_name );
	$email = $current_user->user_email;
}
	

?>
	<div class="front-it-form">
		<?php if ( ! empty( $attributes['formTitle'] ) ) : ?>
			<div class="form-title">
				<h2><?php echo esc_html( $attributes['formTitle'] ); ?></h2>
			</div>
		<?php endif; ?>
		
		<form class="contact-form" data-form-id="front-it-contact">
			<div class="form-group">
				<label for="name">Name *</label>
				<input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" required <?php echo isset( $name ) ? 'disabled value="' . esc_attr( $name ) . '"' : ''; ?>>
			</div>
			
			<div class="form-group">
				<label for="email">Email *</label>
				<input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required <?php echo isset( $email ) ? 'disabled value="' . esc_attr( $email ) . '"' : ''; ?>">
			</div>
			
			<div class="form-group">
				<label for="phone">Phone</label>
				<input type="tel" id="phone" name="phone" class="form-control" placeholder="Enter your phone number">
			</div>
			
			<div class="form-group">
				<label for="message">Message *</label>
				<textarea id="message" name="message" class="form-control" rows="4" placeholder="Enter your message" required></textarea>
			</div>
			
			<button type="submit" class="submit-button">Submit</button>
			<div class="response-message"></div>
		</form>
	</div>