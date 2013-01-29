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
		add_action( current_filter(), array( $this, 'setup_vars' ) );
		add_action( current_filter(), array( $this, 'setup_actions' ), 20 );
		add_action( 'restrict_manage_posts', array( $this, 'get_markup' ) );
	}

	public function setup_vars()
	{
		! isset( $this->post_type )   AND $this->post_type   = get_current_screen()->post_type;
		! isset( $this->taxonomies )  AND $this->taxonomies  = array_diff(
			 get_object_taxonomies( $this->post_type )
			,get_taxonomies( array(
				'show_admin_column' => false
			 ) )
		);
		! isset( $this->post_status ) AND $this->post_status = get_post_stati( array(
			 'show_in_admin_status_list' => true
			,'show_in_admin_all_list'    => true
		) );
	}

	abstract function setup_actions();

	abstract function get_markup();
}