<?php

namespace SREA\Admin;

class Init {

	private static $instance = null;

	private function __construct() {
		$this->init();
	}

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new Init();
		}

		return self::$instance;
	}

	public function init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
	}

	public function assets( $hook ) {
		if ( 'toplevel_page_super-reactions' === $hook ) {
			wp_enqueue_style( 'srea-admin' );
			wp_enqueue_script( 'srea-admin' );
		}
	}

	public function add_menu_page() {
		add_menu_page(
			__( 'Super Reactions', 'super-reactions' ),
			__( 'Reactions', 'super-reactions' ),
			'manage_options',
			'super-reactions',
			array( ( new Settings_View() ), 'renbder_settings_page' ),
			'dashicons-smiley',
			28
		);
	}

}
