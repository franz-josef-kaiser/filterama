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

class Submit implements Viewable
{
	private $key;

	public function __construct( $key = 'match' )
	{
		$this->key = $key;
	}

	/**
	 * @param string $postType
	 * @return mixed|void
	 */
	public function __invoke( $postType = '' )
	{
		printf(
			'%s &nbsp;',
			get_submit_button(
				__( 'Match', 'filterama' ),
				'secondary',
				$this->key,
				false
			)
		);
	}

	/**
	 * @return mixed
	 */
	public function __toString()
	{
		return $this();
	}
}