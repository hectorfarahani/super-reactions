<?php

namespace SREA\Admin;

class Settings_View {
	private $tabs              = array();
	private $cpts              = array();
	public static $tabs_count  = 0;
	public static $views_count = 0;

	public function __construct() {
		$this->custom_post_types();
		$this->define_tabs();
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

	private function tabs() {
		foreach ( $this->tabs as $key => $label ) {
			$classes  = 'srea-tab ';
			$classes .= ! $this::$tabs_count ? 'active' : '';
			?>
			<div class="<?php echo esc_attr( $classes ); ?>" data-view="<?php echo esc_attr( $key ); ?>">
				<span><?php echo esc_html( $label ); ?></span>
			</div>
			<?php
			$this::$tabs_count++;
		}
	}

	private function views() {
		foreach ( $this->tabs as $key => $label ) {
			$classes  = 'srea-view ';
			$classes .= ! $this::$views_count ? 'active' : '';
			?>
			<div id="<?php echo esc_attr( $key ); ?>" class="<?php echo esc_attr( $classes ); ?>">
				<?php $this->show_settings( $key ); ?>
			</div>
			<?php
			$this::$views_count++;
		}
	}

	private function show_settings( $key ) {
		switch ( $key ) {
			case 'posts':
				$this->show_post_settings();
				break;
			case 'wc':
				$this->show_wc_settings();
				break;
			case 'cpt':
				$this->show_cpt_settings();
				break;
			default:
				break;
		}
	}

	private function show_post_settings() {
		$this->render_setting_row( 'post' );
		$this->render_setting_row( 'page' );
	}

	private function show_wc_settings() {
		$this->render_setting_row( 'product' );
	}

	private function show_cpt_settings() {
		foreach ( $this->cpts as $cpt ) {
			$this->render_setting_row( $cpt );
		}
	}

	private function custom_post_types() {
		$args = array(
			'public'   => true,
			'_builtin' => false,
		);

		$this->cpts = array_diff( get_post_types( $args ), array( 'product' ) );
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
		);

		if ( $this->is_wc_active() ) {
			$defaults['wc'] = __( 'WooCommerce' );
		}

		if ( $this->cpts ) {
			$defaults['cpt'] = __( 'Custom Post Types' );
		}

		$tabs = apply_filters( 'srea_admin_settings_view_tabs', $defaults );

		$this->tabs = $tabs;
	}

	private function is_wc_active() {
		return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true );
	}

}
