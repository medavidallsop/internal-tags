<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'Internal_Tags_Update' ) ) {

	class Internal_Tags_Update {

		public function __construct() {

			add_action( 'wp_loaded', array( $this, 'do' ) );

		}

		public function do() {

			// Updates internal tags data conditionally based on the version, or populates internal tags data if installing for the first time

			$version = get_option( 'internal_tags_version' );

			if ( INTERNAL_TAGS_VERSION !== $version ) {

				if ( version_compare( $version, '1.0.0', '<' ) ) {

					$settings = array(
						'post_types'			=> array( 'page', 'post' ),
						'user_capability'		=> 'edit_posts',
						'display_mode'			=> 'horizontal',
						'color_background'		=> '#000000',
						'color_text'			=> '#ffffff',
						'nag'					=> '1',
					);

					update_option( 'internal_tags_settings', $settings );

				}

				update_option( 'internal_tags_version', INTERNAL_TAGS_VERSION );

			}

		}

	}

}
