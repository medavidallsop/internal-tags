<?php

/**
 * Plugin name: Internal Tags
 * Plugin URI: https://wordpress.org/plugins/internal-tags/
 * Description: Set internal tags on posts, pages, or custom post types for easier management.
 * Author: David Allsop
 * Author URI: https://davidallsop.com
 * Version: 1.0.1
 * Requires at least: 5.0.0
 * Requires PHP: 7.0.0
 * Domain path: /languages
 * Text domain: internal-tags
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'Internal_Tags' ) ) {

	define( 'INTERNAL_TAGS_BASENAME', plugin_basename( __FILE__ ) );
	define( 'INTERNAL_TAGS_VERSION', '1.0.1' );

	class Internal_Tags {

		public function __construct() {

			require_once __DIR__ . '/includes/class-internal-tags-columns.php';
			require_once __DIR__ . '/includes/class-internal-tags-enqueues.php';
			require_once __DIR__ . '/includes/class-internal-tags-filters.php';
			require_once __DIR__ . '/includes/class-internal-tags-helpers.php';
			require_once __DIR__ . '/includes/class-internal-tags-nag.php';
			require_once __DIR__ . '/includes/class-internal-tags-settings.php';
			require_once __DIR__ . '/includes/class-internal-tags-taxonomy.php';
			require_once __DIR__ . '/includes/class-internal-tags-translation.php';
			require_once __DIR__ . '/includes/class-internal-tags-update.php';
			require_once __DIR__ . '/includes/class-internal-tags-walker.php';

			new Internal_Tags_Columns();
			new Internal_Tags_Enqueues();
			new Internal_Tags_Filters();
			new Internal_Tags_Helpers();
			new Internal_Tags_Nag();
			new Internal_Tags_Settings();
			new Internal_Tags_Taxonomy();
			new Internal_Tags_Translation();
			new Internal_Tags_Update();

		}

	}

	new Internal_Tags();

}
