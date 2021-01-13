<?php

namespace SREA\Includes\Functions;

function srea_template( string $reaction_slug = 'default' ) {
	echo srea_get_template( $reaction_slug );
}

function srea_active_template( string $section = 'default' ) {
	echo srea_get_active_template( $section );
}
