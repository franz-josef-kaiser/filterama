<?php
/**
 * Plugin Name:  (WCM) Filterama
 * Plugin URI:   https://github.com/franz-josef-kaiser/filterama
 * Description:  Adds one taxonomy filter/drop-down/select box for each taxonomy attached to a (custom) post types list in the admin post list page.
 * Version:      1.2
 * Author:       Franz Josef Kaiser <wecodemore@gmail.com>
 * Author URI:   http://unserkaiser.com
 * Contributors: userabuser, kai-ser
 * License:      MIT
 */

/**
 * This file is part of the "WCM Filterama" package.
 *
 * © 2012-2016 Franz Josef Kaiser
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WCM\Filterama;

use WCM\Filterama\Exception\DomainException;
use WCM\Filterama\Model\PostStatus;
use WCM\Filterama\Model\ScreenResolver;
use WCM\Filterama\Model\Taxonomies;
use WCM\Filterama\View\TaxDropDown;
use WCM\Filterama\View\Submit;
use WCM\Filterama\View\Walker;

if ( file_exists( __DIR__.'/vendor/autoload.php' ) )
	require_once __DIR__.'/vendor/autoload.php';


/**
 * Load translation files
 */
add_action( 'load-edit.php', function() {
	load_plugin_textdomain(
		'filterama',
		false,
		plugin_basename( __DIR__ ).'/lang'
	);
} );

add_action( 'wp_loaded', function() {
	register_taxonomy( 'exampletax', 'post', [
		'labels' => [
			'name'          => 'Example Term',
			'singular_name' => 'Example Terms',
			'all_items'     => 'All Example Terms',
		],
		'show_ui'           => TRUE,
		'show_in_menu'      => TRUE,
		'show_in_nav_menus' => TRUE,
		'show_admin_column' => TRUE,
	] );
} );

/**
 *
 */
add_action( 'load-edit.php', function()
{
	$screen = new ScreenResolver( get_current_screen() );
	new Factory( $screen );

	try {
		$status     = new PostStatus( $screen );
		$taxonomies = new Taxonomies( $screen );
	}
	catch ( DomainException $exception ) {
		// @TODO Log
		return;
	}

	$tax = $taxonomies->getResult();
	add_action(
		#"manage_taxonomies_for_{$postType}_columns",
		"manage_taxonomies_for_post_columns",
		function( $taxonomies, $postType ) use ( $tax ) {
			return array_merge(
				$taxonomies,
				$tax
			);
		}, 10, 2
	);

	add_action(
		'pre_get_posts',
		[ new Match( 'match_action' ), 'setTaxQuery' ]
	);

	add_filter( 'request', function( Array $vars ) use ( $status ) {
		if ( isset( $vars['post_status'] ) ) {
			$vars['post_status'] += $status->getResult();
		}

		return $vars;
	} );

	add_action(
		'restrict_manage_posts',
		( new Submit() )
	);
	add_action(
		'restrict_manage_posts',
		( new TaxDropDown( $taxonomies, new Walker ) )
	);

	#$format = new TaxDropDown( $status, new Walker );
	#add_action( 'restrict_manage_posts', $format );
}, 10, 3 );