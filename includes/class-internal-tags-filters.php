<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'Internal_Tags_Filters' ) ) {

	class Internal_Tags_Filters {

		public function __construct() {

			add_action( 'restrict_manage_posts', array( $this, 'field' ) );
			add_action( 'posts_where', array( $this, 'query' ) );

		}

		public function field( $post_type ) {

			global $pagenow;

			// If on the posts list from one of the internal tags enabled post types

			if ( 'edit.php' == $pagenow && in_array( $post_type, Internal_Tags_Helpers::internal_tags_post_types() ) ) {

				// Display the filter internal tags field

				$filter = '';

				if ( isset( $_GET['internal_tags_filter'] ) && !empty( $_GET['internal_tags_filter'] ) ) {

					$filter = sanitize_text_field( wp_unslash( $_GET['internal_tags_filter'] ) );

				}

				$internal_tags = $this->internal_tags_hierarchical( Internal_Tags_Helpers::internal_tags_taxonomy_key() );

				if ( !empty( $internal_tags ) ) {

					echo '<select name="internal_tags_filter">';

					echo '<option value="">' . esc_html__( 'All internal tags', 'internal-tags' ) . '</option>';

					foreach ( $internal_tags as $internal_tag ) {

						echo '<option value="' . esc_html( $internal_tag->term_id ) . '"' . ( $internal_tag->term_id == $filter ? ' selected' : '' ) . '>' . esc_html( $internal_tag->name ) . '</option>';

						if ( !empty( $internal_tag->children ) ) {

							foreach ( $internal_tag->children as $child ) {

								echo '<option value="' . esc_html( $child->term_id ) . '"' . ( $child->term_id == $filter ? ' selected' : '' ) . '>' . esc_html( $internal_tag->name ) . ' ' . esc_html__( '>', 'internal-tags' ) . ' ' . esc_html( $child->name ) . '</option>';

							}

						}

					}

					echo '<option value="none"' . ( 'none' == $filter ? ' selected' : '' ) . '>' . esc_html__( 'None', 'internal-tags' ) . '</option>';

					echo '</select>';

				}

			}

		}

		public function query( $where ) {

			global $typenow;
			global $wpdb;

			// If the internal tags filter is being used then modify the where clause in the filter query based on internal tags data

			if ( isset( $_GET['internal_tags_filter'] ) && !empty( $_GET['internal_tags_filter'] ) ) {

				$filter = trim( sanitize_text_field( wp_unslash( $_GET['internal_tags_filter'] ) ) );
				$filter = $wpdb->_escape( $filter );

				if ( is_search() && in_array( $typenow, Internal_Tags_Helpers::internal_tags_post_types() ) ) {

					if ( 'none' !== $filter ) {

						$where .= " AND $wpdb->posts.ID IN ( SELECT object_id FROM `{$wpdb->prefix}term_taxonomy` AS term_taxonomy INNER JOIN `{$wpdb->prefix}term_relationships` AS term_relationships ON term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id WHERE term_taxonomy.taxonomy = '" . Internal_Tags_Helpers::internal_tags_taxonomy_key() . "' AND term_taxonomy.term_taxonomy_id = " . $filter . ' )';

					} else {

						$where .= " AND $wpdb->posts.ID NOT IN ( SELECT object_id FROM `{$wpdb->prefix}term_taxonomy` AS term_taxonomy INNER JOIN `{$wpdb->prefix}term_relationships` AS term_relationships ON term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id WHERE term_taxonomy.taxonomy = '" . Internal_Tags_Helpers::internal_tags_taxonomy_key() . "' AND term_taxonomy.term_taxonomy_id LIKE '%' )";

					}

				}

			}

			return $where;

		}

		public function internal_tags_hierarchical( $taxonomy, $parent = 0 ) {

			// Returns an array of internal tags in hierarchical order which can then be used to output all the filter options hierarchically

			$taxonomy = is_array( $taxonomy ) ? array_shift( $taxonomy ) : $taxonomy;

			$terms = get_terms(
				array(
					'taxonomy' => $taxonomy,
					'parent' => $parent
				)
			);

			$children = array();

			foreach ( $terms as $term ) {

				$term->children = $this->internal_tags_hierarchical( $taxonomy, $term->term_id );
				$children[$term->term_id] = $term;

			}

			return $children;

		}

	}

}
