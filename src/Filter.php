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
 * Class WCM\Filterama\Filter
 */
final class Filter extends WCMF_base
{
	public function setup_actions()
	{
		add_filter(
			"manage_taxonomies_for_{$this->post_type}_columns",
			[ $this, 'add_columns' ]
		);
	}

	public function add_columns( $taxonomies )
	{
		return array_merge(
			$taxonomies,
			$this->taxonomies
		);
	}

	public function get_markup()
	{
		$walker = new Walker;
		foreach ( $this->taxonomies as $tax )
		{	
			$queryString = $this->getQueryString(
				$tax,
				get_taxonomy( $tax )
			);

			wp_dropdown_categories( [
				'taxonomy'        => $tax,
				'hide_if_empty'   => true,
				'show_option_all' => get_taxonomy( $tax )->labels->all_items,
				'hide_empty'      => true,
				'hierarchical'    => is_taxonomy_hierarchical( $tax ),
				'show_count'      => true,
				'orderby'         => 'name',
				'selected'        =>
					'0' === get_query_var( $queryString ) ?: get_query_var( $queryString ),
				'name'            => $queryString,
				'id'              => $tax,
				'walker'          => $walker,
			] );
		}
	}

	/**
	 * @param string $taxonomy
	 * @param stdClass $object
	 * @return string
	 */
	private function getQueryString( $taxonomy, $object )
	{
		return isset( $object->query_var )
			? $object->query_var
			: $taxonomy;
	}
}