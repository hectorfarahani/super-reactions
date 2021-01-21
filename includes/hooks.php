<?php

add_action( 'the_content', 'srea_add_reaction_buttons' );

function srea_add_reaction_buttons( $content ) {

	$post_type = get_post_type();

	if ( is_singular( $post_type ) ) {
		$reaction_markup = srea_get_active_template( $post_type );
		if ( 'disable' != $reaction_markup ) {
			$content .= $reaction_markup;
		}
	}

	return $content;
}
