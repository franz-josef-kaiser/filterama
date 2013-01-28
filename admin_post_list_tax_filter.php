<?php
defined( 'ABSPATH' ) OR exit;
/**
 * Plugin Name:  (WCM) Filterama
 * Plugin URI:   http://example.com
 * Description:  Adds one taxonomy filter/drop-down/select box for each taxonomy attached to a (custom) post types list in the admin post list page.
 * Version:      0.2
 * Author:       Franz Josef Kaiser <wecodemore@gmail.com>
 * Author URI:   http://example.com
 * Contributors: userabuser, kai-ser
 * License:      MIT
 */

add_action( 'plugins_loaded', array( 'WCM_Admin_PT_List_Tax_Filter', 'init' ) );
class WCM_Admin_PT_List_Tax_Filter
{
	private static $instance;

	public $post_type;

	public $taxonomies;

	public $new_cols = array();

	static function init()
	{
		null === self :: $instance AND self :: $instance = new self;
		return self :: $instance;
	}

	public function __construct()
	{
		add_action( 'load-edit.php', array( $this, 'setup' ) );
	}

	public function setup()
	{
		add_action( current_filter(), array( $this, 'setup_vars' ), 20 );
		add_action( 'restrict_manage_posts', array( $this, 'get_markup' ) );
		add_filter( "manage_taxonomies_for_{$this->post_type}_columns", array( $this, 'add_columns' ) );

		// ALL or ANY
		# add_action( 'restrict_manage_posts', array( $this, 'all_or_any_markup' ) );
		# add_filter( 'posts_where' , array( $this, 'all_or_any' ) );
	}

	public function setup_vars()
	{
		$this->post_type  = get_current_screen()->post_type;
		$this->taxonomies = array_diff(
			 get_object_taxonomies( $this->post_type )
			,get_taxonomies( array(
				'show_admin_column' => false
			 ) )
		);
	}

	public function add_columns( $taxonomies )
	{
		return array_merge(
			 $taxonomies
			,$this->taxonomies
		);
	}

	/**
	 * Select form elements, used to filter the post list
	 * @return string HTML
	 */
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
			foreach ( get_terms( $tax ) as $taxon )
			{
				$selected = isset( $_GET[ $tax ] )
					? selected( $taxon->slug, $_GET[ $tax ], false )
					: ''
				;
				$parent = '0' !== $taxon->parent ?: true;
				$options .= sprintf(
					 '<option class="level-%s" value="%s" %s>%s%s</option>'
					,! $parent ? '1' : '0'
					,$taxon->slug
					,$selected
					,! $parent ? str_repeat( '&nbsp;', 3 ) : ''
					,"{$taxon->name} ({$taxon->count})"
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

	/**
	 * MarkUp for the "ALL or ANY" match select
	 * @return string $html HTML
	 */
	public function all_or_any_markup()
	{
		$html = '';
		return $html;
	}

	/**
	 * Intercept filter behavior to match ANY or ALL selected terms
	 * @param  string $match WHERE SQL clause
	 * @return string SQL
	 */
	public function all_or_any( $match )
	{
		global $wpdb;
		$array_of_ids = $_GET['match_all'];
		if (
			isset( $array_of_ids )
			AND ! empty( $array_of_ids )
		)
			$match .= $wpdb->prepare(
				 " AND ID IN (SELECT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id IN (%s))"
				,implode( ",", $array_of_ids )
			);

		return $match;
	}
}