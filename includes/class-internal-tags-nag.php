<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'Internal_Tags_Nag' ) ) {

	class Internal_Tags_Nag {

		public function __construct() {

			add_action( 'admin_notices', array( $this, 'display' ) );

		}

		public function display() {

			// Shows nag on specific pages

			global $pagenow;

			$display_nag = false;

			$settings = Internal_Tags_Helpers::internal_tags_settings();

			if ( isset( $settings['nag'] ) ) {

				if ( '1' == $settings['nag'] ) {

					if ( 'edit-tags.php' == $pagenow || 'term.php' == $pagenow ) {

						if ( isset( $_GET['taxonomy'] ) ) {

							if ( Internal_Tags_Helpers::internal_tags_taxonomy_key() == sanitize_text_field( wp_unslash( $_GET['taxonomy'] ) ) ) {

								$display_nag = true; // Display the nag if enabled and it is an internal tags taxonomy page

							}

						}

					} elseif ( 'options-general.php' == $pagenow ) {

						if ( isset( $_GET['page'] ) ) {

							if ( 'internal-tags' == sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) {

								$display_nag = true; // Display the nag if enabled and it is the internal tags settings page

							}

						}

					}

				}

			}

			if ( true == $display_nag ) {

				// translators: %1$s: sponsor link, %2$s: review link, %3$s: settings link
				echo '<div class="notice notice-success"><p>' . sprintf( esc_html__( 'Hello! I\'m David. I develop this plugin in my spare time. If it has helped you, please consider %1$s (one-time or monthly) and/or %2$s. This helps me commit more time to development and keeps it free. You can disable this nag in %3$s.', 'internal-tags' ), '<a href="https://github.com/sponsors/medavidallsop" target="_blank">' . esc_html__( 'sponsoring me on GitHub', 'internal-tags' ) . '</a>', '<a href="https://wordpress.org/support/plugin/internal-tags/reviews/" target="_blank">' . esc_html__( 'leaving a review', 'internal-tags' ) . '</a>', '<a href="' . esc_url( get_admin_url() . 'options-general.php?page=internal-tags' ) . '">' . esc_html__( 'settings', 'internal-tags' ) . '</a>' ) . '</p></div>';

			}

		}

	}

}
