<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'Internal_Tags_Columns' ) ) {

	class Internal_Tags_Columns {

		public function __construct() {

			$post_types = Internal_Tags_Helpers::internal_tags_post_types();

			if ( !empty( $post_types ) ) {

				foreach ( $post_types as $post_type ) {

					// Add hooks for each internal tags enabled post type

					add_filter( 'manage_edit-' . $post_type . '_columns', array( $this, 'register' ) );
					add_action( 'manage_' . $post_type . '_posts_custom_column', array( $this, 'display' ), 10, 2 );

				}

			}

		}

		public function register( $columns ) {

			if ( isset( $columns['date']  ) ) {

				// Add the internal tags column before the date column, as generally the date column is always included on any post type

				$date_name = $columns['date'];
				unset( $columns['date'] );
				$columns['internal_tags'] = esc_html__( 'Internal Tags', 'internal-tags' );
				$columns['date'] = $date_name;

			} else {

				// If for some reason the date column is not available add the internal tags column to the end as a fallback

				$columns['internal_tags'] = esc_html__( 'Internal Tags', 'internal-tags' );

			}

			return $columns;

		}

		public function display( $column_name, $post_id ) {

			// Display the internal tags in the column

			if ( 'internal_tags' == $column_name ) {

				Internal_Tags_Helpers::internal_tags_display( $post_id );

			}

		}

	}

}
