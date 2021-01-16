<?php

function srea_get_template( string $reaction_slug ) {
	if ( ! $reaction_slug ) {
		return false;
	}

	$reaction = srea_get_reaction( $reaction_slug );
	$template = call_user_func_array( $reaction['content_callback'], array( get_the_ID(), get_post_type(), $reaction, $reaction_slug ) );

	return apply_filters( 'srea_filter_template_markup', $template, $reaction_slug );
}

function srea_get_active_template_slug( string $section ) {
	$active_template = srea_get_option( 'active_template', array() );
	return apply_filters( 'srea_filter_active_template_slug', $active_template[ $section ] ?? '' );
}

function srea_get_active_template( string $section ) {
	return srea_get_template( srea_get_active_template_slug( $section ) );
}

function srea_get_default_template( $post_id, $post_type, $reaction, $reaction_slug ) {

	$user_reaction = srea_get_user_reaction( $post_id, $reaction_slug );
	ob_start();
	?>
	<div
	class="srea srea-template srea-single srea-single-<?php echo esc_attr( $post_id ); ?>"
	data-srea-slug="<?php echo esc_attr( $reaction_slug ); ?>"
	data-srea-post-id="<?php echo esc_attr( $post_id ); ?>"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'reaction_check_nonce' ) ); ?>"
	data-reacted="<?php echo esc_attr( $user_reaction ) ?: ''; ?>"
	>
		<?php foreach ( $reaction['buttons'] as $button_slug => $button ) : ?>
			<div class="srea-reaction-item">
				<button
				type="button"
				class="srea-button srea-button-<?php echo esc_attr( $reaction_slug ); ?>"
				data-srea-action="<?php echo esc_attr( $button_slug ); ?>"
				>
					<?php echo esc_html( $button['view'] ); ?>
				</button>
				<span class="srea-template-count">
					<?php echo esc_html( srea_get_count( $post_id, $reaction_slug, $button_slug ) ); ?>
				</span>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
}
