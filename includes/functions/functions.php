<?php

namespace SREA\Includes\Functions;

use SREA\Includes\DB;
use SREA\Includes\SREA_Reactions;

function srea_get_reaction( $slug ) {
	return ( new SREA_Reactions() )->get_reaction( $slug );
}

function srea_reactions() {
	return ( new SREA_Reactions() )->get_all();
}

function srea_get_config( string $option, $default = false ) {
	$options = get_option( 'srea_config', array() );

	return $options[ $option ] ?? $default;
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
