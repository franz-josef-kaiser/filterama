<?php
/**
 * This file is part of the "WCM Filterama" package.
 *
 * © 2012-2016 Franz Josef Kaiser
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WCM\Filterama;

/**
 * Class WCM\Filterama\Walker
 * @extends Walker_CategoryDropdown
 */
class Walker extends Walker_CategoryDropdown
{
	/** @var string */
	public $tree_type = 'category';

	/** @var array */
	public $db_fields = [
		'parent' => 'parent',
		'id'     => 'term_id'
	];

	/** @var string */
	public $tax_name = '';

	/**
	 * @see    Walker::start_el()
	 * @param  string $output Passed by reference. Used to append additional content.
	 * @param  object $term   Taxonomy term data object.
	 * @param  int    $depth  Depth of category. Used for padding.
	 * @param  array  $args   Uses 'selected' and 'show_count' keys, if they exist.
	 * @param  int    $id
	 * @return void
	 */
	public function start_el( &$output, $term, $depth = 0, $args = array(), $id = 0 )
	{
		$pad = str_repeat(
			'&nbsp;',
			$depth * 3
		);
		$cat_name = apply_filters(
			'list_cats',
			$term->name,
			$term
		);

		$output .= sprintf(
			'<option class="level-%s" value="%s" %s>%s%s</option>',
			$depth,
			$term->slug,
			selected(
				$args['selected'],
				$term->slug,
				false
			),
			"{$pad}{$cat_name}",
			$args['show_count']
				? "&nbsp;&nbsp;({$term->count})"
				: ''
		);
	}
}