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

/**
 * Interface Viewable
 * Use this interface to build classes that can get
 * instances directly attached to actions as executables
 * @package WCM\Filterama\View
 */
interface Viewable
{
	/**
	 * Just `echo` whatever Markup you need to output in here
	 * @param string $postType
	 * @return mixed
	 */
	public function __invoke( $postType = '' );

	/**
	 * Proxy for `__invoke()` as it can't take arguments
	 * Use with `return $this();`
	 * @return mixed
	 */
	public function __toString();
}