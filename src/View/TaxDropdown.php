<?php
/**
 * This file is part of the "WCM Filterama" package.
 *
 * © 2012-2016 Franz Josef Kaiser
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WCM\Filterama\View;

use WCM\Filterama\Model\ModelInterface;

/**
 * Class WCM\Filterama\TaxDropDown
 */
final class TaxDropDown implements Viewable
{
	/** @type array */
	private $taxonomies;

	private $walker;

	/**
	 * TaxDropDown constructor.
	 * *
	 *
	 * @param \WCM\Filterama\Model\ModelInterface $taxonomies
	 * @param \Walker                             $walker
	 */
	public function __construct(
		ModelInterface $taxonomies,
		\Walker $walker
	) {
		$this->taxonomies = $taxonomies->getResult();
		$this->walker = $walker;
	}

	/**
	 * @param string $postType
	 * @return mixed|void
	 */
	public function __invoke( $postType = '' )
	{
		foreach ( $this->taxonomies as $tax )
		{
			$queryString = $this->getQueryString(
				$tax,
				get_taxonomy( $tax )
			);

			wp_dropdown_categories( [
				'taxonomy'        => $tax,
				'hide_if_empty'   => true,
				'show_option_all' => $this->getLabel( $tax ),
				'hide_empty'      => true,
				'hierarchical'    => is_taxonomy_hierarchical( $tax ),
				'show_count'      => true,
				'orderby'         => 'name',
				'selected'        =>
					'0' === get_query_var( $queryString ) ?: get_query_var( $queryString ),
				'name'            => $queryString,
				'id'              => $tax,
				'walker'          => $this->walker,
			] );
		}
	}

	/**
	 * @param string $string
	 * @param \stdClass $taxonomy
	 * @return string
	 */
	public function getQueryString( $string, \stdClass $taxonomy )
	{
		return isset( $taxonomy->query_var )
			? $taxonomy->query_var
			: $string;
	}

	public function getLabel( $taxonomy )
	{
		return get_taxonomy( $taxonomy )
			->labels
			->all_items;
	}

	/**
	 * @return mixed
	 */
	public function __toString()
	{
		return $this();
	}
}