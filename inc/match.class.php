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
		add_filter( 'pre_get_posts', array( $this, 'tax_filter' ) );
	}

	public function get_markup()
	{
		$html = get_submit_button(
			 __( 'Match', 'filterama' )
			,'secondary'
			,'match'
			,false
		);
		return print "{$html} &nbsp;";
	}

	public function tax_filter( &$query )
	{
		property_exists( $query->tax_query, 'queries' ) AND $tax_query = array_merge(
			 $query->tax_query->queries
			,array( 'relation' => 'OR' )
		);
		$query->set( 'tax_query', $tax_query );
		return $query;
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
			// Noting to match for a single tax
			OR 1 >= count( $taxonomies )
		)
			return $tt_ids;

		// Get IDs
		foreach ( $taxonomies as $tax => $term_slug )
			$tt_ids[] = term_exists( $term_slug, $tax );

		! empty( $tt_ids ) AND $tt_ids = wp_list_pluck( $tt_ids, 'term_taxonomy_id' );

		return $tt_ids;
	}
}