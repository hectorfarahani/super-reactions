<?php

function srea_get_option( string $option, $default = null ) {
	$options = get_option( 'srea_config', array() );
	return $options[ $option ] ?? $default;
}

function srea_update_option( $option, $new_value ) {
	$config            = get_option( 'srea_config', array() );
	$config[ $option ] = $new_value;
	return update_option( 'srea_config', $config );
}

function srea_update_template( $post_type, $new_value ) {
	$config = get_option( 'srea_config', array() );
	$config['active_template'][ $post_type ] = $new_value;
	return update_option( 'srea_config', $config );
}
