<?php
/**
 * This file is part of the "WCM Filterama" package.
 *
 * © 2016 Franz Josef Kaiser
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$vendor = dirname( dirname( __FILE__ ) ).'/vendor/';

if ( ! realpath( $vendor ) )
	return print 'No Composer autoloader found for Unit tests';

#require_once $vendor.'antecedent/patchwork/Patchwork.php';
#require_once $vendor.'phpunit/phpunit/src/Framework/Assert/Functions.php';
require_once $vendor.'autoload.php';

unset( $vendor );