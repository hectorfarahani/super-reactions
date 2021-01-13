<?php

namespace SREA\Includes;
class SREA_Reactions {

	private $reactions;

	public function __construct() {
		$this->add_defaults();
	}

	public function add( $slug, $args ) {

		$defaults = array(
			'type'             => 'text',
			'buttons'          => array(
				'plus' => array(
					'view' => '---',
					'text' => __( 'Button is not set!', 'wp-ractions' ),
				),
			),
			'content_callback' => 'srea_get_default_template',
		);

		$args = wp_parse_args( $args, $defaults );

		$this->reactions[ $slug ] = $args;
	}

	public function add_defaults() {
		$default = array(
			'name'    => __( 'Default', 'super-reactions' ),
			'buttons' => array(
				'plus'  => array(
					'view' => '+',
					'text' => __( 'Plus', 'wp-ractions' ),
				),
				'minus' => array(
					'view' => '-',
					'text' => __( 'Minus', 'wp-ractions' ),
				),
			),
		);

		$this->add( 'default', $default );

		$thumbs = array(
			'name'    => __( 'Thumbs', 'super-reactions' ),
			'type'    => 'emoji',
			'buttons' => array(
				'like'    => array(
					'view' => '👍',
					'text' => __( 'Like', 'wp-ractions' ),
				),
				'dislike' => array(
					'view' => '👎',
					'text' => __( 'Dislike', 'wp-ractions' ),
				),
			),
		);

		$this->add( 'thumbs', $thumbs );

		$heart = array(
			'name'    => __( 'Heart', 'super-reactions' ),
			'type'    => 'emoji',
			'buttons' => array(
				'like' => array(
					'view' => '❤️',
					'text' => __( 'Like', 'wp-ractions' ),
				),
			),
		);

		$this->add( 'heart', $heart );

	}

	public function get_all() {
		return $this->reactions;
	}

	public function get_reaction( $slug ) {
		$reactions = $this->reactions;
		return $reactions[ $slug ];
	}
}
