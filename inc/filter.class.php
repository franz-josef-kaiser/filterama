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
		add_filter(
			 "manage_taxonomies_for_{$this->post_type}_columns"
			,array( $this, 'add_columns' )
		);
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
		$walker = new WCMF_walker;
		foreach ( $this->taxonomies as $tax )
		{	
			$fullTax = get_taxonomy( $tax );
			$qString = isset($fullTax->query_var) ? $fullTax->query_var : $tax;
			wp_dropdown_categories( array(
				 'taxonomy'        => $tax
				,'hide_if_empty'   => true
				,'show_option_all' => sprintf(
					 get_taxonomy( $tax )->labels->all_items
				 )
				,'hide_empty'      => true
				,'hierarchical'    => is_taxonomy_hierarchical( $tax )
				,'show_count'      => true
				,'orderby'         => 'name'
				,'selected'        => '0' !== get_query_var( $qString )
					? get_query_var( $qString )
					: false
				,'name'            => $qString
				,'id'              => $tax
				,'walker'          => $walker
			) );
		}
	}
}