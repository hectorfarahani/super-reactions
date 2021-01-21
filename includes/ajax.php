<?php

use SREA\Includes\DB;

add_action( 'wp_ajax_srea_handle_post_reactions', 'srea_handle_post_reactions' );
add_action( 'wp_ajax_nopriv_srea_handle_post_reactions', 'srea_handle_post_reactions' );

function srea_handle_post_reactions() {

	if ( ! ( isset( $_POST['n'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['n'] ), 'reaction_check_nonce' ) ) ) {
		wp_send_json_error(
			array( 'message' => __( 'Cheatin’ uh?', 'super-reactions' ) ),
			403
		);
	}

	$reaction = sanitize_text_field( $_POST['srea_action'] ) ?? '';
	$current  = sanitize_text_field( $_POST['current'] ) ?? '';
	$post_id  = sanitize_text_field( $_POST['post_id'] ) ?? 0;
	$slug     = sanitize_text_field( $_POST['slug'] ) ?? 0;

	if ( ! $reaction || ! $post_id ) {
		wp_send_json_error(
			array( 'message' => __( 'Reaction is not set!', 'super-reactions' ) ),
			400
		);
	}

	$user_reaction = srea_get_user_reaction( $post_id, $slug );

	$params['slug']       = $slug;
	$params['reaction']   = $reaction;
	$params['user_id']    = get_current_user_id();
	$params['content_id'] = $post_id;
	$params['ip']         = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

	$db = DB::instance();

	if ( $user_reaction ) {
		if ( $user_reaction === $reaction ) {
			$result = $db->delete( $params );
		} else {
			$result = $db->update( $params );
		}
	} else {
		$params['time']         = current_time( 'mysql' );
		$params['content_type'] = 'post';
		$params['value']        = 1;

		$result = $db->add( $params );
	}

	if ( $result ) {
		wp_send_json_success(
			array(
				'message'   => __( 'Reaction successfully added!', 'super-reactions' ),
				'count'     => $db->count_reaction( $post_id, $slug, $reaction ),
				'old_count' => $db->count_reaction( $post_id, $current, $reaction ),
			),
			200
		);
	}
	// @todo errro handling here. What if !results?
}
