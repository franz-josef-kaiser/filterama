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
final class Match
{
	private $key;

	public function __construct( $key = 'match' )
	{
		$this->key = $key;
	}

	/**
	 * Attaches the `tax_query` query var to the $wp_query object.
	 * The "original" $tax_query property inside the $wp_query object
	 * is a reference to the `WP_Tax_Query` class and can not be used
	 * to set arguments for a tax query. Therefore the sub object has
	 * to be taken, transformed and then attached to the `query_vars`
	 * part of the $wp_query object. Else the `relation` argument
	 * gets unset, will not be used, and the default is `AND`.
	 * @param  \WP_Query $query References to the current `$wp_query` object
	 * @return \WP_Query $query
	 */
	public function setTaxQuery( \WP_Query &$query )
	{
		if (
			! isset( $_GET[ $this->key ] )
			OR ! $query->is_main_query()
		)
			return;

		if (
			property_exists( $query->tax_query, 'queries' )
			&& ! empty( $query->tax_query->queries )
		)
			$query->set( 'tax_query', array_merge(
				$query->tax_query->queries,
				[ 'relation' => 'OR' ]
			) );
	}
}