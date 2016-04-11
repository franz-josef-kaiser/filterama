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
 * Class WCM\Filterama\Model\PostType
 */
class ScreenResolver implements ResolverInterface
{
	/** @type \WP_Screen */
	private $screen;

	public function __construct( \WP_Screen $screen )
	{
		$this->screen = $screen;
	}

	public function resolve( $property )
	{
		if ( ! isset( $this->screen->{$property} ) )
		{
			throw new DomainException( sprintf(
					'%s was not found',
					$property
			) );
		}

		return $this->screen->{$property};
	}
}