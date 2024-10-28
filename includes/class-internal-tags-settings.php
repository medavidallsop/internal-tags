<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'Internal_Tags_Settings' ) ) {

	class Internal_Tags_Settings {

		public function __construct() {

			add_action( 'admin_menu', array( $this, 'page' ) );
			add_action( 'admin_init', array( $this, 'register' ) );
			add_filter( 'plugin_action_links_' . INTERNAL_TAGS_BASENAME, array( $this, 'plugins_link' ) );

		}

		public function page() {

			// Add the settings page

			add_options_page(
				esc_html__( 'Internal Tags Settings', 'internal-tags' ),
				esc_html__( 'Internal Tags', 'internal-tags' ),
				'manage_options',
				'internal-tags',
				array( $this, 'page_render' ),
			);

		}

		public function register() {

			// Register the internal_tags_settings option

			register_setting(
				'internal-tags',
				'internal_tags_settings',
				array(
					'sanitize_callback'	=> array( $this, 'sanitize' ),
					'type'				=> 'array',
				)
			);

			// Register a new section

			add_settings_section(
				'internal-tags-section',
				'',
				'',
				'internal-tags'
			);

			// Define the settings fields

			$fields = array(
				array(
					'id'			=> 'post_types',
					'label'			=> esc_html__( 'Post types', 'internal-tags' ),
					'description'	=> esc_html__( 'Post types that internal tags are enabled on. This is a list of all registered post types, excluding some that are likely to have compatibility issues. Only choose post types you understand and can manage in the dashboard.', 'internal-tags' ),
					'type'			=> 'select_multiple',
					'select2'		=> true,
					'options'		=> Internal_Tags_Helpers::all_post_types( true ),
				),
				array(
					'id'			=> 'user_capability',
					'label'			=> esc_html__( 'User capability', 'internal-tags' ),
					// translators: %s: recommended user capability
					'description'	=> wp_kses_post( sprintf( __( 'Capability a user must have to be able to view internal tags. If unsure, use %s.', 'internal-tags' ), '<code>edit_posts</code>' ) ),
					'type'			=> 'select',
					'select2'		=> true,
					'options'		=> Internal_Tags_Helpers::all_user_capabilties(),
				),
				array(
					'id'			=> 'display_mode',
					'label'			=> esc_html__( 'Display mode', 'internal-tags' ),
					'description'	=> esc_html__( 'Mode used to display internal tags. Compact modes reduce the size of the internal tags list and can be scrolled.', 'internal-tags' ),
					'type'			=> 'select',
					'select2'		=> false,
					'options'		=> array(
						'horizontal'			=> esc_html__( 'Horizontal', 'internal-tags' ),
						'horizontal_compact'	=> esc_html__( 'Horizontal compact', 'internal-tags' ),
						'vertical'				=> esc_html__( 'Vertical', 'internal-tags' ),
						'vertical_compact'		=> esc_html__( 'Vertical compact', 'internal-tags' ),
					)
				),
				array(
					'id'			=> 'color_background',
					'label'			=> esc_html__( 'Background color', 'internal-tags' ),
					'description'	=> esc_html__( 'Default background color used on internal tags when the background color is not set.', 'internal-tags' ),
					'type'			=> 'color',
				),
				array(
					'id'			=> 'color_text',
					'label'			=> esc_html__( 'Text color', 'internal-tags' ),
					'description'	=> esc_html__( 'Default text color used on internal tags when the text color is not set.', 'internal-tags' ),
					'type'			=> 'color',
				),
				array(
					'id'			=> 'nag',
					'label'			=> esc_html__( 'Nag', 'internal-tags' ),
					'description'	=> esc_html__( 'Enable or disable the sponsor/review nag notice.', 'internal-tags' ),
					'type'			=> 'checkbox',
				),
			);

			// Register the settings fields

			foreach ( $fields as $field ) {

				add_settings_field(
					$field['id'],
					$field['label'],
					array( $this, 'field_render' ),
					'internal-tags',
					'internal-tags-section',
					array(
						'label_for'	=> $field['id'],
						'field'		=> $field,
					)
				);

			}

		}

		public function plugins_link( $links ) {

			// Add settings link to plugins list, unshift ensures settings link first

			array_unshift( $links, '<a href="' . esc_url( get_admin_url() . 'options-general.php?page=internal-tags' ) . '">' . esc_html__( 'Settings', 'internal-tags' ) . '</a>' );
			return $links;

		}

		public function field_render( $args ) {

			// Render the field based on the passed field type

			$field = $args['field'];
			$settings = Internal_Tags_Helpers::internal_tags_settings();
			$show_description = false;

			if ( 'checkbox' == $field['type'] ) {

				?>

				<input type="checkbox" id="<?php echo esc_attr( $field['id'] ); ?>" name="internal_tags_settings[<?php echo esc_attr( $field['id'] ); ?>]" value="1"<?php echo isset( $settings[ $field['id'] ] ) ? ( checked( $settings[ $field['id'] ], 1, false ) ) : ( '' ); ?>>

				<?php

				$show_description = true;

			} elseif ( 'color' == $field['type'] ) {

				?>

				<input type="text" id="<?php echo esc_attr( $field['id'] ); ?>" class="internal-tags-color-picker" name="internal_tags_settings[<?php echo esc_attr( $field['id'] ); ?>]" value="<?php echo isset( $settings[ $field['id'] ] ) ? esc_attr( $settings[ $field['id'] ] ) : ''; ?>">

				<?php

				$show_description = true;

			} elseif ( 'select' == $field['type'] ) {

				?>

				<select id="<?php echo esc_attr( $field['id'] ); ?>" name="internal_tags_settings[<?php echo esc_attr( $field['id'] ); ?>]"<?php echo ( true == $field['select2'] ? ' class="internal-tags-select2"' : '' ); ?>>
					<?php
					foreach ( $field['options'] as $key => $option ) {
						?>
						<option value="<?php echo esc_html( $key ); ?>"<?php echo isset( $settings[ $field['id'] ] ) ? ( selected( $settings[ $field['id'] ], $key, false ) ) : ( '' ); ?>><?php echo esc_html( $option ); ?></option>
						<?php
					}
					?>
				</select>

				<?php

				$show_description = true;

			} elseif ( 'select_multiple' == $field['type'] ) {

				?>

				<select id="<?php echo esc_attr( $field['id'] ); ?>" name="internal_tags_settings[<?php echo esc_attr( $field['id'] ); ?>][]"<?php echo ( true == $field['select2'] ? ' class="internal-tags-select2"' : '' ); ?> multiple>
					<?php
					foreach ( $field['options'] as $key => $option ) {
						?>
						<option value="<?php echo esc_html( $key ); ?>"<?php echo ( isset( $settings['post_types'] ) && in_array( $key, $settings['post_types'] ) ? ' selected'  : '' ); ?>><?php echo esc_html( $option ); ?></option>
						<?php
					}
					?>
				</select>

				<?php

				$show_description = true;

			}

			if ( true == $show_description ) {

				?>

				<p class="description"><?php echo wp_kses_post( $field['description'] ); ?></p>

				<?php

			}

		}

		public function page_render() {

			// Display the settings form

			?>

			<div id="internal-tags-settings" class="wrap">
				<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
				<form action="options.php" method="post">
					<?php
					settings_fields( 'internal-tags' );
					do_settings_sections( 'internal-tags' );
					submit_button();
					?>
				</form>
			</div>

			<?php

		}

		public function sanitize( $settings ) {

			return map_deep( $settings, 'sanitize_text_field' );

		}

	}

}
