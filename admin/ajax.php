<?php

add_action( 'wp_ajax_srea_save_settings', 'srea_save_settings' );

function srea_save_settings() {

	check_ajax_referer( 'srea_save_settings', 'nonce' );

	$post_type = sanitize_text_field( $_POST['option'] );
	$value     = sanitize_text_field( $_POST['value'] );
	$results   = srea_update_template( $post_type, $value );

	if ( $results ) {
		wp_send_json_success(
			array(
				'results' => __( 'Saved!', 'super-reactions' ),
			)
		);
	}

	wp_send_json_error(
		array(
			'results' => __( 'Failed!', 'super-reactions' ),
		)
	);

}
