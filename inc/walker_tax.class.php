<?php
defined( 'ABSPATH' ) OR exit;

class WCMF_walker extends Walker_CategoryDropdown
{
	var $tree_type = 'category';
	var $db_fields = array(
		 'parent' => 'parent'
		,'id'     => 'term_id'
	);
	public $tax_name;

	/**
	 * @see   Walker::start_el()
	 * @param  string $output Passed by reference. Used to append additional content.
	 * @param  object $term   Taxonomy term data object.
	 * @param  int    $depth  Depth of category. Used for padding.
	 * @param  array  $args   Uses 'selected' and 'show_count' keys, if they exist.
	 * @param  int    $id
	 * @return void
	 */
	function start_el( &$output, $term, $depth, $args, $id = 0 )
	{
		$pad = str_repeat( '&nbsp;', $depth * 3 );
		$cat_name = apply_filters( 'list_cats', $term->name, $term );
		$output .= sprintf(
			 '<option class="level-%s" value="%s" %s>%s%s</option>'
			,$depth
			,$term->slug
			,selected(
				 $args['selected']
				,$term->slug
				,false
			 )
			,"{$pad}{$cat_name}"
			,$args['show_count']
				? "&nbsp;&nbsp;({$term->count})"
				: ''
		);
	}
}