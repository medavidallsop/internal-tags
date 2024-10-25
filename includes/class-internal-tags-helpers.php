<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'Internal_Tags_Helpers' ) ) {

	class Internal_Tags_Helpers {

		public static function all_post_types( $exclude_incompatible = true ) {

			// Gets all the registered post types in WordPress

			$post_types = get_post_types();

			// Exclude any post types that are likely to be incompatible with internal tags, currently or potentially in future

			if ( true == $exclude_incompatible ) {

				unset( $post_types['attachment'] );
				unset( $post_types['custom_css'] );
				unset( $post_types['customize_changeset'] );
				unset( $post_types['nav_menu_item'] );
				unset( $post_types['oembed_cache'] );
				unset( $post_types['patterns_ai_data'] );
				unset( $post_types['product_variation'] );
				unset( $post_types['revision'] );
				unset( $post_types['shop_coupon'] );
				unset( $post_types['shop_order'] );
				unset( $post_types['shop_order_placehold'] );
				unset( $post_types['shop_order_refund'] );
				unset( $post_types['user_request'] );
				unset( $post_types['wp_block'] );
				unset( $post_types['wp_font_face'] );
				unset( $post_types['wp_font_family'] );
				unset( $post_types['wp_global_styles'] );
				unset( $post_types['wp_navigation'] );
				unset( $post_types['wp_template'] );
				unset( $post_types['wp_template_part'] );

			}

			// Set the value to be the post type name and ID, this is done because some internal post types from plugins might not set a name label and default to a posts label, e.g. global_product_addon from WooCommerce Product Add-ons, so in this scenario without the ID you have 2 posts types named Posts and wouldn't be able to differentiate between them

			if ( !empty( $post_types ) ) {

				foreach ( $post_types as $post_type ) {

					$post_types[$post_type] = get_post_type_object( $post_type )->labels->name . ' (' . $post_type . ')';

				}

			}

			// Sort the array according to the value not key

			asort( $post_types );

			// Return the post types

			return $post_types;

		}

		public static function all_user_capabilties() {

			// Get all user capabilities by getting all the capabilities of the administrator user role

			$admin_user_caps = get_role( 'administrator' )->capabilities;

			$all_user_caps = array();

			foreach ( $admin_user_caps as $admin_user_cap_key => $admin_user_cap_value ) {

				$all_user_caps[$admin_user_cap_key] = $admin_user_cap_key;

			}

			ksort( $all_user_caps );

			return $all_user_caps;

		}

		public static function string_ends_with( $string, $starts_with ) {

			return substr_compare( $string, $starts_with, -strlen( $starts_with ) ) === 0;

		}

		public static function string_starts_with( $string, $starts_with ) {

			return substr_compare( $string, $starts_with, 0, strlen( $starts_with ) ) === 0;

		}

		public static function internal_tags_display( $post_id ) {

			// Displays the internal tags HTML markup

			$allowed_user_capability = self::internal_tags_user_capability();

			if ( true == current_user_can( $allowed_user_capability ) ) {

				$taxonomy = self::internal_tags_taxonomy_key();

				$terms = wp_get_post_terms(
					$post_id,
					$taxonomy,
					array(
						'fields' => 'ids',
					)
				);

				if ( !empty( $terms ) ) {

					$settings = self::internal_tags_settings();
					$display_mode = ( isset( $settings['display_mode'] ) ? $settings['display_mode'] : 'horizontal' );

					$class = 'internal-tags-display';
					$class .= ( self::string_starts_with( $display_mode, 'horizontal' ) ? ' internal-tags-display-horizontal' : ' internal-tags-display-vertical' );
					$class .= ( self::string_ends_with( $display_mode, 'compact' ) ? ' internal-tags-display-compact' : '' );

					echo '<ul class="' . esc_attr( $class ) . '">';

					$terms = trim( implode( ',', $terms ), ',' );

					wp_list_categories(
						array(
							'hide_empty'	=> false, // This has to be false, if this was true then if an internal tag is not set on any other posts, if that tag is then set on a draft post it displays "No categories" instead of the internal tag
							'include'		=> $terms,
							'taxonomy'		=> $taxonomy,
							'title_li'		=> '', // Blank as populating this results in an unwanted title displaying
							'walker'		=> new Internal_Tags_Walker(), // Manipulates the default wp_list_categories() markup so it can be displayed as required
						)
					);

					echo '</ul>';

				} else {

					echo '—';

				}

			} else {

				esc_html_e( 'You do not have permission to view internal tags.', 'internal-tags' );

			}

		}

		public static function internal_tags_settings() {

			// Returns the internal tags settings data

			return get_option( 'internal_tags_settings' );

		}

		public static function internal_tags_post_types() {

			// Returns the internal tags enabled post types

			$settings = self::internal_tags_settings();
			return ( isset( $settings['post_types'] ) && is_array( $settings['post_types'] ) ? $settings['post_types'] : array() );

		}

		public static function internal_tags_taxonomy_key() {

			// Returns the taxonomy key used for internal tags

			return 'internal_tag';

		}

		public static function internal_tags_user_capability() {

			// Returns the internal tags user capabiltiy allowed to view internal tags

			$settings = self::internal_tags_settings();
			return ( isset( $settings['user_capability'] ) ? $settings['user_capability'] : '' );

		}

	}

}
