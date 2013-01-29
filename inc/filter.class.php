<?php
defined( 'ABSPATH' ) OR exit;

add_action( current_filter(), array( 'WCMF_filter', 'init' ) );
/**
 * @package    WCM Filterama
 * @subpackage Taxonomy Filter
 */
final class WCMF_filter extends WCMF_base
{
	protected static $instance;

	public static function init()
	{
		null === self::$instance AND self::$instance = new self;
		return self::$instance;
	}

	public function setup_actions()
	{
		add_filter( "manage_taxonomies_for_{$this->post_type}_columns", array( $this, 'add_columns' ) );
	}

	public function add_columns( $taxonomies )
	{
		return array_merge(
			 $taxonomies
			,$this->taxonomies
		);
	}

	public function get_markup()
	{
		$html = '';
		foreach ( $this->taxonomies as $tax )
		{
			$options = sprintf(
				 '<option value="">%s %s</option>'
				,__( 'View All' )
				,get_taxonomy( $tax )->label
			);
			foreach ( get_terms( $tax ) as $term )
			{
				$selected = isset( $_GET[ $tax ] )
					? selected( $term->slug, $_GET[ $tax ], false )
					: ''
				;
				$parent = '0' !== $term->parent ?: true;
				$options .= sprintf(
					 '<option class="level-%s" value="%s" %s>%s%s</option>'
					,! $parent ? '1' : '0'
					,$term->slug
					,$selected
					,! $parent ? str_repeat( '&nbsp;', 3 ) : ''
					,"{$term->name} ({$term->count})"
				);
			}
			$html .= sprintf(
				 '<select name="%s" id="%s" class="postform">%s</select>'
				,$tax
				,$tax
				,$options
			);
		}

		return print $html;
	}
}