<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'Internal_Tags_Enqueues' ) ) {

	class Internal_Tags_Enqueues {

		public function __construct() {

			// Remember to hide the descriptions under taxonomy fields

			add_action( 'admin_enqueue_scripts', array( $this, 'assets_admin' ) );

		}

		public function assets_admin() {

			global $pagenow;

			// Enqueue global admin assets

			wp_enqueue_script( 'jquery' );

			wp_enqueue_script(
				'internal-tags-admin',
				plugins_url( 'assets/js/admin.min.js', __DIR__ ),
				array(
					'jquery',
					'wp-color-picker',
				),
				INTERNAL_TAGS_VERSION,
				true
			);

			wp_enqueue_style(
				'internal-tags-admin',
				plugins_url( 'assets/css/admin.css', __DIR__ ),
				array(),
				INTERNAL_TAGS_VERSION,
				'all'
			);

			// Enqueue settings admin assets

			if ( 'options-general.php' == $pagenow ) {

				if ( isset( $_GET['page'] ) ) {

					if ( 'internal-tags' == $_GET['page'] ) {

						wp_enqueue_style( 'wp-color-picker' );

						wp_enqueue_script(
							'internal-tags-select2',
							plugins_url( 'libraries/select2/js/select2.min.js', __DIR__ ),
							array(
								'jquery',
							),
							INTERNAL_TAGS_VERSION,
							true
						);

						wp_enqueue_style(
							'internal-tags-select2',
							plugins_url( 'libraries/select2/css/select2.min.css', __DIR__ ),
							array(),
							INTERNAL_TAGS_VERSION,
							'all'
						);

					}

				}

			}

			// Enqueue taxonomy term admin assets

			if ( 'term.php' == $pagenow ) {

				if ( isset( $_GET['taxonomy'] ) ) {

					if ( Internal_Tags_Helpers::internal_tags_taxonomy_key() == $_GET['taxonomy'] ) {

						wp_enqueue_style( 'wp-color-picker' );

					}

				}

			}

		}

	}

}
