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

	public $get_param = 'match';

	public static function init()
	{
		null === self::$instance AND self::$instance = new self;
		return self::$instance;
	}

	public function setup_actions()
	{
		add_filter( 'pre_get_posts', array( $this, 'tax_filter' ) );
	}

	/**
	 * WP 3.8 already put the submit button there.
	 * @return string
	 */
	public function get_markup()
	{
		return '';
		
		// return printf(
		// 	'%s &nbsp;',
		// 	get_submit_button(
		// 		__( 'Match', 'filterama' ),
		// 		'secondary',
		// 		$this->get_param,
		// 		false
		// 	)
		// );
	}

	/**
	 * Attaches the `tax_query` query var to the $wp_query object.
	 * The "original" $tax_query property inside the $wp_query object
	 * is a reference to the `WP_Tax_Query` class and can not be used
	 * to set arguments for a tax query. Therefore the sub object has
	 * to be taken, transformed and then attached to the `query_vars`
	 * part of the $wp_query object. Else the `relation` argument
	 * gets unset, will not be used and the default is `AND`.
	 * @param  object $query References to the current `$wp_query` object
	 * @return object $query
	 */
	public function tax_filter( &$query )
	{
		if (
			! isset( $_GET[ $this->get_param ] )
			OR ! $query->is_main_query()
		)
			return;

		property_exists( $query->tax_query, 'queries' )
		AND ! empty( $query->tax_query->queries )
			AND $query->set( 'tax_query', array_merge(
				$query->tax_query->queries,
				array( 'relation' => 'OR' )
			) );
		return $query;
	}

# ====== HELPER

	public function get_tax_ids()
	{
		// Get set taxonomy terms
		$taxonomies = array_filter( array_intersect_key(
			$_GET,
			array_flip( $this->taxonomies )
		) );

		$tt_ids = array();
		if (
			! isset( $_GET[ $this->get_param ] )
			OR empty( $taxonomies )
			// Noting to match for a single tax
			OR 1 >= count( $taxonomies )
		)
			return $tt_ids;

		// Get IDs
		foreach ( $taxonomies as $tax => $term_slug )
			$tt_ids[] = term_exists( $term_slug, $tax );

		! empty( $tt_ids )
			AND $tt_ids = wp_list_pluck( $tt_ids, 'term_taxonomy_id' );

		return $tt_ids;
	}
}
