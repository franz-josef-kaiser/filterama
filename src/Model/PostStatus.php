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
 * Class WCM\Filterama\Model\PostStatus
 */
class PostStatus implements ModelInterface
{
	public function getData()
	{
		return get_post_stati( [
			'show_in_admin_status_list' => true,
			'show_in_admin_all_list'    => true,
			'exclude_from_search'       => false,
			'internal'                  => false,
			'private'                   => current_user_can(
				get_post_type_object( $this->post_type )
					->cap
					->read_private_posts
			),
		] );
	}
}