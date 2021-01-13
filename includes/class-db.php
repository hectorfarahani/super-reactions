<?php

namespace SREA\Includes;
class DB {

	public static $instance = null;

	public $table;
	public $charset_collate;

	private function __construct() {
		$this->init();
	}

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new DB();
		}

		return self::$instance;
	}

	public function init() {
		global $wpdb;
		$this->table           = $wpdb->prefix . SREA_SLUG;
		$this->charset_collate = $wpdb->get_charset_collate();
	}

	public function create() {
		global $wpdb;

		$sql = "CREATE TABLE $this->table (
			id INT NOT NULL AUTO_INCREMENT,
			slug tinytext NOT NULL,
			reaction tinytext NOT NULL,
			content_type tinytext NOT NULL,
			content_id INT DEFAULT 0 NOT NULL,
			user_id INT DEFAULT 0 NOT NULL,
			value tinytext NOT NULL,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			ip tinytext NOT NULL,
			PRIMARY KEY  (id)
		) $this->charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	public function add( $args ) {

		global $wpdb;

		$row_data = wp_parse_args(
			$args,
			array(
				'slug'         => 'default',
				'reaction'     => 'plus',
				'content_type' => 'post',
				'content_id'   => 0,
				'user_id'      => 0,
				'value'        => 1,
				'time'         => current_time( 'mysql', false ),
				'ip'           => '0.0.0.0',
			)
		);

		$result = $wpdb->insert( $this->table, $row_data );

		return $result;

	}

	public function delete( $args ) {
		global $wpdb;

		$where = array(
			'user_id'    => $args['user_id'],
			'content_id' => $args['content_id'],
			'slug'       => $args['slug'],
			'reaction' => $args['reaction'],
		);

		$result = $wpdb->delete( $this->table, $where );

		return $result;
	}

	public function update( $args ) {
		global $wpdb;

		$data = array(
			'reaction' => $args['reaction'],
			'ip'       => $args['ip'],
		);

		$where = array(
			'user_id'    => $args['user_id'],
			'content_id' => $args['content_id'],
			'slug'       => $args['slug'],
		);

		$result = $wpdb->update( $this->table, $data, $where );

		return $result;
	}

	public function count_reaction( $content_id, $slug, $reaction ) {
		global $wpdb;
		$sql = $wpdb->prepare(
			"SELECT COUNT(*) FROM $this->table WHERE `content_id`=%d AND `slug`=%s AND `reaction`=%s",
			$content_id,
			$slug,
			$reaction
		);
		return $wpdb->get_var( $sql );
	}

	public function get_user_reaction( $user_id, $content_id, $slug ) {
		global $wpdb;

		$sql = $wpdb->prepare(
			"SELECT `reaction` FROM $this->table WHERE `content_id`=%d AND `user_id`=%d AND `slug`=%s LIMIT 1",
			$content_id,
			$user_id,
			$slug
		);

		$reaction = $wpdb->get_col( $sql );

		if ( empty( $reaction ) ) {
			return 0;
		}

		return $reaction[0];
	}

}
