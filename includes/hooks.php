<?php

namespace SREA\Includes;

use SREA\Includes\Functions;

add_action( 'the_content', 'SREA\Includes\srea_add_reaction_buttons' );

function srea_add_reaction_buttons( $content ) {
	$templates = array(
		Functions\srea_get_active_template_slug( 'post' ),
		Functions\srea_get_active_template_slug( 'page' ),
	);

	if ( in_array( 'disable', $templates, true ) ) {
		return $content;
	}

	$section = is_single() ? 'post' : ( is_page() ? 'page' : '' );

	$reaction_markup = Functions\srea_get_active_template( $section );

	return $content . $reaction_markup;
}
