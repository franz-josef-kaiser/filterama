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

	public function __construct()
	{
		$this->post_type  = get_current_screen()->post_type;
		$this->taxonomies = array_diff(
			 get_object_taxonomies( $this->post_type )
			,get_taxonomies( array(
				'show_admin_column' => false
			 ) )
		);

		add_action( current_filter(), array( $this, 'setup_actions' ), 20 );
		add_action( 'restrict_manage_posts', array( $this, 'get_markup' ) );
	}

	abstract function setup_actions();

	abstract function get_markup();
}