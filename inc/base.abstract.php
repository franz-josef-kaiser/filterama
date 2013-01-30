<?php
defined( 'ABSPATH' ) OR exit;

/**
 * @package    WCM Filterama
 * @subpackage Abstract/Base
 */
abstract class WCMF_base
{
	public $post_type;

	public $taxonomies;

	public $post_status;

	public function __construct()
	{
		add_action( current_filter(), array( $this, 'setup_vars' ), 15 );
		add_action( current_filter(), array( $this, 'setup_actions' ), 20 );
		add_action( 'restrict_manage_posts', array( $this, 'get_markup' ) );
	}

	public function setup_vars()
	{
		! isset( $this->post_type )  AND $this->post_type   = get_current_screen()->post_type;
		! isset( $this->taxonomies ) AND $this->taxonomies  = array_diff(
			 get_object_taxonomies( $this->post_type )
			,get_taxonomies( array(
				'show_admin_column' => false
			 ) )
		);
		if ( ! isset( $this->post_status ) )
		{
			$args = array(
				 'private'                   => false
				,'internal'                  => false
				,'exclude_from_search'       => false
				,'show_in_admin_all_list'    => true
				,'show_in_admin_status_list' => true
			);
			current_user_can(
				get_post_type_object( $this->post_type )
					->cap
					->read_private_posts
			)
				AND $args['private'] = true;

			$this->post_status = get_post_stati( $args );
		}
	}

	abstract function setup_actions();

	abstract function get_markup();
}