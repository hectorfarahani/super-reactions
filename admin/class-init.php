<?php

namespace SREA\Admin;

use SREA\Includes\Functions;

class Init {

	private static $instance = null;
	private $tabs            = array();

	private function __construct() {
		$this->define_tabs();
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
			array( $this, 'renbder_settings_page' ),
			'dashicons-smiley',
			28
		);
	}

	private function tab( $key, $label ) {
		?>
		<div class="srea-tab" data-tab="<?php echo esc_attr( $key ); ?>">
			<span><?php echo esc_html( $label ); ?></span>
		</div>
		<?php

	}

	private function view( $key, $label ) {
		?>
		<div id="<?php echo esc_attr( $key ); ?>" class="srea-view">
			<?php echo 'view of ' . $label; ?>
		</div>
		<?php
	}

	private function tabs() {
		foreach ( $this->tabs as $key => $label ) {
			$this->tab( $key, $label );
		}
	}

	private function views() {
		foreach ( $this->tabs as $key => $label ) {
			$this->view( $key, $label );
		}
	}

	public function renbder_settings_page() {
		?>

		<div class="srea-admin-wrapper">

			<div class="srea-admin-header">
				<div class="srea-logo">
					<?php srea_logo( 100, 100 ); ?>
				</div>
				<div class="srea-admin-title">
					<h1><?php esc_html_e( 'Super Reactions', 'super-reactions' ); ?></h1>
				</div>
			</div>

			<div class="srea-admin-main">

				<div class="srea-tabs">
					<?php $this->tabs(); ?>
				</div>
				<div class="srea-views">
					<?php $this->views(); ?>
				</div>

				<?php wp_nonce_field( 'srea_save_settings' ); ?>
			</div>

		</div>
		<?php
	}

	public function template_selector() {
		?>
		<div class="srea-settings-wrapper">
		<?php
		$args = array(
			'public' => true,
		);

		$post_types = get_post_types( $args );

		// remove attachment from the list
		unset( $post_types['attachment'] );

		foreach ( $post_types as $post_type ) {
			$this->render_setting_row( $post_type );
		}

		?>
		</div>
		<?php
	}

	private function render_setting_row( $post_type ) {
		$reactions = srea_reactions();
		?>
			<div class="srea-template-selector">
				<label for="srea-template-selector-<?php echo esc_attr( $post_type ); ?>"><?php echo ucfirst( $post_type ) . ':'; ?></label>
				<select name="<?php echo esc_attr( $post_type ); ?>" id="srea-template-selector-<?php echo esc_attr( $post_type ); ?>">
					<option value="0"><?php esc_html_e( 'Disable', 'super-reactions' ); ?></option>
					<?php foreach ( $reactions as $slug => $reaction ) : ?>
						<?php $selected = srea_get_active_template_slug( $post_type ) === $slug ? 'selected' : ''; ?>
						<option value="<?php echo esc_attr( $slug ); ?>" <?php echo esc_attr( $selected ); ?> ><?php echo esc_html( $reaction['name'] ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

		<?php
	}

	private function define_tabs() {
		$defaults = array(
			'posts' => __( 'Post & Pages' ),
			'wc'    => __( 'WooCommerce' ),
			'cpt'   => __( 'Custom Post Types' ),
		);

		$tabs = apply_filters( 'srea_admin_settings_view_tabs', $defaults );

		$this->tabs = $tabs;
	}

}
