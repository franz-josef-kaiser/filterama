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
		foreach ( $this->taxonomies as $tax )
		{
			$options = array(
				 'taxonomy'        => $tax
				,'show_option_all' => sprintf(
					 '%s %s'
					,__( 'View All', 'filterama' )
					,get_taxonomy( $tax )->label
				 )
				,'hide_empty'   => 0
				,'hierarchical' => is_taxonomy_hierarchical( $tax )
				,'show_count'   => 1
				,'orderby'      => 'name'
				,'selected'     => $tax
			);
			wp_dropdown_categories( $options );
		}
	}
}