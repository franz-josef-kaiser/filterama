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

use WCM\Filterama\Exception\DomainException;

/**
 * Class WCM\Filterama\Model\Taxonomies
 */
class Taxonomies implements ModelInterface
{
	/** @type string */
	private $postType;

	/**
	 * Taxonomies constructor.
	 * @param \WCM\Filterama\Model\ResolverInterface $screen
	 * @throws \WCM\Filterama\Exception\DomainException
	 */
	public function __construct( ResolverInterface $screen )
	{
		try {
			$this->postType = $screen->resolve( 'post_type' );
		}
		catch ( DomainException $exception ) {
			throw $exception;
		}
	}

	/**
	 * Combine core, post type and custom taxonomies
	 * @return array An array where keys and values are the same
	 */
	public function getResult()
	{
		$result = array_diff(
			$this->getPostTypeTaxonomies(),
			$this->getCustomTaxonomies(),
			$this->getCoreTaxonomies()
		);
		return array_combine( $result, $result );
	}

	/**
	 * All taxonomies assigned to the currently shown post type
	 * @return array
	 */
	protected function getPostTypeTaxonomies()
	{
		return get_object_taxonomies( $this->postType );
	}

	/**
	 * Registered taxonomy names
	 * @return array
	 */
	protected function getCustomTaxonomies()
	{
		return get_taxonomies( [
			'show_admin_column' => false,
		] );
	}

	/**
	 * Hard coded taxonomy names in select elements
	 * @return array
	 */
	protected function getCoreTaxonomies()
	{
		return [
			'category',
			'link_category',
			'post_tag',
		];
	}
}