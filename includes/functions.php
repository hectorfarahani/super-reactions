<?php

use SREA\Includes\DB;
use SREA\Includes\SREA_Reactions;

function srea_get_reaction( $slug ) {
	return ( new SREA_Reactions() )->get_reaction( $slug );
}

function srea_reactions() {
	return ( new SREA_Reactions() )->get_all();
}

function srea_get_count( $id = 0, $slug = '', $reaction = '' ) {
	$db = DB::instance();
	return $db->count_reaction( $id, $slug, $reaction );
}

function srea_get_user_reaction( $post_id, $slug ) {
	if ( is_user_logged_in() ) {
		$db = DB::instance();
		return $db->get_user_reaction( get_current_user_id(), $post_id, $slug );
	}

}

function srea_logo( $width, $height ) {
	ob_start();
	?>
	<img
	src="<?php echo SREA_IMG_ASSETS . 'logo.png'; ?>"
	alt="Super Reaction Plugin logo"
	width="<?php echo esc_attr( $width ); ?>"
	height="<?php echo esc_attr( $height ); ?>"
	>
	<?php
	echo ob_get_clean();
}

function srea_template( string $reaction_slug = 'default' ) {
	echo srea_get_template( $reaction_slug );
}

function srea_active_template( string $section = 'default' ) {
	echo srea_get_active_template( $section );
}
