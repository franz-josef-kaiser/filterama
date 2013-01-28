<?php
defined( 'ABSPATH' ) OR exit;
/**
 * Plugin Name:  (WCM) Filterama
 * Plugin URI:   http://example.com
 * Description:  Adds one taxonomy filter/drop-down/select box for each taxonomy attached to a (custom) post types list in the admin post list page.
 * Version:      0.3.2
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
		add_action( 'restrict_manage_posts', array( $this, 'get_markup_tax_select' ) );
		add_filter( "manage_taxonomies_for_{$this->post_type}_columns", array( $this, 'add_columns' ) );

		// ALL or ANY
		add_action( 'restrict_manage_posts', array( $this, 'get_markup_match' ) );
		add_filter( 'posts_where' , array( $this, 'sql_where_match' ) );
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


	/**
	 * @param  array $taxonomies
	 * @return array
	 */
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
	public function get_markup_tax_select()
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

	/**
	 * MarkUp for the "ALL or ANY" match select
	 * @return string $html HTML
	 */
	public function get_markup_match()
	{
		$html = get_submit_button(
			 __( 'Match', 'filterarma_textdomain' )
			,'secondary'
			,'match'
			,false
		);
		return print "{$html} &nbsp;";
	}

	/**
	 * Intercept filter behavior to match ANY or ALL selected terms
	 * @param  string $where WHERE SQL clause
	 * @return string SQL
	 */
	public function sql_where_match( $where )
	{
		global $wpdb;
		$tt_ids = $this->get_tax_ids();
		if ( empty( $tt_ids ) )
			return $where;

		$where .= $wpdb->prepare(
			 " AND ID IN (
			    SELECT object_id
			    FROM {$wpdb->term_relationships}
			    WHERE term_taxonomy_id
			    IN (%s)
			 )"
			,implode( ",", $tt_ids )
		);

		return $where;
	}


	/**
	 * Intersects the $_GET params with our custom taxonomies
	 * @return array $tt_ids IDs of terms of match
	 */
	public function get_tax_ids()
	{
		$param = 'match';

		// Get set taxonomy terms
		$taxonomies = array_filter( array_intersect_key(
			$_GET
			,array_flip( $this->taxonomies )
		) );

		$tt_ids = array();
		if (
			! isset( $_GET[ $param ] )
			OR empty( $_GET[ $param ] )
			OR empty( $taxonomies )
		)
			return $tt_ids;

		// Get IDs
		foreach ( $taxonomies as $tax => $term_slug )
			$tt_ids[] = term_exists( $term_slug, $tax );

		! empty( $tt_ids ) AND $tt_ids = wp_list_pluck( $tt_ids, 'term_taxonomy_id' );

		return $tt_ids;
	}
}