<?php
defined( 'ABSPATH' ) OR exit;
/**
 * Plugin Name: (WCM) Admin Post List Taxonomy Filter
 * Plugin URI:  http://example.com
 * Description: Adds a taxonomy filter in the admin list page for a custom post type.
 * Version:     0.1
 * Author:      Franz Josef Kaiser <wecodemore@gmail.com>
 * Author URI:  http://example.com
 * License:     MIT
 *
 * Originally written by: Mike Schinkel - http://mikeschinkel.com/custom-workpress-plugins
 * @link http://wordpress.stackexchange.com/posts/582/
 */

add_action( 'plugins_loaded', array( 'WCM_Admin_PT_List_Tax_Filter', 'init' ) );
/**
 *
 */
class WCM_Admin_PT_List_Tax_Filter
{
	private static $instance;

	public $post_type;

	public $taxonomies;

	public $new_cols = array();

	static function init()
	{
		is_null( self :: $instance ) AND self :: $instance = new self;
		return self :: $instance;
	}

	public function __construct()
	{
		add_action( 'load-edit.php', array( $this, 'setup' ) );
	}

	public function setup()
	{
		add_action( current_filter(), array( $this, 'setup_vars' ), 20 );

		add_action( 'restrict_manage_posts', array( $this, 'get_select' ) );

		add_filter( "manage_taxonomies_for_{$this->post_type}_columns", array( $this, 'add_columns' ) );

		#add_filter( "manage_{$this->post_type}_posts_columns", array( $this, 'add_column' ), 10, 2 );
		#add_filter( "manage_{$this->post_type}_posts_custom_column", array( $this, 'add_column_content' ), 10, 2 );
	}

	public function setup_vars()
	{
		$this->post_type = get_current_screen()->post_type;
		# $pt_obj = get_post_type_object( $this->post_type );
		#wp_filter_object_list( $taxonomies, array( 'show_admin_column' => true ), 'and', 'name' );
		$this->taxonomies = array_diff(
			 get_object_taxonomies( $this->post_type )
			,get_taxonomies( array( 'show_admin_column' => 'false' ) )
		);
		var_dump( $this->taxonomies );
	}

	public function add_columns( $taxonomies )
	{
		return array_merge(
			 $taxonomies
			,$this->taxonomies
		);
	}

	/**
	 * Filters out built in taxonomies
	 * @param array $tax
	 */
	public function filter_post_tax( $tax )
	{
		$builtin_taxonomies = get_taxonomies( array(
			 '_builtin' => true
			#,'object_type' => $this->post_type
		) );
		#in_array( $this->post_type, $GLOBALS['wp_taxonomies'][ $tax ]->object_type );
		if ( 'post' !== $this->post_type )
			return;

		$this->taxonomies = array_diff(
			 $this->taxonomies
			,array( 'category', 'post_tag' )
		);
	}

	/**
	 * Select form element to filter the post list
	 * @return string HTML
	 */
	public function get_select()
	{
		$html = '';
		foreach ( $this->taxonomies as $tax )
		{
			$options = sprintf(
				'<option value="">View All %s</option>'
				,get_taxonomy( $tax )->label
			);
			$class = is_taxonomy_hierarchical( $tax ) ? ' class="level-0"' : '';
			foreach ( get_terms( $tax ) as $taxon )
			{
				$options .= sprintf(
					 '<option %s%s value="%s">%s%s</option>'
					,isset( $_GET[ $tax ] ) ? selected( $taxon->slug, $_GET[ $tax ], false ) : ''
					,'0' !== $taxon->parent ? ' class="level-1"' : $class
					,$taxon->slug
					,'0' !== $taxon->parent ? str_repeat( '&nbsp;', 3 ) : ''
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
	 * Adds the custom columns to the WP_List_Table
	 * @param  array  $cols Default columns or added by other plugins
	 * @param  string $post_type
	 * @return array
	 */
	public function add_column( $cols, $post_type )
	{
		foreach ( $this->taxonomies as $tax )
		{
			! in_array( $tax, $cols ) AND $this->new_cols[ $tax ] = get_taxonomy( $tax )->label;
		}

		return array_merge(
			$cols
			,$this->new_cols
		);
	}

	/**
	 * Content for a column
	 * @param  string $col_id  Taxonomy slug
	 * @param  int    $post_id
	 * @return string Linked Terms
	 */
	public function add_column_content( $col_id, $post_id )
	{
		timer_start();
		if ( ! is_array( $terms = get_the_terms( $post_id, $col_id ) ) )
			return print '&mdash;';
		timer_stop(1,10);

		foreach ( $terms as $key => $term )
		{
			$terms[ $key ] = sprintf(
				'<a href="%s">%s</a>'
				,get_term_link( $term, $col_id )
				,$term->name
			);
		}

		return print implode( ", ", array_filter( $terms ) );
	}
}