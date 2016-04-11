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
use WCM\Filterama\Model\ScreenResolver;

/**
 * Load translation files
 */
add_action( 'load-edit.php', function() {
	load_plugin_textdomain(
		'filterama',
		false,
		plugin_basename( dirname( __FILE__ ) ).'/lang'
	);
} );

/**
 *
 */
add_action( 'contextual_help', function(
	$contextual_help,
	$screen_id,
	\WP_Screen $screen
) {
	try {
		$postType = ( new ScreenResolver( $screen ) )
				->resolve( 'post_type' );
	}
	catch ( DomainException $exception ) {
		# $exception->getMessage();
		return;
	}

	add_action( 'load-edit.php', function() use ( $postType ) {

	} );
} );
#add_action( 'load-edit.php', array( $this, 'load_files' ), 0 );