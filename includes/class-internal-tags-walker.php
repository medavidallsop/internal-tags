<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'Internal_Tags_Walker' ) ) {

	class Internal_Tags_Walker extends Walker_Category {

		public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {

			// Manipulates the wp_list_categories() start element HTML markup to include a span and adds the colors inline that have been set for the internal tag, or falls back to the default colors

			$settings = Internal_Tags_Helpers::internal_tags_settings();
			$color_background_default = $settings['color_background'];
			$color_text_default = $settings['color_text'];

			$color_background = get_term_meta( $category->term_id, 'internal_tags_color_background', true );
			$color_background = ( !empty( $color_background ) ? $color_background : $color_background_default );

			$color_text = get_term_meta( $category->term_id, 'internal_tags_color_text', true );
			$color_text = ( !empty( $color_text ) ? $color_text : $color_text_default );

			$output .= '<li><span style="background-color: ' . $color_background . '; color: ' . $color_text . ';">' . $category->name . '</span>';

		}

	}

}
