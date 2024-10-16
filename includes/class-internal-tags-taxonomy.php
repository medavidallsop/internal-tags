<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'Internal_Tags_Taxonomy' ) ) {

	class Internal_Tags_Taxonomy {

		public function __construct() {

			add_action( 'init', array( $this, 'register' ) );
			add_action( Internal_Tags_Helpers::internal_tags_taxonomy_key() . '_edit_form_fields', array( $this, 'term_fields' ) );
			add_action( 'edited_' . Internal_Tags_Helpers::internal_tags_taxonomy_key(), array( $this, 'term_fields_save' ) );

		}

		public function register() {

			// Register the internal tags taxonomy

			$labels = array(
				'name'							=> esc_html__( 'Internal Tags', 'internal-tags' ),
				'singular_name'					=> esc_html__( 'Internal Tag', 'internal-tags' ),
				'menu_name'						=> esc_html__( 'Internal Tags', 'internal-tags' ),
				'all_items'						=> esc_html__( 'All', 'internal-tags' ) . ' ' . esc_html__( 'Internal Tags', 'internal-tags' ),
				'parent_item'					=> esc_html__( 'Parent', 'internal-tags' ) . ' ' . esc_html__( 'Internal Tag', 'internal-tags' ),
				'parent_item_colon'				=> esc_html__( 'Parent', 'internal-tags' ) . ' ' . esc_html__( 'Internal Tag', 'internal-tags' ) . ' ' . esc_html__( ':', 'internal-tags' ),
				'new_item_name'					=> esc_html__( 'New', 'internal-tags' ) . ' ' . esc_html__( 'Internal Tag', 'internal-tags' ),
				'add_new_item'					=> esc_html__( 'Add New', 'internal-tags' ) . ' ' . esc_html__( 'Internal Tag', 'internal-tags' ),
				'edit_item'						=> esc_html__( 'Edit', 'internal-tags' ) . ' ' . esc_html__( 'Internal Tag', 'internal-tags' ),
				'update_item'					=> esc_html__( 'Update', 'internal-tags' ) . ' ' . esc_html__( 'Internal Tag', 'internal-tags' ),
				'view_item'						=> esc_html__( 'View', 'internal-tags' ) . ' ' . esc_html__( 'Internal Tags', 'internal-tags' ),
				'separate_items_with_commas'	=> esc_html__( 'Separate', 'internal-tags' ) . ' ' . strtolower( esc_html__( 'Internal Tags', 'internal-tags' ) ) . ' ' . esc_html__( 'with commas', 'internal-tags' ),
				'add_or_remove_items'			=> esc_html__( 'Add or remove', 'internal-tags' ) . ' ' . strtolower( esc_html__( 'Internal Tags', 'internal-tags' ) ),
				'choose_from_most_used'			=> esc_html__( 'Choose from the most used', 'internal-tags' ),
				'popular_items'					=> esc_html__( 'Popular', 'internal-tags' ) . ' ' . esc_html__( 'Internal Tags', 'internal-tags' ),
				'search_items'					=> esc_html__( 'Search', 'internal-tags' ) . ' ' . esc_html__( 'Internal Tags', 'internal-tags' ),
				'not_found'						=> esc_html__( 'Not Found', 'internal-tags' ),
				'no_terms'						=> esc_html__( 'No', 'internal-tags' ) . ' ' . strtolower( esc_html__( 'Internal Tags', 'internal-tags' ) ),
				'items_list'					=> esc_html__( 'Internal Tags', 'internal-tags' ) . ' ' . esc_html__( 'list', 'internal-tags' ),
				'items_list_navigation'			=> esc_html__( 'Internal Tags', 'internal-tags' ) . ' ' . esc_html__( 'list navigation', 'internal-tags' ),
			);

			register_taxonomy(
				Internal_Tags_Helpers::internal_tags_taxonomy_key(),
				Internal_Tags_Helpers::internal_tags_post_types(),
				array(
					'hierarchical'	=> true,
					'labels'		=> $labels,
					'public'		=> false,
					'show_in_rest'  => true, // Required to ensure the taxonomy is visible in the block editor
					'show_ui'		=> true,
				)
			);

		}

		public function term_fields( $term ) {

			// Add internal tags taxonomy term fields

			$term_id = $term->term_id;
			$color_background = get_term_meta( $term_id, 'internal_tags_color_background', true );
			$color_text = get_term_meta( $term_id, 'internal_tags_color_text', true );

			?>

			<table id="internal-tags-taxonomy-term-fields" class="form-table">
				<tbody>
					<tr class="form-field">
						<th scope="row">
							<label for="internal-tags-color-background"><?php esc_html_e( 'Background Color', 'internal-tags' ); ?></label>
						</th>
						<td>
							<input type="text" id="internal-tags-color-background" class="internal-tags-color-picker" name="internal_tags_color_background" value="<?php echo esc_html( $color_background ); ?>">
							<p class="description">
								<?php
								// translators: %s: settings link
								echo sprintf( esc_html__( 'Sets the tag background color. If not set, the %s is used.', 'internal-tags' ), '<a href="' . esc_url( get_admin_url() . 'options-general.php?page=internal-tags' ) . '" target="_blank">' . esc_html__( 'default color', 'internal-tags' ) . '</a>' );
								?>
							</p>
						</td>
					</tr>
					<tr class="form-field">
						<th scope="row">
							<label for="internal-tags-color-text"><?php esc_html_e( 'Text Color', 'internal-tags' ); ?></label>
						</th>
						<td>
							<input type="text" id="internal-tags-color-text" class="internal-tags-color-picker" name="internal_tags_color_text" value="<?php echo esc_html( $color_text ); ?>">
							<p class="description">
								<?php
								// translators: %s: settings link
								echo sprintf( esc_html__( 'Sets the tag text color. If not set, the %s is used.', 'internal-tags' ), '<a href="' . esc_url( get_admin_url() . 'options-general.php?page=internal-tags' ) . '" target="_blank">' . esc_html__( 'default color', 'internal-tags' ) . '</a>' );
								?>
							</p>
						</td>
					</tr>
				</tbody>
			</table>

			<?php

			wp_nonce_field( 'internal_tags_taxonomy_term_fields_save', 'internal_tags_taxonomy_term_fields_save_nonce' );

		}

		public function term_fields_save( $term_id ) {

			// Save internal tags taxonomy term fields

			if ( isset( $_POST['internal_tags_taxonomy_term_fields_save_nonce'] ) ) { // This also ensures we are only dealing with edits to internal tags and not effecting any other taxonomies

				if ( wp_verify_nonce( sanitize_key( $_POST['internal_tags_taxonomy_term_fields_save_nonce'] ), 'internal_tags_taxonomy_term_fields_save' ) ) {

					if ( isset( $_POST['internal_tags_color_background'] ) ) {

						update_term_meta( $term_id, 'internal_tags_color_background', sanitize_text_field( wp_unslash( $_POST['internal_tags_color_background'] ) ) );

					}

					if ( isset( $_POST['internal_tags_color_text'] ) ) {

						update_term_meta( $term_id, 'internal_tags_color_text', sanitize_text_field( wp_unslash( $_POST['internal_tags_color_text'] ) ) );

					}

				}

			}

		}

	}

}
