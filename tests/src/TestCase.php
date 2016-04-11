<?php
/**
 * This file is part of the "WCM Filterama" package.
 *
 * © 2016 Franz Josef Kaiser
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WCM\Filterama\Tests;

use \Brain\Monkey;

/**
 * Class TestCase
 *
 * @package WCM\Filterama\Tests
 * @author  Franz Josef Kaiser <wecodemore@gmail.com>
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		parent::setUp();
		Monkey::setUpWP();
	}

	protected function tearDown()
	{
		Monkey::tearDownWP();
		parent::tearDown();
	}
}