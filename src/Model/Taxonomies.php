<?php
/**
 * This file is part of the "WCM Filterama" package.
 *
 * © 2012-2016 Franz Josef Kaiser
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WCM\Filterama\Model;

/**
 * Class WCM\Filterama\Model\Taxonomies
 */
class Taxonomies implements ModelInterface
{
	private $postType;

	public function __construct( ResolverInterface $screen )
	{
		$this->postType = $screen->resolve( 'post_type' );
	}

	public function getData()
	{
		return array_diff(
			$this->getPostTypeTaxonomies(),
			$this->getCustomTaxonomies(),
			$this->getCoreTaxonomies()
		);
	}

	/**
	 * @return mixed
	 */
	private function getPostTypeTaxonomies()
	{
		return get_object_taxonomies( $this->post_type );
	}

	/**
	 * @return mixed
	 */
	private function getCustomTaxonomies()
	{
		return get_taxonomies( [
			'show_admin_column' => false,
		] );
	}

	/**
	 * Hard coded taxonomy selects
	 * @return array
	 */
	private function getCoreTaxonomies()
	{
		return [
			'category',
			'link_category',
			'post_tag',
		];
	}
}