<?php
/**
 * This file is part of the "WCM Filterama" package.
 *
 * © 2016 Franz Josef Kaiser
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WCM\Filterama\Exception;

class DomainException extends \DomainException
{
	const NONEXISTANT = 1;

	const INACCESSIBLE = 2;

	public function __construct( $message = "", $code = 0, \Exception $previous = null )
	{
		var_dump( debug_backtrace() );
		parent::__construct( $message, $code, $previous );
	}

	public function createMessage( $type )
	{
		$message = "";
		switch ( $type ) {
			case self::NONEXISTANT :
				$message = "[%d] %s is no existing property on this instance of %s";
				break;
			case self::INACCESSIBLE :
				$message = "[%d] %s is no publicly accessible property of this instance of %s";
				break;
			case self::INACCESSIBLE | self::NONEXISTANT :
				$message = "[%d] %s is no existing or publicly accessible property of this instance of %s";
				break;
		}

		return $message;
	}
}