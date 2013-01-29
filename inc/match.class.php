<?php
defined( 'ABSPATH' ) OR exit;

add_action( current_filter(), array( 'WCMF_match', 'init' ) );
/**
 * @package    WCM Filterama
 * @subpackage Taxonomy Match
 */
final class WCMF_match extends WCMF_base
{
	protected static $instance;

	public static function init()
	{
		null === self::$instance AND self::$instance = new self;
		return self::$instance;
	}

	public function setup_actions()
	{
		add_filter( 'posts_where' , array( $this, 'set_where' ) );
	}

	public function get_markup()
	{
		$html = get_submit_button(
			 __( 'Match', 'filterarma_textdomain' )
			,'secondary'
			,'match'
			,false
		);
		return print "{$html} &nbsp;";
	}

	public function set_where( $where )
	{
		global $wpdb;
		$tt_ids = $this->get_tax_ids();
		if ( empty( $tt_ids ) )
			return $where;

		$where = $wpdb->prepare(
		   "AND wp_posts.post_type = '{$this->post_type}' 
			AND ID IN (
				SELECT object_id
				FROM {$wpdb->term_relationships}
				WHERE term_taxonomy_id
				IN (". implode(',', array_map('intval', $tt_ids)) . ")
			)"
		);

		return $where;
	}

# ====== HELPER

	public function get_tax_ids()
	{
		$param = 'match';

		// Get set taxonomy terms
		$taxonomies = array_filter( array_intersect_key(
			 $_GET
			,array_flip( $this->taxonomies )
		) );

		$tt_ids = array();
		if (
			! isset( $_GET[ $param ] )
			OR empty( $_GET[ $param ] )
			OR empty( $taxonomies )
		)
			return $tt_ids;

		// Get IDs
		foreach ( $taxonomies as $tax => $term_slug )
			$tt_ids[] = term_exists( $term_slug, $tax );

		! empty( $tt_ids ) AND $tt_ids = wp_list_pluck( $tt_ids, 'term_taxonomy_id' );

		return $tt_ids;
	}
}
