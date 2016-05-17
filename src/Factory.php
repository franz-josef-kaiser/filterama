<?php
/**
 * This file is part of the "WCM Filterama" package.
 *
 * © 2016 Franz Josef Kaiser
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WCM\Filterama;

use WCM\Filterama\Model\ResolverInterface;

class Factory
{
	private $postType;

	public function __construct( ResolverInterface $resolver )
	{
		$this->postType = $resolver->resolve( 'post_type' );
	}

	protected function create( $type )
	{
	}
}