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
			$color_background = get_term_meta( $category->term_id, 'internal_tags_color_background', true );
			$color_background = ( !empty( $color_background ) ? $color_background : $color_background_default );

			$color_text_default = $settings['color_text'];
			$color_text = get_term_meta( $category->term_id, 'internal_tags_color_text', true );
			$color_text = ( !empty( $color_text ) ? $color_text : $color_text_default );

			$inline_styles = '';

			if ( !empty( $color_background ) || !empty( $color_text ) ) {

				$inline_styles .= ' style="';

				if ( !empty( $color_background ) ) {

					$inline_styles .= 'background-color: ' . $color_background . ';';

				}

				if ( !empty( $color_text ) ) {

					if ( !empty( $color_background ) ) {

						$inline_styles .= ' ';

					}

					$inline_styles .= 'color: ' . $color_text . ';';

				}

				$inline_styles .= '"';

			}

			$output .= '<li><span title="' . esc_attr( $category->description ) . '"' . wp_kses_post( $inline_styles ) . '>' . esc_html( $category->name ) . '</span>';

		}

	}

}
