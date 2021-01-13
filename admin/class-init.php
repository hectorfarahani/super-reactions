<?php

namespace SREA\Admin;

use SREA\Includes\Functions;

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
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	public function assets( $hook ) {
		if ( 'toplevel_page_super-reactions' === $hook ) {
			wp_enqueue_style( 'srea-admin' );
		}
	}

	public function add_menu_page() {
		add_menu_page(
			__( 'Super Reactions', 'super-reactions' ),
			__( 'Reactions', 'super-reactions' ),
			'manage_options',
			'super-reactions',
			array( $this, 'renbder_settings_page' ),
			'dashicons-smiley',
			28
		);
	}

	public function renbder_settings_page() {
		?>
		<form method="POST" action="options.php">
			<?php
			settings_fields( 'super-reactions' );
			do_settings_sections( 'super-reactions' );
			submit_button();
			?>
		</form>
		<?php
	}

	public function register_settings() {

		add_settings_section(
			'srea_settings_section',
			__( 'Super Reaction Settings', 'wp-reaction' ),
			array(),
			'super-reactions'
		);

		add_settings_field(
			'srea_config',
			__( 'Reaction Templates:', 'super-reactions' ),
			array( $this, 'template_selector' ),
			'super-reactions',
			'srea_settings_section'
		);

		 register_setting( 'super-reactions', 'srea_config' );
	}

	public function template_selector() {
		?>
		<div class="srea-settings-wrapper">
			<?php $this->render_setting_row( 'post' ); ?>
			<?php $this->render_setting_row( 'page' ); ?>
			<?php
			if ( class_exists( 'WooCommerce' ) ) {
				$this->render_setting_row( 'product' );
			}
			?>
		</div>
		<?php
	}

	private function render_setting_row( $post_type ) {
		$reactions = Functions\srea_reactions();
		?>
			<div class="srea-template-selector">
				<label for="srea-template-selector-<?php echo esc_attr( $post_type ); ?>"><?php echo ucfirst( $post_type ) . ':'; ?></label>
				<select name="srea_config[active_template][<?php echo esc_attr( $post_type ); ?>]" id="srea-template-selector-<?php echo esc_attr( $post_type ); ?>">
					<option value="0"><?php esc_html_e( 'Disable', 'super-reactions' ); ?></option>
					<?php foreach ( $reactions as $slug => $reaction ) : ?>
						<?php $selected = Functions\srea_get_active_template_slug( $post_type ) === $slug ? 'selected' : ''; ?>
						<option value="<?php echo esc_attr( $slug ); ?>" <?php echo esc_attr( $selected ); ?> ><?php echo esc_html( $reaction['name'] ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

		<?php
	}

}
