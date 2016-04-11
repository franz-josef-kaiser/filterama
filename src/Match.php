<?php
/**
 * This file is part of the "WCM Filterama" package.
 *
 * © 2012-2016 Franz Josef Kaiser
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WCM\Filterama;

/**
 * Class WCM\Filterama\Match
 */
final class Match extends WCMF_base
{
	public $param = 'match';

	public function setup_actions()
	{
		add_filter( 'pre_get_posts', [ $this, 'tax_filter' ] );
	}

	public function get_markup()
	{
		return printf(
			'%s &nbsp;',
			get_submit_button(
				__( 'Match', 'filterama' ),
				'secondary',
				$this->param,
				false
			)
		);
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
			! isset( $_GET[ $this->param ] )
			OR ! $query->is_main_query()
		)
			return;

		property_exists( $query->tax_query, 'queries' )
		&& ! empty( $query->tax_query->queries )
			and $query->set( 'tax_query', array_merge(
				$query->tax_query->queries,
				[ 'relation' => 'OR' ]
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

		$tt_ids = [];
		if (
			! isset( $_GET[ $this->param ] )
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