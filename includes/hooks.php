<?php

namespace SREA\Includes;

use SREA\Includes\Functions;

add_action( 'the_content', 'SREA\Includes\srea_add_reaction_buttons' );

function srea_add_reaction_buttons( $content ) {

	$post_type = get_post_type();

	if ( is_singular( $post_type ) ) {
		$reaction_markup = Functions\srea_get_active_template( $post_type );
		if ( 'disable' != $reaction_markup ) {
			$content .= $reaction_markup;
		}
	}

	return $content;
}
